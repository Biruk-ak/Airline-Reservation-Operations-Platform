<?php
namespace App\Services\LoyaltyProgram;

use App\Models\LoyaltyProgram\LoyaltyAccount;

class TierProgressSnapshot
{
    public function forAccount(LoyaltyAccount $account): array
    {
        $points = (float) $account->getMetadataKey('points', 0);
        $nextTierAt = (float) $account->getMetadataKey('next_tier_points', 1000);
        $tier = (string) $account->getMetadataKey('tier', 'member');
        $progress = $nextTierAt > 0 ? min(100, round(($points / $nextTierAt) * 100, 2)) : 0;

        return [
            'tier' => $tier,
            'points' => $points,
            'next_tier_points' => $nextTierAt,
            'tier_progress_pct' => $progress,
        ];
    }
}
