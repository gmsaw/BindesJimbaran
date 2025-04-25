<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek jika user tidak login atau role_id null
        if (!Auth::check() || is_null(Auth::user()->role_id)) {
            return back();
        }

        $rolename = Role::find(Auth::user()->role_id)->name;
        
        if (!in_array($rolename, $roles)) {
            return back();
        }

        return $next($request);
    }
}