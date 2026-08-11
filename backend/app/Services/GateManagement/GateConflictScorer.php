<?php
namespace App\Services\GateManagement;

use App\Models\GateManagement\Gate;

class GateConflictScorer
{
    public function score(Gate $gate): int
    {
        $score = (int) $gate->priority * 10;
        $score += match ($gate->status) {
            'in_progress' => 25,
            'pending_review' => 20,
            'scheduled' => 10,
            'cancelled' => -15,
            'archived' => -20,
            default => 0,
        };
        if (!$gate->is_active) {
            $score -= 5;
        }
        return max(0, $score);
    }

    public function enrichSummary(Gate $gate, array $summary): array
    {
        $summary['conflict_score'] = $this->score($gate);
        return $summary;
    }
}
