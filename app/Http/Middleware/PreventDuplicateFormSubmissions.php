<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventDuplicateFormSubmissions
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
        if ($request->session()->has('form_submitted')) {
            // Set an error message to indicate a duplicate form submission
            return redirect()->back()->with('toast_error', 'Duplicate form submission. Please wait before submitting again.');
        }
    
        // Set the form submission key in the session
        $request->session()->put('form_submitted', true);
    
        // Allow the request to proceed
        $response = $next($request);
    
        // Remove the form submission key from the session after a specific time
        $request->session()->forget('form_submitted');

        return $response;


    }
}
