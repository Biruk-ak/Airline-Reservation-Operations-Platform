<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'airline_id', 'station_code',
        'employee_number', 'department', 'phone', 'is_active', 'last_login_at',
        'preferences', 'permissions_cache',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'preferences' => 'array',
        'permissions_cache' => 'array',
    ];

    public function airline()
    {
        return $this->belongsTo(Airline::class);
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function isOperationsStaff(): bool
    {
        return $this->hasRole('ops_agent', 'ops_supervisor', 'ops_manager', 'admin', 'super_admin');
    }

    public function isCrew(): bool
    {
        return $this->hasRole('pilot', 'cabin_crew', 'crew_scheduler');
    }

    public function canAccessStation(string $stationCode): bool
    {
        if ($this->hasRole('admin', 'super_admin', 'ops_manager')) {
            return true;
        }
        return strtoupper((string) $this->station_code) === strtoupper($stationCode);
    }

    public function preference(string $key, $default = null)
    {
        return ($this->preferences ?? [])[$key] ?? $default;
    }

    public function setPreference(string $key, $value): self
    {
        $prefs = $this->preferences ?? [];
        $prefs[$key] = $value;
        $this->preferences = $prefs;
        $this->save();
        return $this;
    }

    public function markLogin(): self
    {
        $this->last_login_at = now();
        $this->save();
        return $this;
    }

    public function toProfileArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'airline_id' => $this->airline_id,
            'station_code' => $this->station_code,
            'employee_number' => $this->employee_number,
            'department' => $this->department,
            'is_active' => $this->is_active,
            'last_login_at' => optional($this->last_login_at)->toIso8601String(),
        ];
    }
}
