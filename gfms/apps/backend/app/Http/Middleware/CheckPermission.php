<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Activitylog\Facades\Activity;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        if (!$request->user()->hasPermissionTo($permission)) {
            // Log unauthorized access attempt
            activity()
                ->causedBy($request->user())
                ->withProperties([
                    'permission' => $permission,
                    'route' => $request->path(),
                    'method' => $request->method(),
                    'ip_address' => $request->ip(),
                ])
                ->log('Unauthorized permission access attempt');
            
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to perform this action',
                'required_permission' => $permission,
            ], 403);
        }

        return $next($request);
    }
}
