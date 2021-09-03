<?php
namespace App\Services\Authorization;
use App\Models\User;

class PermissionResolver
{
    protected array $rolePermissions = [
        'super_admin' => ['*'],
        'admin' => [
            'flights.*', 'aircraft.*', 'crew.*', 'bookings.*', 'seats.*',
            'checkin.*', 'boarding.*', 'gates.*', 'baggage.*', 'cargo.*',
            'maintenance.*', 'pricing.*', 'payments.*', 'loyalty.*',
            'airports.*', 'tracking.*', 'weather.*', 'analytics.*',
            'reports.*', 'admin.*',
        ],
        'ops_manager' => [
            'flights.*', 'aircraft.read', 'crew.*', 'gates.*', 'boarding.*',
            'baggage.*', 'cargo.*', 'maintenance.read', 'tracking.*',
            'weather.read', 'analytics.read', 'reports.read',
        ],
        'ops_supervisor' => [
            'flights.read', 'flights.update', 'gates.*', 'boarding.*',
            'baggage.*', 'checkin.*', 'tracking.read', 'weather.read',
        ],
        'ops_agent' => [
            'flights.read', 'checkin.*', 'boarding.read', 'boarding.update',
            'baggage.read', 'baggage.update', 'gates.read', 'seats.read',
        ],
        'crew_scheduler' => ['crew.*', 'flights.read', 'aircraft.read'],
        'pilot' => ['flights.read', 'crew.read', 'tracking.read', 'weather.read'],
        'cabin_crew' => ['flights.read', 'crew.read', 'boarding.read'],
        'finance' => ['pricing.*', 'payments.*', 'loyalty.read', 'reports.finance'],
        'analyst' => ['analytics.*', 'reports.*', 'tracking.read', 'weather.read'],
    ];

    public function userHasPermission(User $user, string $permission): bool
    {
        $perms = $this->permissionsForRole($user->role);
        if (in_array('*', $perms, true)) {
            return true;
        }
        if (in_array($permission, $perms, true)) {
            return true;
        }
        [$domain] = array_pad(explode('.', $permission, 2), 2, null);
        return in_array($domain.'.*', $perms, true);
    }

    public function permissionsForRole(string $role): array
    {
        return $this->rolePermissions[$role] ?? [];
    }

    public function listPermissions(User $user): array
    {
        return $this->permissionsForRole($user->role);
    }
}
