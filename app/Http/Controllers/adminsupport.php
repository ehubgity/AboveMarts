<?php

namespace App\Http\Controllers;

use App\Mail\emailVerify;
use App\Mail\support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class adminsupport extends Controller
{
    public function index(){
        $datasupports = DB::table('supports')->get();
        return view('admin.adminsupport')->with('datasupports', $datasupports);
    }

    public function sendMail(Request $request)
    {
        

            return back()->withToastSuccess('Email Sent Successfully');
        
            // $details = [
            //     'name' => $dat->firstname.' '.$dat->lastname,
            //     'email' => $dat->email,
            //     'msg' => $request->msg,
                
            // ];
            // Mail::to($dat->email)->send(new support($details));
            // // return 'email sent';
            // return back()->withToastSuccess('Email Sent Successfully');
        // }
        
    }
}
