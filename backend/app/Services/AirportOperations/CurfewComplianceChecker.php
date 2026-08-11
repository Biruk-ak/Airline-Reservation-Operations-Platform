<?php
namespace App\Services\AirportOperations;

use App\Models\AirportOperations\Airport;
use Carbon\Carbon;

class CurfewComplianceChecker
{
    public function flag(Airport $airport, $moment = null): array
    {
        $moment = $moment ? Carbon::parse($moment) : now();
        $start = $airport->getMetadataKey('curfew_start_hour', 23);
        $end = $airport->getMetadataKey('curfew_end_hour', 5);
        $hour = (int) $moment->format('G');

        $inCurfew = $start > $end
            ? ($hour >= (int) $start || $hour < (int) $end)
            : ($hour >= (int) $start && $hour < (int) $end);

        return [
            'curfew_compliant' => !$inCurfew,
            'in_curfew_window' => $inCurfew,
        ];
    }
}
