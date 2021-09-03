<?php
namespace App\Services\Audit;
use App\Models\Audit\EntityAuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditLogger
{
    public function logCreated(Model $entity, ?int $userId): void
    {
        $this->write($entity, 'created', $userId, ['after' => $this->snapshot($entity)]);
    }

    public function logUpdated(Model $entity, array $before, array $after, ?int $userId): void
    {
        $this->write($entity, 'updated', $userId, ['before' => $before, 'after' => $after]);
    }

    public function logDeleted(Model $entity, ?int $userId, array $summary = []): void
    {
        $this->write($entity, 'deleted', $userId, ['before' => $summary ?: $this->snapshot($entity)]);
    }

    public function logCustom(Model $entity, string $action, ?int $userId, array $payload = []): void
    {
        $this->write($entity, $action, $userId, $payload);
    }

    protected function write(Model $entity, string $action, ?int $userId, array $payload): void
    {
        EntityAuditLog::create([
            'entity_type' => get_class($entity),
            'entity_id' => $entity->getKey(),
            'action' => $action,
            'user_id' => $userId,
            'airline_id' => $entity->airline_id ?? null,
            'payload' => $payload,
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }

    protected function snapshot(Model $entity): array
    {
        if (method_exists($entity, 'toOperationalSummary')) {
            return $entity->toOperationalSummary();
        }
        return $entity->toArray();
    }
}
