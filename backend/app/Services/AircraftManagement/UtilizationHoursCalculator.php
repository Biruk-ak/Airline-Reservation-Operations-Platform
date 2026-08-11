<?php
namespace App\Services\AircraftManagement;

use App\Models\AircraftManagement\Aircraft;

class UtilizationHoursCalculator
{
    public function forAircraft(Aircraft $aircraft): float
    {
        $metaHours = (float) $aircraft->getMetadataKey('utilization_hours', 0);
        $priorityFactor = max(1, (int) $aircraft->priority) * 12.5;
        return round($metaHours + $priorityFactor, 2);
    }

    public function enrichSummary(Aircraft $aircraft, array $summary): array
    {
        $summary['utilization_hours'] = $this->forAircraft($aircraft);
        return $summary;
    }
}
