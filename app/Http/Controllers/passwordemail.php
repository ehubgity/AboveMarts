<?php

namespace App\Http\Controllers;

use App\Mail\passwordRecovery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class passwordemail extends Controller
{
    public function index()
    {

        return view('auth.passwordemail');
    }
    public function email(Request $request)
    {
        $email = $request->email;

        $data = DB::table('users')->where('email', $email)->first();
        if ($data == null) {
            return back()->with('toast_error', 'Oops!! This Email Address is not registered');
        } else {
            $details = [
                'name' => $data->firstName,
                ' ',
                $data->lastName,
                'data' => $data->userId,
            ];

            Mail::to($request->email)->send(new passwordRecovery($details));
            return back()->with('toast_success', 'Please check your email inbox or spam and reset your password');
        }


    }
}
