<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class profile extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('user.profile');
    }
    public function updateprofile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'lastName' => 'required',
            'firstName' => 'required',
            'phoneNumber' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
        }

        $data = User::find(auth()->user()->id);
        $data->lastName = $request->lastName;
        $data->firstName = $request->firstName;
        $data->phoneNumber = $request->phoneNumber;

        $data->save();

        return back()->with('toast_success', 'Profile has been updated');
    }

    public function updatepassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'max:39', 'min:8', 'confirmed'],
        ]);
        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
        }

        $data = User::find(auth()->user()->id);
        $data->password = Hash::make($request->password);
        $data->save();

        Auth::logoutOtherDevices($request->password);

        return redirect()->route('login')->with('toast_success', 'Password has been updated');
    }

    public function photoupdate(Request $request)
    {
       

        $validator = Validator::make($request->all(), [
            'file' => 'required |mimes:png,jpg,jpeg',
        ]);
        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
        }

        $data = User::find(auth()->user()->id);
        $input = $request->all();

        if ($image = $request->file('file')) {
            $destinationPath = 'image/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $data->photo = "image/$profileImage";
        } else {
            unset($input['photo']);
        }

        $data->save();

        return back()->with('toast_success', 'profile image successfully updated');
    }

    public function updatesponsor(Request $request){
        $data = DB::table('users')->where('userId', auth()->user()->userId)->first();
        $validate = DB::table('users')->where('mySponsorId', $request->sponsor)->first();
        if($validate == null){
            return back()->with('toast_error', "Oops!! sponsor not found");
        }else{
            if($data->sponsor == 'Admin'){
                DB::table('users')->where('userId', auth()->user()->userId)->update([
                    'sponsor' => $request->sponsor,
                    'uplineOne' => $request->sponsor,
                ]);

                DB::table('downlines')->insert([
                    'userId' => $request->sponsor,
                    'owner' => $request->sponsor,
                    'downline' => auth()->user()->username,
                    'fullname' => auth()->user()->firstName . ' ' . auth()->user()->lastName,
                    'email' => auth()->user()->email,
                    'phoneNumber' => auth()->user()->phoneNumber,
                    'rank' => auth()->user()->rank,
                    'package' => auth()->user()->package,
                    'status' => 'ACTIVE',
                ]);
                return back()->with('toast_success', 'Sponsor successfully updated');
            }else{
                return back()->with('toast_error', "Can't update, you already have a sponsor");
            }
        }
    }
}


