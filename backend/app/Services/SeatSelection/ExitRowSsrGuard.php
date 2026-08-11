<?php
namespace App\Services\SeatSelection;

use App\Models\SeatSelection\SeatMap;

class ExitRowSsrGuard
{
    public function assertCanActivate(SeatMap $seatMap): ?string
    {
        $isExitRow = (bool) $seatMap->getMetadataKey('exit_row', false);
        if (!$isExitRow) {
            return null;
        }

        $hasSsr = (bool) $seatMap->getMetadataKey('exit_row_ssr', false);
        if ($hasSsr) {
            return null;
        }

        return 'Exit-row seat requires exit_row_ssr metadata before activation';
    }
}
