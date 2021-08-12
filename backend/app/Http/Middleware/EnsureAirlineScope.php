<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class EnsureAirlineScope
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user || !$user->airline_id) {
            return response()->json(['message' => 'Airline scope required'], 403);
        }
        $request->attributes->set('airline_id', $user->airline_id);
        return $next($request);
    }
}
