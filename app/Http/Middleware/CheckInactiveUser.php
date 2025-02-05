<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckInactiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->is_active) {
            auth()->logout();
            $request->session()->invalidate();
        }
        return $next($request);
    }

}
