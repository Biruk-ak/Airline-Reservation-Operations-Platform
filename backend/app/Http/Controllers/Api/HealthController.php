<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class HealthController extends Controller
{
    public function show()
    {
        $db = 'ok';
        $redis = 'ok';
        try { DB::connection()->getPdo(); } catch (\Throwable $e) { $db = 'error'; }
        try { Redis::ping(); } catch (\Throwable $e) { $redis = 'error'; }

        return response()->json([
            'status' => ($db === 'ok' && $redis === 'ok') ? 'ok' : 'degraded',
            'app' => 'Airline Reservation & Operations Platform',
            'checks' => compact('db', 'redis'),
            'time' => now()->toIso8601String(),
        ]);
    }
}
