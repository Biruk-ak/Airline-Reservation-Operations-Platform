<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Airline extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'iata_code', 'icao_code', 'country', 'hq_airport',
        'timezone', 'is_active', 'settings', 'branding', 'contact_email',
        'contact_phone', 'fleet_size_target', 'loyalty_program_name',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
        'branding' => 'array',
        'fleet_size_target' => 'integer',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function setting(string $key, $default = null)
    {
        return ($this->settings ?? [])[$key] ?? $default;
    }

    public function setSetting(string $key, $value): self
    {
        $s = $this->settings ?? [];
        $s[$key] = $value;
        $this->settings = $s;
        $this->save();
        return $this;
    }

    public function brandColor(string $token = 'primary'): string
    {
        return ($this->branding ?? [])[$token] ?? '#0B3D91';
    }

    public function toPublicProfile(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'iata_code' => $this->iata_code,
            'icao_code' => $this->icao_code,
            'country' => $this->country,
            'hq_airport' => $this->hq_airport,
            'loyalty_program_name' => $this->loyalty_program_name,
            'branding' => $this->branding,
        ];
    }
}
