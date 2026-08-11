<?php

namespace App\Services\PassengerBooking;

use \App\Models\PassengerBooking\Booking;
use App\Services\Audit\AuditLogger;
use App\Services\Notifications\OperationsNotifier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingService
{
    public function __construct(
        private AuditLogger $auditLogger,
        private OperationsNotifier $notifier,
    ) {}

    public function search(array $filters, int $perPage = 25): LengthAwarePaginator
    {
        $query = Booking::query()->forAirline($filters['airline_id'] ?? 0);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['station_code'])) {
            $query->forStation($filters['station_code']);
        }
        if (!empty($filters['region'])) {
            $query->forRegion($filters['region']);
        }
        if (!empty($filters['code'])) {
            $query->searchByCode($filters['code']);
        }
        if (!empty($filters['name'])) {
            $query->searchByName($filters['name']);
        }
        if (isset($filters['is_active'])) {
            $query->where('is_active', (bool) $filters['is_active']);
        }
        if (!empty($filters['priority_min'])) {
            $query->withPriorityAbove((int) $filters['priority_min'] - 1);
        }
        if (!empty($filters['external_ref'])) {
            $query->byExternalRef($filters['external_ref']);
        }
        if (!empty($filters['q'])) {
            $term = $filters['q'];
            $query->where(function ($q) use ($term) {
                $q->where('code', 'like', "%{$term}%")
                  ->orWhere('name', 'like', "%{$term}%")
                  ->orWhere('external_ref', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%");
            });
        }
        if (!empty($filters['effective_on'])) {
            $query->effectiveOn(Carbon::parse($filters['effective_on']));
        }
        if (!empty($filters['created_from']) && !empty($filters['created_to'])) {
            $query->forDateRange([
                Carbon::parse($filters['created_from'])->startOfDay(),
                Carbon::parse($filters['created_to'])->endOfDay(),
            ]);
        }

        $sort = $filters['sort'] ?? 'updated_at';
        $dir = strtolower($filters['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['updated_at', 'created_at', 'code', 'name', 'priority', 'status', 'version'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'updated_at';
        }

        return $query->orderBy($sort, $dir)->paginate($perPage);
    }

    public function findForAirline(int $id, int $airlineId): ?Booking
    {
        return Booking::query()->forAirline($airlineId)->whereKey($id)->first();
    }

    public function create(array $payload): Booking
    {
        if (empty($payload['code'])) {
            $payload['code'] = $this->generateCode($payload);
        }
        $payload['external_ref'] = $payload['external_ref'] ?? Str::uuid()->toString();
        $payload['version'] = 1;

        $entity = Booking::create($payload);
        $errors = $entity->validateOperationalConstraints();
        if ($errors) {
            $entity->setMetadataKey('validation_warnings', $errors);
        }

        $this->auditLogger->logCreated($entity, $payload['created_by'] ?? null);
        $this->notifier->notifyModuleEvent('PassengerBooking', 'created', $entity->toOperationalSummary());
        $this->bustCache($payload['airline_id'] ?? 0);

        return $entity->fresh();
    }

    public function update(Booking $entity, array $payload): Booking
    {
        $before = $entity->toOperationalSummary();
        $entity->fill($payload);
        $entity->bumpVersion($payload);
        $this->auditLogger->logUpdated($entity, $before, $entity->toOperationalSummary(), $payload['updated_by'] ?? null);
        $this->notifier->notifyModuleEvent('PassengerBooking', 'updated', $entity->toOperationalSummary());
        $this->bustCache($entity->airline_id);
        return $entity->fresh();
    }

    public function delete(Booking $entity, int $userId): void
    {
        $summary = $entity->toOperationalSummary();
        $entity->updated_by = $userId;
        $entity->archive('deleted_via_api');
        $entity->delete();
        $this->auditLogger->logDeleted($entity, $userId, $summary);
        $this->notifier->notifyModuleEvent('PassengerBooking', 'deleted', $summary);
        $this->bustCache($entity->airline_id);
    }

    public function bulkAction(string $action, array $ids, int $airlineId, int $userId, array $payload = []): array
    {
        $affected = 0;
        $errors = [];
        $items = Booking::query()->forAirline($airlineId)->whereIn('id', $ids)->get();

        foreach ($items as $entity) {
            try {
                $entity->updated_by = $userId;
                match ($action) {
                    'activate' => $entity->activate(),
                    'deactivate' => $entity->deactivate($payload['reason'] ?? null),
                    'archive' => $entity->archive($payload['reason'] ?? null),
                    'set_priority' => tap($entity, function ($e) use ($payload) {
                        $e->priority = (int) ($payload['priority'] ?? $e->priority);
                        $e->save();
                    }),
                    'set_status' => tap($entity, function ($e) use ($payload) {
                        $e->status = $payload['status'] ?? $e->status;
                        $e->save();
                    }),
                    default => throw new \InvalidArgumentException("Unknown bulk action: {$action}"),
                };
                $affected++;
            } catch (\Throwable $e) {
                $errors[] = ['id' => $entity->id, 'error' => $e->getMessage()];
            }
        }

        $this->bustCache($airlineId);
        return ['affected' => $affected, 'errors' => $errors];
    }

    public function export(array $filters): array
    {
        $filters['per_page_override'] = true;
        $query = Booking::query()->forAirline($filters['airline_id'] ?? 0);
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        return $query->orderBy('code')->limit(5000)->get()->map->toOperationalSummary()->all();
    }

    public function statistics(int $airlineId): array
    {
        $cacheKey = "stats:PassengerBooking:{$airlineId}";
        return Cache::remember($cacheKey, 120, function () use ($airlineId) {
            $base = Booking::query()->forAirline($airlineId);
            return [
                'total' => (clone $base)->count(),
                'active' => (clone $base)->active()->count(),
                'inactive' => (clone $base)->inactive()->count(),
                'archived' => (clone $base)->archived()->count(),
                'high_priority' => (clone $base)->highPriority()->count(),
                'pending_review' => (clone $base)->pendingReview()->count(),
                'by_status' => (clone $base)->select('status', DB::raw('count(*) as c'))
                    ->groupBy('status')->pluck('c', 'status'),
                'by_region' => (clone $base)->select('region', DB::raw('count(*) as c'))
                    ->groupBy('region')->pluck('c', 'region'),
                'avg_priority' => round((float) (clone $base)->avg('priority'), 2),
            ];
        });
    }

    public function timeline(Booking $entity): array
    {
        $history = $entity->getMetadataKey('change_history', []);
        $audits = $entity->auditLogs()->orderByDesc('created_at')->limit(100)->get()->map(function ($log) {
            return [
                'type' => 'audit',
                'action' => $log->action,
                'at' => optional($log->created_at)->toIso8601String(),
                'user_id' => $log->user_id,
                'payload' => $log->payload,
            ];
        })->all();

        $versionEvents = collect($history)->map(function ($h) {
            return [
                'type' => 'version',
                'action' => 'version_bump',
                'at' => $h['at'] ?? null,
                'user_id' => $h['by'] ?? null,
                'payload' => $h,
            ];
        })->all();

        return collect(array_merge($audits, $versionEvents))
            ->sortByDesc('at')
            ->values()
            ->all();
    }

    public function cloneRecord(Booking $entity, int $userId, array $overrides = []): Booking
    {
        $data = $entity->only([
            'airline_id', 'status', 'name', 'description', 'metadata',
            'region', 'station_code', 'priority', 'notes',
        ]);
        $data['code'] = ($overrides['code'] ?? ($entity->code.'-COPY'));
        $data['name'] = $overrides['name'] ?? ($entity->name.' (Copy)');
        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;
        $data['is_active'] = false;
        $data['status'] = 'draft';
        $data['version'] = 1;
        $data['external_ref'] = Str::uuid()->toString();
        $data = array_merge($data, $overrides);

        return $this->create($data);
    }

    protected function generateCode(array $payload): string
    {
        $prefix = strtoupper(substr('PassengerBooking', 0, 3));
        $station = strtoupper(substr($payload['station_code'] ?? 'XXX', 0, 3));
        return sprintf('%s-%s-%s', $prefix, $station, strtoupper(Str::random(6)));
    }

    protected function bustCache(int $airlineId): void
    {
        Cache::forget("stats:PassengerBooking:{$airlineId}");
    }
}
