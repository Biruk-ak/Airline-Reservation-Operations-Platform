<?php
namespace App\Services\Maintenance;

use App\Models\Maintenance\MaintenanceWorkOrder;

class OverdueWorkOrderSpotlight
{
    public function countOverdue(int $airlineId): int
    {
        return MaintenanceWorkOrder::query()
            ->forAirline($airlineId)
            ->whereNotNull('effective_to')
            ->where('effective_to', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled', 'archived'])
            ->count();
    }

    public function applyOverdueFilter($query)
    {
        return $query->whereNotNull('effective_to')
            ->where('effective_to', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled', 'archived']);
    }
}
