<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // define role as array
    public function handle(Request $request, Closure $next, $roles)
    {
        // check if user is logged in
        if (!auth()->check()) {
            return redirect('login');
        }

        // check if user has role
        $user = auth()->user();
        $userRole = $user->role;
        $roles = explode('|', $roles);
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // return abort(401);
        return response(view('error.403'));

    }
}
