<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PasswordNotExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $user = $request->user();

            $password_changed_at = $user->password_changed_at ?? $user->created_at;

            if (now()->diffInDays($password_changed_at) < config('auth.password_expires_days')) {
                return redirect('/');
            }
        }

        return $next($request);
    }
}
