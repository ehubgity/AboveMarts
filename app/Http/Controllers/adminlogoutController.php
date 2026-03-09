<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class adminlogoutController extends Controller
{
    public function logout(Request $request)
    {
        DB::table('admins')
            ->where('admin_id', Auth::guard('admin')->user()->admin_id)
            ->update(['logoutTime' => date('Y-m-d H:i:s')]);

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('adminlogin');
    }
}
