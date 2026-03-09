<?php

namespace App\Http\Controllers;

use App\Mail\emailVerify;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class Register extends Controller
{
    public function index(Request $request)
    {
        if (isset($request->ref)) {
            $id = $request->ref;
            return view("auth.register")->with('id', $id);
        } else {
            $id = '';

            return view("auth.register")->with('id', $id);
        }
    }
    public function randomDigit()
    {
        $pass = substr(str_shuffle("0123456789"), 0, 12);
        return $pass;
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', "min:3", "max:20", 'regex:/^[a-zA-Z0-9]+$/'],
            'lastname' => ['required', "min:3", "max:20", 'regex:/^[a-zA-Z0-9]+$/'],
            'username' => ['required', 'unique:users', "min:3", "max:20", 'regex:/^[a-zA-Z0-9]+$/'],
            'email' => ['required', 'unique:users'],
            'phoneNumber' => ['required', 'digits:11', 'numeric', 'unique:users'],
            'password' => ['required', 'max:39', 'min:8', 'confirmed'],
        ]);
        if ($validator->fails()) {
            return back()
                ->with('toast_error', $validator->messages()->all()[0])
                ->withInput();
        }
        $sponsorExisit = User::where('mySponsorId', $request->sponsor)->first();
        if (isset($request->sponsor)) {
            if ($sponsorExisit == null) {
                return back()
                    ->with('toast_error', 'Sponsor not found');
            } else {
                $manager = $this->getRandomManager();

                if (!$manager) {
                    return back()->with('toast_error', 'No account manager available to assign.');
                }
                $user = User::create([
                    'userId' => $this->randomDigit(),
                    'firstName' => $request->firstname,
                    'lastName' => $request->lastname,
                    'username' => $request->username,
                    'email' => $request->email,
                    'phoneNumber' => $request->phoneNumber,
                    'country' => 'None',
                    'sponsor' => $request->sponsor,
                    'mySponsorId' => $request->username,
                    'manager' => $manager->id,
                    'status' => 'BLOCK',
                    'emailVerified' => 'YES',
                    'password' => Hash::make($request->password),
                    'photo' => 'assets/img/user/user-2.png',
                    'rank' => 'Free Member',
                    'package' => 'Basic',
                    'point' => 0,
                    'totalEarning' => 0,
                    'expectedEarning' => 0,
                    'uplineOne' => 'Admin',
                    'uplineTwo' => 'Admin',
                    'uplineThree' => 'Admin',
                    'uplineFour' => 'Admin',
                    'uplineFive' => 'Admin',
                    'uplineSix' => 'Admin',
                    'uplineSeven' => 'Admin',
                    'downlineOne' => 'Admin',
                    'downlineTwo' => 'Admin',
                    'downlineThree' => 'Admin',
                    'downlineFour' => 'Admin',
                    'downlineFive' => 'Admin',
                    'downlineSix' => 'Admin',
                    'downlineSeven' => 'Admin',

                ]);
                DB::table('downlines')->insert([
                    'userId' => $request->sponsor,
                    'owner' => $request->sponsor,
                    'downline' => $request->username,
                    'fullname' => $request->firstname . ' ' . $request->lastname,
                    'email' => $request->email,
                    'phoneNumber' => $request->phoneNumber,
                    'rank' => 'Free Member',
                    'package' => 'Basic',
                    'status' => 'ACTIVE',
                ]);
                $manager->users()->syncWithoutDetaching([$user->id]);
                $manager->increment('noOfUsers');
                $manager->increment('totalUsers');
                // email......
                $userData = DB::table('users')
                    ->where('username', $request->username)
                    ->first();

                $details = [
                    'name' => $request->firstName . ' ' . $request->lastName,
                    'id' => $userData->userId,
                ];
                // Mail::to($request->email)->send(new emailVerify($details));
                // return 'email sent';

                return redirect()
                    ->route('login')
                    ->withToastSuccess('Registration Successful');
            }
        } else {
            $user = User::create([
                'userId' => $this->randomDigit(),
                'firstName' => $request->firstname,
                'lastName' => $request->lastname,
                'username' => $request->username,
                'email' => $request->email,
                'phoneNumber' => $request->phoneNumber,
                'country' => 'None',
                'sponsor' => 'Admin',
                'mySponsorId' => $request->username,
                'status' => 'BLOCK',
                'emailVerified' => 'YES',
                'password' => Hash::make($request->password),
                'photo' => 'assets/img/user/user-2.png',
                'rank' => 'Free Member',
                'package' => 'Basic',
                'point' => 0,
                'totalEarning' => 0,
                'expectedEarning' => 0,
                'uplineOne' => 'Admin',
                'uplineTwo' => 'Admin',
                'uplineThree' => 'Admin',
                'uplineFour' => 'Admin',
                'uplineFive' => 'Admin',
                'uplineSix' => 'Admin',
                'uplineSeven' => 'Admin',
                'downlineOne' => 'Admin',
                'downlineTwo' => 'Admin',
                'downlineThree' => 'Admin',
                'downlineFour' => 'Admin',
                'downlineFive' => 'Admin',
                'downlineSix' => 'Admin',
                'downlineSeven' => 'Admin',

            ]);
            $manager = $this->getRandomManager();

            if (!$manager) {
                return back()->with('toast_error', 'No account manager available to assign.');
            }
            $manager->users()->syncWithoutDetaching([$user->id]);
            $manager->increment('noOfUsers');
            $manager->increment('totalUsers');

            // $user->attachRole('user');
            // email......

            $userData = DB::table('users')
                ->where('username', $request->username)
                ->first();

            $details = [
                'name' => $request->firstName . ' ' . $request->lastName,
                'id' => $userData->userId,
            ];
            // Mail::to($request->email)->send(new emailVerify($details));
            // return 'email sent';

            return redirect()
                ->route('login')
                ->withToastSuccess('Registration Successful');
        }
    }

    private function getRandomManager()
    {
        return \App\Models\AccountManager::inRandomOrder()->first();
    }
}
