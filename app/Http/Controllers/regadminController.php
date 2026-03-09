<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Admin;
use App\Mail\EmailVerification;
use App\Models\admin as ModelsAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class regadminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        return view('admin.register');
    }

    public function rand()
    {
        return substr(rand(0, 10000000) . time(), 0, 15);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => ['required', 'max:30', 'min:10', 'confirmed'],
        ]);
        if ($validator->fails()) {
            return back()
                ->with('toast_error', $validator->messages()->all()[0])
                ->withInput();
        } else {
            if ($request->role == "None") {
                return back()->with('toast_error', "Select a role");
            } else {
                $admin = ModelsAdmin::create([
                    'admin_id' => $this->rand(),
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                    'username' => $request->username,
                    'status' => 'Active',
                    'loginTime' => '0',
                    'logoutTime' => '0',
                ]);
                return redirect()
                    ->route('admin')
                    ->withToastSuccess('Registration Successful');
            }
        }
    }
}
