<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountManagerAuthController extends Controller
{
    //
    public function showLoginForm()
    {
        return view('account_managers.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('account_manager')->attempt($credentials)) {
            return redirect()->route('account-manager.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        Auth::guard('account_manager')->logout();
        return redirect()->route('account-manager.login');
    }
}
