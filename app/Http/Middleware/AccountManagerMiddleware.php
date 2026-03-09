<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AccountManagerMiddleware
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
        if (!auth('account_manager')->check()) {
            return redirect()->route('account-manager.login');
        }

        return $next($request);
    }
}
