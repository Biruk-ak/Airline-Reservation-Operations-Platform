<?php
namespace App\Services\WeatherIntegration;

use App\Models\WeatherIntegration\WeatherObservation;

class SevereWeatherAlerter
{
    public function alertLevel(WeatherObservation $observation): string
    {
        $severity = strtolower((string) $observation->getMetadataKey('severity', 'normal'));
        return match ($severity) {
            'extreme', 'critical' => 'severe',
            'high', 'warning' => 'elevated',
            'moderate' => 'watch',
            default => 'normal',
        };
    }
}
