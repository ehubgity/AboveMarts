<?php

namespace App\Http\Controllers;

use App\Mail\EmailFunding;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class edituser extends Controller
{
    public function index(Request $request)
    {
        $id = $request->id;

        if ($id == null) {
            return back()->with('toast_error', "Invalid Id");
        } else {
            $data = DB::table('users')
                ->where('userId', $id)
                ->first();
            $expenses = DB::table('transactions')
                ->where('userId', $data->userId)
                ->where('status', 'CONFIRM')
                ->where('transactionType', '!=', 'Deposit')
                ->sum('amount');
            $capital = DB::table('funds')
                ->where('userId', $data->userId)
                ->where('status', 'success')
                ->sum('amount');
            $bonus = DB::table('transactions')
                ->where('userId', $data->userId)
                ->where('transactionService', 'Commission')
                ->sum('amount');
            $withdrawamount = DB::table('withdraws')
                ->where('userId', $data->userId)
                ->where('status', 'CONFIRM')
                ->where('paymentType', 'wallet')
                ->sum('amount');
            $withdrawbonus = DB::table('withdraws')
                ->where('userId', $data->userId)
                ->where('status', 'CONFIRM')
                ->where('paymentType', 'Transfer')
                ->sum('amount');
            $sharedamount = DB::table('withdraws')
                ->where('userId', $data->userId)
                ->where('status', 'CONFIRM')
                ->where('paymentType', 'Shared')
                ->sum('amount');
            $bonusamount = DB::table('bonuses')
                ->where('sponsor', $data->mySponsorId)
              ->where('status', 'CONFIRM')
                ->sum('amount');

            return view('admin.edituser')
                ->with('data', $data)
                ->with('withdrawamount', $withdrawamount)
                ->with('bonusamount', $bonusamount)
                ->with('withdrawbonus', $withdrawbonus)
                ->with('bonus', $bonus)
                ->with('capital', $capital)
                ->with('expenses', $expenses)
                ->with('sharedamount', $sharedamount);
        }
    }

    public function store(Request $request)
    {
        $data = DB::table('users')
            ->where('username', $request->sponsor)
            ->first();
        $user = DB::table('users')
            ->where('username', $request->username)
            ->first();

        if ($request->sponsor == "Admin") {
            DB::table('users')
                ->where('userId', $request->id)
                ->update([
                    'lastName' => $request->lastname,
                    'firstName' => $request->firstname,
                    'country' => $request->country,
                    // 'password' => Hash::make($request->password),
                    // 'passwordh' => $request->password,
                    'uplineOne' => $request->sponsor,
                    'sponsor' => $request->sponsor,
                    'email' => $request->email,
                    'phoneNumber' => $request->phone,
                    'accountNumber' => $request->accountNumber,
                ]);

            return back()->with('toast_success', 'Profile has been updated');
        } else {
            // Admin should only send to admin
            if ($data == null) {
                return back()->with('toast_error', 'Sponsor Not Available');
            } else {
                if ($user->sponsor == "Admin") {
                    DB::table('users')
                        ->where('userId', $request->id)
                        ->update([
                            'lastName' => $request->lastname,
                            'firstName' => $request->firstname,
                            'country' => $request->country,
                            // 'password' => Hash::make($request->password),
                            // 'passwordh' => $request->password,
                            'uplineOne' => $request->sponsor,
                            'sponsor' => $request->sponsor,
                            'email' => $request->email,
                            'phoneNumber' => $request->phone,
                                                'accountNumber' => $request->accountNumber,

                        ]);

                    DB::table('downlines')->insert([
                        'userId' => $request->sponsor,
                        'owner' => $request->sponsor,
                        'downline' => $request->username,
                        'fullname' => $request->firstname . ' ' . $request->lastname,
                        'email' => $request->email,
                        'phoneNumber' => $request->phone,
                        'rank' => $user->rank,
                        'package' => $user->package,
                        'status' => 'ACTIVE',
                    ]);
                    return back()->with('toast_success', 'Profile has been updated');
                } else {
                    DB::table('users')
                        ->where('userId', $request->id)
                        ->update([
                            'lastName' => $request->lastname,
                            'firstName' => $request->firstname,
                            'country' => $request->country,
                            // 'password' => Hash::make($request->password),
                            // 'passwordh' => $request->password,
                            'uplineOne' => $request->sponsor,
                            'sponsor' => $request->sponsor,
                            'email' => $request->email,
                            'phoneNumber' => $request->phone,
                                                'accountNumber' => $request->accountNumber,

                        ]);
                    DB::table('downlines')
                        ->where('downline', $request->username)
                        ->where('owner', $user->sponsor)
                        ->update([
                            'owner' => $request->sponsor,
                            'userId' => $request->sponsor,
                        ]);
                    return back()->with('toast_success', 'Profile has been updated');
                }
            }
        }
    }

    public function updatepassword(Request $request)
    {
        DB::table('users')
            ->where('userId', $request->id)
            ->update([
                'password' => Hash::make($request->password),
            ]);

        return back()->with('toast_success', 'Password has been updated');
    }
}
