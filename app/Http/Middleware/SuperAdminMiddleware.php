<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnums;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        if ($user->role !== RoleEnums::SYSTEME_ADMIN->value) {
            return response()->json(['error' => 'Accès refusé. Administrateur système uniquement.'], 403);
        }

        return $next($request);
    }
}
