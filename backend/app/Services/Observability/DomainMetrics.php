<?php
namespace App\Services\Observability;
use Illuminate\Support\Facades\Redis;

class DomainMetrics
{
    public function increment(string $key, int $by = 1): void
    {
        try {
            Redis::incrby('metrics:'.$key, $by);
        } catch (\Throwable $e) {
            // Metrics must never break primary flows.
        }
    }

    public function gauge(string $key, float $value): void
    {
        try {
            Redis::set('metrics:gauge:'.$key, (string) $value);
        } catch (\Throwable $e) {
        }
    }

    public function timing(string $key, float $ms): void
    {
        try {
            Redis::lpush('metrics:timing:'.$key, (string) $ms);
            Redis::ltrim('metrics:timing:'.$key, 0, 999);
        } catch (\Throwable $e) {
        }
    }
}
