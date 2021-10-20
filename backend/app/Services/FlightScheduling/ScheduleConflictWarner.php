<?php
namespace App\Services\FlightScheduling;

use App\Models\FlightScheduling\Flight;

class ScheduleConflictWarner
{
    public function warningsFor(array $payload): array
    {
        $airlineId = (int) ($payload['airline_id'] ?? 0);
        $station = $payload['station_code'] ?? null;
        if ($airlineId <= 0) {
            return [];
        }

        $query = Flight::query()->forAirline($airlineId)->active();
        if ($station) {
            $query->forStation($station);
        }

        $candidates = $query->orderByDesc('priority')->limit(5)->get(['id', 'code', 'name', 'priority']);
        if ($candidates->isEmpty()) {
            return [];
        }

        return [[
            'code' => 'SCHEDULE_SOFT_CONFLICT',
            'message' => 'Potential schedule overlap detected for station window',
            'candidates' => $candidates->map->only(['id', 'code', 'name', 'priority'])->values()->all(),
        ]];
    }
}
