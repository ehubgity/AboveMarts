<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Subadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->user() != null) {
            if (Auth::guard('admin')->user()->role == 'Superadmin' || Auth::guard('admin')->user()->role = 'Admin') {
                return $next($request);
            } else {
                return redirect()->route('adminlogin')->with('toast_error', 'No access');
            }
        }
        return redirect()->route('adminlogin')->with('toast_error', 'No access');
    }
}
