<?php
namespace App\Services\CrewScheduling;

use App\Models\CrewScheduling\CrewMember;

class DutyRiskIndicator
{
    public function assess(CrewMember $member): array
    {
        $used = (float) $member->getMetadataKey('duty_hours_used', 0);
        $limit = (float) $member->getMetadataKey('duty_hours_limit', 100);
        $ratio = $limit > 0 ? ($used / $limit) : 0;

        $level = 'ok';
        if ($ratio >= 1) {
            $level = 'breach';
        } elseif ($ratio >= 0.85) {
            $level = 'approaching';
        }

        return [
            'duty_risk' => $level,
            'duty_hours_used' => $used,
            'duty_hours_limit' => $limit,
            'duty_ratio' => round($ratio, 3),
        ];
    }
}
