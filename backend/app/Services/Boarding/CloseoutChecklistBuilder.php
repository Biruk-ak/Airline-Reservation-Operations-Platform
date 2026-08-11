<?php
namespace App\Services\Boarding;

use App\Models\Boarding\BoardingPass;

class CloseoutChecklistBuilder
{
    public function build(int $airlineId, ?string $stationCode = null): array
    {
        $query = BoardingPass::query()->forAirline($airlineId);
        if ($stationCode) {
            $query->forStation($stationCode);
        }

        $pending = (clone $query)->whereIn('status', ['scheduled', 'in_progress', 'pending_review'])->count();
        $completed = (clone $query)->completed()->count();
        $noShows = (clone $query)->where('status', 'cancelled')->count();

        return [
            'pending_boarding' => $pending,
            'completed_boarding' => $completed,
            'cancelled_or_no_show' => $noShows,
            'ready_for_closeout' => $pending === 0,
        ];
    }
}
