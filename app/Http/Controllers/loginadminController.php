<?php

namespace App\Http\Controllers;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class loginadminController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return back()
                ->with('toast_error', $validator->messages()->all()[0])
                ->withInput();
        } else {
            $adminauth = $request->only('email', 'password');

            if (Auth::guard('admin')->attempt($adminauth)) {
                if (Auth::guard('admin')->user()->status == 'Active') {
                    DB::table('admins')
                        ->where('admin_id', Auth::guard('admin')->user()->admin_id)
                        ->update(['loginTime' => date('Y-m-d H:i:s')]);
                    return redirect()->route('admin');
                } else {
                    return back()->with('toast_error', 'Contact Admin');
                }
            } else {
                return back()->with('toast_error', 'Invalid login details');
            }
        }
    }
}
