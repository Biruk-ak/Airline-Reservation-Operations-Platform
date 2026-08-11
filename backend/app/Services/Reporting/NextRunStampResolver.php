<?php
namespace App\Services\Reporting;

use App\Models\Reporting\ReportDefinition;

class NextRunStampResolver
{
    public function resolve(ReportDefinition $report): ?string
    {
        $next = $report->getMetadataKey('next_run_at');
        if ($next) {
            return $next;
        }
        $cron = $report->getMetadataKey('schedule_cron');
        if (!$cron) {
            return null;
        }
        // Placeholder stamp for schedule-aware UIs until cron parser is wired.
        return now()->addDay()->startOfHour()->toIso8601String();
    }
}
