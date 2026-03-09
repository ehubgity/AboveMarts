<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

class resetpassword extends Controller
{
    public function index(Request $request){
        $data = $request->id;
        return view('auth.passwordreset')->with('data', $data);
    }
    
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'confirmed'],
            'password_confirmation' => 'required',
        ]);
      if($validator->fails()){
        return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
      }
    

        $passwordh = Hash::make($request->password);
        
        DB::table('users')
        ->where('userId', $request->id)
        ->update(['password'=> $passwordh]);
        
        return redirect()->route('login')->withToastSuccess('Successful');

    }
}
