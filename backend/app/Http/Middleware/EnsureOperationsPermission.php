<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use App\Services\Authorization\PermissionResolver;
class EnsureOperationsPermission
{
    public function __construct(private PermissionResolver $resolver) {}

    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = $request->user();
        if (!$user || !$this->resolver->userHasPermission($user, $permission)) {
            return response()->json(['message' => 'Missing operations permission: '.$permission], 403);
        }
        return $next($request);
    }
}
