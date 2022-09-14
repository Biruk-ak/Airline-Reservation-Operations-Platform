<?php
namespace App\Services\FlightTracking;

use App\Models\FlightTracking\FlightPosition;

class StalePositionDetector
{
    public function __construct(private int $thresholdMinutes = 15) {}

    public function isStale(FlightPosition $position): bool
    {
        $updated = $position->updated_at ?? $position->effective_from;
        if (!$updated) {
            return true;
        }
        return $updated->lt(now()->subMinutes($this->thresholdMinutes));
    }

    public function enrichSummary(FlightPosition $position, array $summary): array
    {
        $summary['stale'] = $this->isStale($position);
        $summary['stale_threshold_minutes'] = $this->thresholdMinutes;
        return $summary;
    }
}
