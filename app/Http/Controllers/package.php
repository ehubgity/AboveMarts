<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\buypackage;
use App\Models\bonus;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class package extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data = DB::table('packages')->get();
        $package = DB::table('packages')->where('packageName', auth()->user()->package)->first();
        $packageAmount = 0;

        if (auth()->user()->package != "Basic") {
            $packageAmount = $package->packageAmount;
        }

        return view('user.package')->with('data', $data)->with('packageAmount', $packageAmount);
    }
    public function randomDigit()
    {
        $pass = substr(str_shuffle("01234561089abcDEfadsasfdasdfasdsfa3425542FGHIJnostXYZ"), 0, 30);
        return $pass;
    }
    public function store(Request $request)
    {
        $datapackage = DB::table('packages')
            ->where('packageName', $request->package)
            ->first();
        $userdatapackage = DB::table('packages')
            ->where('packageName', auth()->user()->package)
            ->first();
        $walletamount = DB::table('funds')
            ->where('userId', auth()->user()->userId)
            ->where('status', 'success')
            ->sum('amount');
        $expenses = DB::table('transactions')
            ->where('userId', auth()->user()->userId)
            ->where('status', 'CONFIRM')
            ->where('transactionType', '!=', 'Deposit')
            ->sum('amount');
        $withdrawamount = DB::table('withdraws')
            ->where('userId', auth()->user()->userId)
            ->sum('amount');
        $uplineOne = DB::table('users')
            ->where('uplineOne', '!=', 'Admin')
            ->first();
        $uplineTwo = DB::table('users')
            ->where('uplineTwo', '!=', 'Admin')
            ->first();
        $uplineThree = DB::table('users')
            ->where('uplineThree', '!=', 'Admin')
            ->first();
        $uplineFour = DB::table('users')
            ->where('uplineFour', '!=', 'Admin')
            ->first();
        $uplineFive = DB::table('users')
            ->where('uplineFive', '!=', 'Admin')
            ->first();
        $uplineSix = DB::table('users')
            ->where('uplineSix', '!=', 'Admin')
            ->first();
        $uplineSeven = DB::table('users')
            ->where('uplineSeven', '!=', 'Admin')
            ->first();
        $totalamount = $walletamount - $expenses;

        if ($request->payment == 'wallet') {

            if ($request->package == "NONE" && $request->packagemigrate == "NONE") {
                return back()->with('toast_error', 'Failed transaction');
            } else {

                if ($request->migrate == "YES") {
                    $oldbonus = 0;
                    $oldpoint = 0;

                    if (auth()->user()->package = 'Silver') {
                        $oldbonus = 2500;
                        $oldpoint = 0.2;
                    } elseif (auth()->user()->package = 'Gold') {
                        $oldbonus = 5000;
                        $oldpoint = 0.3;
                    } elseif (auth()->user()->package = 'Bronze') {
                        $oldbonus = 1000;
                        $oldpoint = 0.1;
                    } elseif (auth()->user()->package = 'Platinum') {
                        $oldbonus = 10000;
                        $oldpoint = 0.5;
                    } else {
                        return redirect()
                            ->route('fund')
                            ->with('toast_error', 'Invalid Pacakge');
                    }
                    $datapackage = DB::table('packages')
                        ->where('packageName', $request->packagemigrate)
                        ->first();
                    // your new packageamount - your current packageamount
                    $newpackageamount = $datapackage->packageAmount - $userdatapackage->packageAmount;
                    if ($totalamount < $newpackageamount || $datapackage->packageAmount < $userdatapackage->packageAmount || auth()->user()->package == $request->packagemigrate) {
                        return redirect()
                            ->route('fund')
                            ->with('toast_error', 'Insufficient amount for the transaction');
                    } else {
                        DB::table('transactions')->insert([
                            'transactionId' => $this->randomDigit(),
                            'userId' => auth()->user()->userId,
                            'username' => auth()->user()->username,
                            'email' => auth()->user()->email,
                            'phoneNumber' => auth()->user()->phoneNumber,
                            'amount' => $newpackageamount,
                            'transactionType' => 'Package Transaction',
                            'transactionService' => $request->packagemigrate . ' ' . 'Package',
                            'paymentMethod' => 'wallet',
                            'Admin' => 'None',
                            'status' => 'CONFIRM',
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        ]);
                        DB::table('users')
                            ->where('userId', auth()->user()->userId)
                            ->update(['rank' => 'Paid Partner']);
                        if (
                            User::where('sponsor', auth()->user()->sponsor)->exists() &&
                            auth()->user()->sponsor != 'Admin'
                        ) {
                            if ($request->packagemigrate == 'Silver') {
                                // return dd('sds');
                                $goldenbonus = 0;
                                $expectedEarning =
                                    auth()->user()->expectedEarning +
                                    ($newpackageamount * 500) / 100;
                                // update
                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('package', 'Basic')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Bronze')
                                    ->update(['package' => $datapackage->packageName]);

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['expectedEarning' => $expectedEarning]);

                                // create
                                buypackage::create([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'phoneNumber' => auth()->user()->userId,
                                    'amount' => $newpackageamount,
                                    'package' => $datapackage->packageName,
                                    'goldenBonus' => $goldenbonus,
                                    'goldenBonusStatus' => 'Pending',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);

                                // bonus
                                $bonus = 2500 - $oldbonus;

                                $data = DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('sponsor', '!=', 'Admin')
                                    ->first();

                                $sponsordata = $data->sponsor;
                                $point = 0.2 - $oldpoint;
                                $oldpoint = auth()->user()->point;
                                $newpoint = $oldpoint + $point;

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['point' => $newpoint]);

                                DB::table('points')->insert([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'point' => $point,
                                    'package' => 'None',
                                    'sponsor' => auth()->user()->username,
                                    'status' => 'CONFIRM',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);

                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->mySponsorId,
                                    'sponsorId' => auth()->user()->mySponsorId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus,
                                    'package' => $request->packagemigrate,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineOne,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus,
                                    'package' => $request->packagemigrate,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);


                                $uplineOneData = DB::table('users')
                                    ->where('mySponsorId', auth()->user()->uplineOne)
                                    ->first();

                                if ($uplineOneData != null) {
                                    $uplineOnePoint = $uplineOneData->point + $point;
                                    DB::table('users')
                                        ->where('mySponsorId', auth()->user()->uplineOne)
                                        ->update(['point' => $uplineOnePoint]);

                                    DB::table('points')->insert([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->uplineOne,
                                        'username' => auth()->user()->uplineOne,
                                        'point' => $point,
                                        'package' => 'None',
                                        'sponsor' => auth()->user()->uplineOne,
                                        'status' => 'CONFIRM',
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                    ]);

                                } else {
                                    return back()->with('toast_success', 'Transaction Successful');
                                }
                                // email
                                // return back()->with('toast_success', 'Transaction Successful');
                            } elseif ($request->packagemigrate == 'Gold') {
                                $goldenbonus = 0;
                                $expectedEarning =
                                    auth()->user()->expectedEarning +
                                    ($newpackageamount * 500) / 100;
                                // update
                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('package', 'Basic')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Bronze')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Silver')
                                    ->update(['package' => $datapackage->packageName]);
                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['expectedEarning' => $expectedEarning]);
                                // create
                                buypackage::create([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'phoneNumber' => auth()->user()->userId,
                                    'amount' => $newpackageamount,
                                    'package' => $datapackage->packageName,
                                    'goldenBonus' => $goldenbonus,
                                    'goldenBonusStatus' => 'Pending',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                // bonus
                                $bonus = 5000 - $oldbonus;
                                $data = DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('sponsor', '!=', 'Admin')
                                    ->first();

                                $sponsordata = $data->sponsor;
                                $point = 0.3 - $oldpoint;
                                $oldpoint = auth()->user()->point;
                                $newpoint = $oldpoint + $point;

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['point' => $newpoint]);

                                DB::table('points')->insert([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'point' => $point,
                                    'package' => 'None',
                                    'sponsor' => auth()->user()->username,
                                    'status' => 'CONFIRM',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);

                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->mySponsorId,
                                    'sponsorId' => auth()->user()->mySponsorId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus,
                                    'package' => $request->packagemigrate,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineOne,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus,
                                    'package' => $request->packagemigrate,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);

                                $uplineOneData = DB::table('users')
                                    ->where('mySponsorId', auth()->user()->uplineOne)
                                    ->first();
                                if ($uplineOneData != null) {
                                    $uplineOnePoint = $uplineOneData->point + $point;
                                    DB::table('users')
                                        ->where('mySponsorId', auth()->user()->uplineOne)
                                        ->update(['point' => $uplineOnePoint]);

                                    DB::table('points')->insert([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->uplineOne,
                                        'username' => auth()->user()->uplineOne,
                                        'point' => $point,
                                        'package' => 'None',
                                        'sponsor' => auth()->user()->uplineOne,
                                        'status' => 'CONFIRM',
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                    ]);

                                } else {
                                    return back()->with('toast_success', 'Transaction Successful');
                                }
                                // email
                                // return back()->with('toast_success', 'Transaction Successful');
                            } elseif ($request->packagemigrate == 'Platinum') {
                                $goldenbonus = 0;
                                $expectedEarning =
                                    auth()->user()->expectedEarning +
                                    ($newpackageamount * 500) / 100;
                                // update
                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('package', 'Basic')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Bronze')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Silver')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Gold')
                                    ->update(['package' => $datapackage->packageName]);

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['expectedEarning' => $expectedEarning]);

                                // create
                                buypackage::create([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'phoneNumber' => auth()->user()->userId,
                                    'amount' => $newpackageamount,
                                    'package' => $datapackage->packageName,
                                    'goldenBonus' => $goldenbonus,
                                    'goldenBonusStatus' => 'Pending',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                // bonus
                                $bonus = 10000 - $oldbonus;
                                $data = DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('sponsor', '!=', 'Admin')
                                    ->first();
                                $sponsordata = $data->sponsor;
                                $point = 0.5 - $oldpoint;
                                $oldpoint = auth()->user()->point;
                                $newpoint = $oldpoint + $point;

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['point' => $newpoint]);

                                DB::table('points')->insert([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'point' => $point,
                                    'package' => 'None',
                                    'sponsor' => auth()->user()->username,
                                    'status' => 'CONFIRM',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);

                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->mySponsorId,
                                    'sponsorId' => auth()->user()->mySponsorId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus,
                                    'package' => $request->packagemigrate,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineOne,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus,
                                    'package' => $request->packagemigrate,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);

                                $uplineOneData = DB::table('users')
                                    ->where('mySponsorId', auth()->user()->uplineOne)
                                    ->first();
                                if ($uplineOneData != null) {
                                    $uplineOnePoint = $uplineOneData->point + $point;
                                    DB::table('users')
                                        ->where('mySponsorId', auth()->user()->uplineOne)
                                        ->update(['point' => $uplineOnePoint]);

                                    DB::table('points')->insert([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->uplineOne,
                                        'username' => auth()->user()->uplineOne,
                                        'point' => $point,
                                        'package' => 'None',
                                        'sponsor' => auth()->user()->uplineOne,
                                        'status' => 'CONFIRM',
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                    ]);

                                } else {
                                    return back()->with('toast_success', 'Transaction Successful');
                                }
                                // email
                                // return back()->with('toast_success', 'Transaction Successful');
                            } else {
                                return back()->with('toast_error', 'Invalid Transaction');
                            }
                        } else {
                            if ($request->packagemigrate == 'Silver') {
                                $goldenbonus = 0;
                                $expectedEarning =
                                    auth()->user()->expectedEarning +
                                    ($newpackageamount * 500) / 100;
                                // update
                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('package', 'Basic')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Bronze')
                                    ->update(['package' => $datapackage->packageName]);

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['expectedEarning' => $expectedEarning]);
                                // create
                                buypackage::create([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'phoneNumber' => auth()->user()->userId,
                                    'amount' => $newpackageamount,
                                    'package' => $datapackage->packageName,
                                    'goldenBonus' => $goldenbonus,
                                    'goldenBonusStatus' => 'Pending',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);

                                // bonus
                                $bonus = 2500 - $oldbonus;

                                $data = DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('sponsor', '!=', 'Admin')
                                    ->first();
                                $point = 0.2 - $oldpoint;
                                $oldpoint = auth()->user()->point;
                                $newpoint = $oldpoint + $point;

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['point' => $newpoint]);

                                DB::table('points')->insert([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'point' => $point,
                                    'package' => 'None',
                                    'sponsor' => auth()->user()->username,
                                    'status' => 'CONFIRM',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);

                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->mySponsorId,
                                    'sponsorId' => auth()->user()->mySponsorId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus,
                                    'package' => $request->packagemigrate,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineOne,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus,
                                    'package' => $request->packagemigrate,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);

                                // email
                                return back()->with('toast_success', 'Transaction Successful');
                            } elseif ($request->packagemigrate == 'Gold') {
                                $goldenbonus = 0;
                                $expectedEarning =
                                    auth()->user()->expectedEarning +
                                    ($newpackageamount * 500) / 100;
                                // update
                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('package', 'Basic')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Bronze')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Silver')
                                    ->update(['package' => $datapackage->packageName]);
                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['expectedEarning' => $expectedEarning]);
                                // create
                                buypackage::create([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'phoneNumber' => auth()->user()->userId,
                                    'amount' => $newpackageamount,
                                    'package' => $datapackage->packageName,
                                    'goldenBonus' => $goldenbonus,
                                    'goldenBonusStatus' => 'Pending',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                // bonus
                                $bonus = $oldbonus - 5000;
                                $data = DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('sponsor', '!=', 'Admin')
                                    ->first();
                                $point = $oldpoint - 0.3;
                                $oldpoint = auth()->user()->point;
                                $newpoint = $oldpoint + $point;

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['point' => $newpoint]);

                                DB::table('points')->insert([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'point' => $point,
                                    'package' => 'None',
                                    'sponsor' => auth()->user()->username,
                                    'status' => 'CONFIRM',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);

                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->mySponsorId,
                                    'sponsorId' => auth()->user()->mySponsorId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus,
                                    'package' => $request->packagemigrate,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineOne,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus,
                                    'package' => $request->packagemigrate,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);


                                // email
                                return back()->with('toast_success', 'Transaction Successful');
                            } elseif ($request->packagemigrate == 'Platinum') {
                                $goldenbonus = 0;
                                $expectedEarning =
                                    auth()->user()->expectedEarning +
                                    ($newpackageamount * 500) / 100;
                                // update
                                $point = 0.5 - $oldpoint;
                                $oldpoint = auth()->user()->point;
                                $newpoint = $oldpoint + $point;
                                // update
                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('package', 'Basic')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Bronze')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Silver')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Gold')
                                    ->update(['package' => $datapackage->packageName]);

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update([
                                        'point' => $newpoint,
                                        'expectedEarning' => $expectedEarning,
                                    ]);

                                DB::table('points')->insert([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'point' => $point,
                                    'package' => 'None',
                                    'sponsor' => auth()->user()->username,
                                    'status' => 'CONFIRM',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);

                                // create
                                buypackage::create([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'phoneNumber' => auth()->user()->userId,
                                    'amount' => $newpackageamount,
                                    'package' => $datapackage->packageName,
                                    'goldenBonus' => $goldenbonus,
                                    'goldenBonusStatus' => 'Pending',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                // bonus
                                $bonus = 10000 - $oldbonus;
                                // $data = DB::table('users')
                                //     ->where('userId', auth()->user()->userId)
                                //     ->where('sponsor', '!=', 'Admin')
                                //     ->first();
                                // $sponsordata = $data->sponsor;
                                // $bronzepoint = 0.3;
                                // $oldpoint = $data->point;
                                // $newpoint = $oldpoint + $bronzepoint;

                                // DB::table('users')
                                //     ->where('userId', auth()->user()->userId)
                                //     ->update(['point' => $newpoint]);
                                // DB::table('users')
                                //     ->where('userId', auth()->user()->userId)
                                //     ->where('mySponsorId', $sponsordata)
                                //     ->update(['point' => $newpoint]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->mySponsorId,
                                    'sponsorId' => auth()->user()->mySponsorId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus,
                                    'package' => $request->packagemigrate,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineOne,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus,
                                    'package' => $request->packagemigrate,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);

                                // email
                                return back()->with('toast_success', 'Transaction Successful');
                            } else {
                                return back()->with('toast_error', 'Invalid Transaction');
                            }
                        }
                    }
                } else {
                    if ($totalamount < $datapackage->packageAmount) {
                        return redirect()
                            ->route('fund')
                            ->with('toast_error', 'Insufficient amount for the transaction');
                    } else {
                        DB::table('transactions')->insert([
                            'transactionId' => $this->randomDigit(),
                            'userId' => auth()->user()->userId,
                            'username' => auth()->user()->username,
                            'email' => auth()->user()->email,
                            'phoneNumber' => auth()->user()->phoneNumber,
                            'amount' => $datapackage->packageAmount,
                            'transactionType' => 'Package Transaction',
                            'transactionService' => $request->package . ' ' . 'Package',
                            'paymentMethod' => 'wallet',
                            'Admin' => 'None',

                            'status' => 'CONFIRM',
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        ]);
                        DB::table('users')
                            ->where('userId', auth()->user()->userId)
                            ->update(['rank' => 'Paid Partner']);
                        if (
                            User::where('sponsor', auth()->user()->sponsor)->exists() &&
                            auth()->user()->sponsor != 'Admin'
                        ) {
                            if ($request->package == 'Bronze') {
                                $goldenbonus = 0;
                                $expectedEarning =
                                    auth()->user()->expectedEarning +
                                    ($datapackage->packageAmount * 500) / 100;
                                // update
                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('package', 'Basic')
                                    ->update([
                                        'package' => $datapackage->packageName,
                                        'expectedEarning' => $expectedEarning,
                                    ]);
                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['expectedEarning' => $expectedEarning]);
                                // create
                                buypackage::create([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'phoneNumber' => auth()->user()->userId,
                                    'amount' => $datapackage->packageAmount,
                                    'package' => $datapackage->packageName,
                                    'goldenBonus' => $goldenbonus,
                                    'goldenBonusStatus' => 'Pending',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                // bonus
                                $bonus1000 = 1000;
                                $bonus500 = 500;

                                // points for user
                                $data = DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('sponsor', '!=', 'Admin')
                                    ->first();
                                $sponsordata = $data->sponsor;
                                $point = 0.1;
                                $oldpoint = auth()->user()->point;
                                $newpoint = $oldpoint + $point;

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['point' => $newpoint]);

                                DB::table('points')->insert([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'point' => $point,
                                    'package' => auth()->user()->package,
                                    'sponsor' => auth()->user()->username,
                                    'status' => 'CONFIRM',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);

                                // DB::table('users')
                                // ->where('mySponsorId', auth()->user()->uplineOne)
                                // ->where('mySponsorId', $sponsordata)
                                // ->update(['point' => $newpoint]);

                                // DB::table('points')->insert([
                                //     'transactionId' => $this->randomDigit(),
                                //     'userId' => $sponsordata,
                                //     'username' => $sponsordata,
                                //     'point' => $point,
                                //     'package' => 'None',
                                //     'sponsor' => $sponsordata,
                                //     'status' => 'CONFIRM',
                                //     "created_at" => date('Y-m-d H:i:s'),
                                //     "updated_at" => date('Y-m-d H:i:s'),
                                // ]);

                                // add bonuses
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->mySponsorId,
                                    'sponsorId' => auth()->user()->mySponsorId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus1000,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineOne,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus1000,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineTwo,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);


                                // points for uplines
                                // upline One
                                $uplineOneData = DB::table('users')
                                    ->where('mySponsorId', auth()->user()->uplineOne)
                                    ->first();

                                if ($uplineOneData != null) {
                                    $uplineOnePoint = $uplineOneData->point + $point;
                                    DB::table('users')
                                        ->where('mySponsorId', auth()->user()->uplineOne)
                                        ->update(['point' => $uplineOnePoint]);
                                    DB::table('points')->insert([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->uplineOne,
                                        'username' => auth()->user()->uplineOne,
                                        'point' => $point,
                                        'package' => 'None',
                                        'sponsor' => auth()->user()->uplineOne,
                                        'status' => 'CONFIRM',
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                    ]);
                                    // upline two
                                    $uplineTwoData = DB::table('users')
                                        ->where('mySponsorId', auth()->user()->uplineTwo)
                                        ->first();
                                    if ($uplineTwoData != null) {
                                        $uplineTwoPoint = $uplineTwoData->point + $point;
                                        DB::table('users')
                                            ->where('mySponsorId', auth()->user()->uplineTwo)
                                            ->update(['point' => $uplineTwoPoint]);

                                        DB::table('points')->insert([
                                            'transactionId' => $this->randomDigit(),
                                            'userId' => auth()->user()->uplineTwo,
                                            'username' => auth()->user()->uplineTwo,
                                            'point' => $point,
                                            'package' => 'None',
                                            'sponsor' => auth()->user()->uplineTwo,
                                            'status' => 'CONFIRM',
                                            "created_at" => date('Y-m-d H:i:s'),
                                            "updated_at" => date('Y-m-d H:i:s'),
                                        ]);
                                        return back()->with(
                                            'toast_success',
                                            'Transaction Successful'
                                        );
                                        // upline three


                                    } else {
                                        return back()->with('toast_success', 'Transaction Successful');
                                    }
                                } else {
                                    return back()->with('toast_success', 'Transaction Successful');
                                }
                                // email
                                // return back()->with('toast_success', 'Transaction Successful');
                            } elseif ($request->package == 'Silver') {
                                // return dd('sds');
                                $goldenbonus = 0;
                                $expectedEarning =
                                    auth()->user()->expectedEarning +
                                    ($datapackage->packageAmount * 500) / 100;
                                // update
                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('package', 'Basic')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Bronze')
                                    ->update(['package' => $datapackage->packageName]);

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['expectedEarning' => $expectedEarning]);
                                // create
                                buypackage::create([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'phoneNumber' => auth()->user()->userId,
                                    'amount' => $datapackage->packageAmount,
                                    'package' => $datapackage->packageName,
                                    'goldenBonus' => $goldenbonus,
                                    'goldenBonusStatus' => 'Pending',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                // bonus
                                $bonus1000 = 2500;
                                $bonus500 = 1250;
                                $data = DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('sponsor', '!=', 'Admin')
                                    ->first();

                                $sponsordata = $data->sponsor;
                                $point = 0.2;
                                $oldpoint = auth()->user()->point;
                                $newpoint = $oldpoint + $point;

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['point' => $newpoint]);

                                DB::table('points')->insert([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'point' => $point,
                                    'package' => 'None',
                                    'sponsor' => auth()->user()->username,
                                    'status' => 'CONFIRM',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);

                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->mySponsorId,
                                    'sponsorId' => auth()->user()->mySponsorId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus1000,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineOne,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus1000,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineTwo,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineThree,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);


                                $uplineOneData = DB::table('users')
                                    ->where('mySponsorId', auth()->user()->uplineOne)
                                    ->first();

                                if ($uplineOneData != null) {
                                    $uplineOnePoint = $uplineOneData->point + $point;
                                    DB::table('users')
                                        ->where('mySponsorId', auth()->user()->uplineOne)
                                        ->update(['point' => $uplineOnePoint]);

                                    DB::table('points')->insert([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->uplineOne,
                                        'username' => auth()->user()->uplineOne,
                                        'point' => $point,
                                        'package' => 'None',
                                        'sponsor' => auth()->user()->uplineOne,
                                        'status' => 'CONFIRM',
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                    ]);
                                    // upline two
                                    $uplineTwoData = DB::table('users')
                                        ->where('mySponsorId', auth()->user()->uplineTwo)
                                        ->first();
                                    if ($uplineTwoData != null) {
                                        $uplineTwoPoint = $uplineTwoData->point + $point;
                                        DB::table('users')
                                            ->where('mySponsorId', auth()->user()->uplineTwo)
                                            ->update(['point' => $uplineTwoPoint]);

                                        DB::table('points')->insert([
                                            'transactionId' => $this->randomDigit(),
                                            'userId' => auth()->user()->uplineTwo,
                                            'username' => auth()->user()->uplineTwo,
                                            'point' => $point,
                                            'package' => 'None',
                                            'sponsor' => auth()->user()->uplineTwo,
                                            'status' => 'CONFIRM',
                                            "created_at" => date('Y-m-d H:i:s'),
                                            "updated_at" => date('Y-m-d H:i:s'),
                                        ]);
                                        // return back()->with(
                                        //     'toast_success',
                                        //     'Transaction Successful'
                                        // );
                                        // upline three
                                        $uplineThreeData = DB::table('users')
                                            ->where('mySponsorId', auth()->user()->uplineThree)
                                            ->first();
                                        if ($uplineThreeData != null) {
                                            $uplineThreePoint = $uplineThreeData->point + $point;
                                            DB::table('users')
                                                ->where('mySponsorId', auth()->user()->uplineThree)
                                                ->update(['point' => $uplineThreePoint]);

                                            DB::table('points')->insert([
                                                'transactionId' => $this->randomDigit(),
                                                'userId' => auth()->user()->uplineThree,
                                                'username' => auth()->user()->uplineThree,
                                                'point' => $point,
                                                'package' => 'None',
                                                'sponsor' => auth()->user()->uplineThree,
                                                'status' => 'CONFIRM',
                                                "created_at" => date('Y-m-d H:i:s'),
                                                "updated_at" => date('Y-m-d H:i:s'),
                                            ]);


                                        } else {
                                            return back()->with(
                                                'toast_success',
                                                'Transaction Successful'
                                            );
                                        }
                                    } else {
                                        return back()->with('toast_success', 'Transaction Successful');
                                    }
                                } else {
                                    return back()->with('toast_success', 'Transaction Successful');
                                }
                                // email
                                // return back()->with('toast_success', 'Transaction Successful');
                            } elseif ($request->package == 'Gold') {
                                $goldenbonus = 0;
                                $expectedEarning =
                                    auth()->user()->expectedEarning +
                                    ($datapackage->packageAmount * 500) / 100;
                                // update
                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('package', 'Basic')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Bronze')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Silver')
                                    ->update(['package' => $datapackage->packageName]);
                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['expectedEarning' => $expectedEarning]);
                                // create
                                buypackage::create([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'phoneNumber' => auth()->user()->userId,
                                    'amount' => $datapackage->packageAmount,
                                    'package' => $datapackage->packageName,
                                    'goldenBonus' => $goldenbonus,
                                    'goldenBonusStatus' => 'Pending',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                // bonus
                                $bonus1000 = 5000;
                                $bonus500 = 2500;
                                $data = DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('sponsor', '!=', 'Admin')
                                    ->first();
                                $sponsordata = $data->sponsor;
                                $point = 0.3;
                                $oldpoint = auth()->user()->point;
                                $newpoint = $oldpoint + $point;

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['point' => $newpoint]);

                                DB::table('points')->insert([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'point' => $point,
                                    'package' => 'None',
                                    'sponsor' => auth()->user()->username,
                                    'status' => 'CONFIRM',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);

                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->mySponsorId,
                                    'sponsorId' => auth()->user()->mySponsorId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus1000,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineOne,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus1000,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineTwo,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineThree,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineFour,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);

                                $uplineOneData = DB::table('users')
                                    ->where('mySponsorId', auth()->user()->uplineOne)
                                    ->first();
                                if ($uplineOneData != null) {
                                    $uplineOnePoint = $uplineOneData->point + $point;
                                    DB::table('users')
                                        ->where('mySponsorId', auth()->user()->uplineOne)
                                        ->update(['point' => $uplineOnePoint]);

                                    DB::table('points')->insert([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->uplineOne,
                                        'username' => auth()->user()->uplineOne,
                                        'point' => $point,
                                        'package' => 'None',
                                        'sponsor' => auth()->user()->uplineOne,
                                        'status' => 'CONFIRM',
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                    ]);
                                    // upline two
                                    $uplineTwoData = DB::table('users')
                                        ->where('mySponsorId', auth()->user()->uplineTwo)
                                        ->first();
                                    if ($uplineTwoData != null) {
                                        $uplineTwoPoint = $uplineTwoData->point + $point;
                                        DB::table('users')
                                            ->where('mySponsorId', auth()->user()->uplineTwo)
                                            ->update(['point' => $uplineTwoPoint]);

                                        DB::table('points')->insert([
                                            'transactionId' => $this->randomDigit(),
                                            'userId' => auth()->user()->uplineTwo,
                                            'username' => auth()->user()->uplineTwo,
                                            'point' => $point,
                                            'package' => 'None',
                                            'sponsor' => auth()->user()->uplineTwo,
                                            'status' => 'CONFIRM',
                                            "created_at" => date('Y-m-d H:i:s'),
                                            "updated_at" => date('Y-m-d H:i:s'),
                                        ]);

                                        // upline three
                                        $uplineThreeData = DB::table('users')
                                            ->where('mySponsorId', auth()->user()->uplineThree)
                                            ->first();
                                        if ($uplineThreeData != null) {
                                            $uplineThreePoint = $uplineThreeData->point + $point;
                                            DB::table('users')
                                                ->where('mySponsorId', auth()->user()->uplineThree)
                                                ->update(['point' => $uplineThreePoint]);

                                            DB::table('points')->insert([
                                                'transactionId' => $this->randomDigit(),
                                                'userId' => auth()->user()->uplineThree,
                                                'username' => auth()->user()->uplineThree,
                                                'point' => $point,
                                                'package' => 'None',
                                                'sponsor' => auth()->user()->uplineThree,
                                                'status' => 'CONFIRM',
                                                "created_at" => date('Y-m-d H:i:s'),
                                                "updated_at" => date('Y-m-d H:i:s'),
                                            ]);
                                            // upline four
                                            $uplineFourData = DB::table('users')
                                                ->where('mySponsorId', auth()->user()->uplineFour)
                                                ->first();
                                            if ($uplineFourData != null) {
                                                $uplineFourPoint = $uplineFourData->point + $point;
                                                DB::table('users')
                                                    ->where('mySponsorId', auth()->user()->uplineFour)
                                                    ->update(['point' => $uplineFourPoint]);

                                                DB::table('points')->insert([
                                                    'transactionId' => $this->randomDigit(),
                                                    'userId' => auth()->user()->uplineFour,
                                                    'username' => auth()->user()->uplineFour,
                                                    'point' => $point,
                                                    'package' => 'None',
                                                    'sponsor' => auth()->user()->uplineFour,
                                                    'status' => 'CONFIRM',
                                                    "created_at" => date('Y-m-d H:i:s'),
                                                    "updated_at" => date('Y-m-d H:i:s'),
                                                ]);

                                            } else {
                                                return back()->with(
                                                    'toast_success',
                                                    'Transaction Successful'
                                                );
                                            }
                                        } else {
                                            return back()->with(
                                                'toast_success',
                                                'Transaction Successful'
                                            );
                                        }
                                    } else {
                                        return back()->with('toast_success', 'Transaction Successful');
                                    }
                                } else {
                                    return back()->with('toast_success', 'Transaction Successful');
                                }
                                // email
                                // return back()->with('toast_success', 'Transaction Successful');
                            } elseif ($request->package == 'Platinum') {
                                $goldenbonus = 0;
                                $expectedEarning =
                                    auth()->user()->expectedEarning +
                                    ($datapackage->packageAmount * 500) / 100;
                                // update
                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('package', 'Basic')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Bronze')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Silver')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Gold')
                                    ->update(['package' => $datapackage->packageName]);

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['expectedEarning' => $expectedEarning]);

                                // create
                                buypackage::create([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'phoneNumber' => auth()->user()->userId,
                                    'amount' => $datapackage->packageAmount,
                                    'package' => $datapackage->packageName,
                                    'goldenBonus' => $goldenbonus,
                                    'goldenBonusStatus' => 'Pending',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                // bonus
                                $bonus1000 = 10000;
                                $bonus500 = 5000;
                                $data = DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('sponsor', '!=', 'Admin')
                                    ->first();
                                $sponsordata = $data->sponsor;
                                $point = 0.5;
                                $oldpoint = auth()->user()->point;
                                $newpoint = $oldpoint + $point;

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['point' => $newpoint]);

                                DB::table('points')->insert([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'point' => $point,
                                    'package' => 'None',
                                    'sponsor' => auth()->user()->username,
                                    'status' => 'CONFIRM',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);

                                // DB::table('users')
                                //     ->where('userId', auth()->user()->userId)
                                //     ->where('mySponsorId', $sponsordata)
                                //     ->update(['point' => $newpoint]);

                                // DB::table('points')->insert([
                                //         'transactionId' => $this->randomDigit(),
                                //         'userId' => $sponsordata,
                                //         'username' => $sponsordata,
                                //         'point' => $point,
                                //         'package' => 'None',
                                //         'sponsor' => $sponsordata,
                                //         'status' => 'CONFIRM',
                                //         "created_at" => date('Y-m-d H:i:s'),
                                //         "updated_at" => date('Y-m-d H:i:s'),
                                //     ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->mySponsorId,
                                    'sponsorId' => auth()->user()->mySponsorId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus1000,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineOne,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus1000,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineTwo,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineThree,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineFour,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineFive,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);

                                $uplineOneData = DB::table('users')
                                    ->where('mySponsorId', auth()->user()->uplineOne)
                                    ->first();
                                if ($uplineOneData != null) {
                                    $uplineOnePoint = $uplineOneData->point + $point;
                                    DB::table('users')
                                        ->where('mySponsorId', auth()->user()->uplineOne)
                                        ->update(['point' => $uplineOnePoint]);

                                    DB::table('points')->insert([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->uplineOne,
                                        'username' => auth()->user()->uplineOne,
                                        'point' => $point,
                                        'package' => 'None',
                                        'sponsor' => auth()->user()->uplineOne,
                                        'status' => 'CONFIRM',
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                    ]);

                                    // upline two
                                    $uplineTwoData = DB::table('users')
                                        ->where('mySponsorId', auth()->user()->uplineTwo)
                                        ->first();
                                    if ($uplineTwoData != null) {
                                        $uplineTwoPoint = $uplineTwoData->point + $point;
                                        DB::table('users')
                                            ->where('mySponsorId', auth()->user()->uplineTwo)
                                            ->update(['point' => $uplineTwoPoint]);

                                        DB::table('points')->insert([
                                            'transactionId' => $this->randomDigit(),
                                            'userId' => auth()->user()->uplineTwo,
                                            'username' => auth()->user()->uplineTwo,
                                            'point' => $point,
                                            'package' => 'None',
                                            'sponsor' => auth()->user()->uplineTwo,
                                            'status' => 'CONFIRM',
                                            "created_at" => date('Y-m-d H:i:s'),
                                            "updated_at" => date('Y-m-d H:i:s'),
                                        ]);
                                        // upline three
                                        $uplineThreeData = DB::table('users')
                                            ->where('mySponsorId', auth()->user()->uplineThree)
                                            ->first();
                                        if ($uplineThreeData != null) {
                                            $uplineThreePoint = $uplineThreeData->point + $point;
                                            DB::table('users')
                                                ->where('mySponsorId', auth()->user()->uplineThree)
                                                ->update(['point' => $uplineThreePoint]);

                                            DB::table('points')->insert([
                                                'transactionId' => $this->randomDigit(),
                                                'userId' => auth()->user()->uplineThree,
                                                'username' => auth()->user()->uplineThree,
                                                'point' => $point,
                                                'package' => 'None',
                                                'sponsor' => auth()->user()->uplineThree,
                                                'status' => 'CONFIRM',
                                                "created_at" => date('Y-m-d H:i:s'),
                                                "updated_at" => date('Y-m-d H:i:s'),
                                            ]);

                                            // upline four
                                            $uplineFourData = DB::table('users')
                                                ->where('mySponsorId', auth()->user()->uplineFour)
                                                ->first();
                                            if ($uplineFourData != null) {
                                                $uplineFourPoint = $uplineFourData->point + $point;
                                                DB::table('users')
                                                    ->where('mySponsorId', auth()->user()->uplineFour)
                                                    ->update(['point' => $uplineFourPoint]);

                                                DB::table('points')->insert([
                                                    'transactionId' => $this->randomDigit(),
                                                    'userId' => auth()->user()->uplineFour,
                                                    'username' => auth()->user()->uplineFour,
                                                    'point' => $point,
                                                    'package' => 'None',
                                                    'sponsor' => auth()->user()->uplineFour,
                                                    'status' => 'CONFIRM',
                                                    "created_at" => date('Y-m-d H:i:s'),
                                                    "updated_at" => date('Y-m-d H:i:s'),
                                                ]);

                                                // upline Five
                                                $uplineFiveData = DB::table('users')
                                                    ->where('mySponsorId', auth()->user()->uplineFive)
                                                    ->first();
                                                if ($uplineFiveData != null) {
                                                    $uplineFivePoint = $uplineFiveData->point + $point;
                                                    DB::table('users')
                                                        ->where(
                                                            'mySponsorId',
                                                            auth()->user()->uplineFive
                                                        )
                                                        ->update(['point' => $uplineFivePoint]);

                                                    DB::table('points')->insert([
                                                        'transactionId' => $this->randomDigit(),
                                                        'userId' => auth()->user()->uplineFive,
                                                        'username' => auth()->user()->uplineFive,
                                                        'point' => $point,
                                                        'package' => 'None',
                                                        'sponsor' => auth()->user()->uplineFive,
                                                        'status' => 'CONFIRM',
                                                        "created_at" => date('Y-m-d H:i:s'),
                                                        "updated_at" => date('Y-m-d H:i:s'),
                                                    ]);
                                                    return back()->with(
                                                        'toast_success',
                                                        'Transaction Successful'
                                                    );
                                                } else {
                                                    return back()->with(
                                                        'toast_success',
                                                        'Transaction Successful'
                                                    );
                                                }
                                            } else {
                                                return back()->with(
                                                    'toast_success',
                                                    'Transaction Successful'
                                                );
                                            }
                                        } else {
                                            return back()->with(
                                                'toast_success',
                                                'Transaction Successful'
                                            );
                                        }
                                    } else {
                                        return back()->with('toast_success', 'Transaction Successful');
                                    }
                                } else {
                                    return back()->with('toast_success', 'Transaction Successful');
                                }
                                // email
                                // return back()->with('toast_success', 'Transaction Successful');
                            } else {
                                return back()->with('toast_error', 'Invalid Transaction');
                            }
                        } else {
                            if ($request->package == 'Bronze') {
                                $goldenbonus = 0;
                                $point = 0.1;
                                $oldpoint = auth()->user()->point;
                                $newpoint = $oldpoint + $point;
                                // update
                                $expectedEarning =
                                    auth()->user()->expectedEarning +
                                    ($datapackage->packageAmount * 500) / 100;
                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('package', 'Basic')
                                    ->update(['package' => $datapackage->packageName]);

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update([
                                        'point' => $newpoint,
                                        'expectedEarning' => $expectedEarning,
                                    ]);

                                DB::table('points')->insert([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'point' => $point,
                                    'package' => 'None',
                                    'sponsor' => auth()->user()->username,
                                    'status' => 'CONFIRM',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);
                                // create
                                buypackage::create([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'phoneNumber' => auth()->user()->userId,
                                    'amount' => $datapackage->packageAmount,
                                    'package' => $datapackage->packageName,
                                    'goldenBonus' => $goldenbonus,
                                    'goldenBonusStatus' => 'Pending',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);

                                // bonus
                                $bonus1000 = 1000;
                                $bonus500 = 500;

                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->mySponsorId,
                                    'sponsorId' => auth()->user()->mySponsorId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus1000,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);

                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineOne,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus1000,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineTwo,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineThree,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineFour,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineFive,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                // email
                                return back()->with('toast_success', 'Transaction Successful');
                            } elseif ($request->package == 'Silver') {
                                $goldenbonus = 0;
                                $expectedEarning =
                                    auth()->user()->expectedEarning +
                                    ($datapackage->packageAmount * 500) / 100;
                                // update
                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('package', 'Basic')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Bronze')
                                    ->update(['package' => $datapackage->packageName]);

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['expectedEarning' => $expectedEarning]);
                                // create
                                buypackage::create([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'phoneNumber' => auth()->user()->userId,
                                    'amount' => $datapackage->packageAmount,
                                    'package' => $datapackage->packageName,
                                    'goldenBonus' => $goldenbonus,
                                    'goldenBonusStatus' => 'Pending',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                // bonus
                                $bonus1000 = 2500;
                                $bonus500 = 1250;
                                $data = DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('sponsor', '!=', 'Admin')
                                    ->first();
                                $point = 0.2;
                                $oldpoint = auth()->user()->point;
                                $newpoint = $oldpoint + $point;

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['point' => $newpoint]);

                                DB::table('points')->insert([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'point' => $point,
                                    'package' => 'None',
                                    'sponsor' => auth()->user()->username,
                                    'status' => 'CONFIRM',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);

                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->mySponsorId,
                                    'sponsorId' => auth()->user()->mySponsorId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus1000,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineOne,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus1000,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineTwo,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineThree,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineFour,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineFive,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                // email
                                return back()->with('toast_success', 'Transaction Successful');
                            } elseif ($request->package == 'Gold') {
                                $goldenbonus = 0;
                                $expectedEarning =
                                    auth()->user()->expectedEarning +
                                    ($datapackage->packageAmount * 500) / 100;
                                // update
                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('package', 'Basic')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Bronze')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Silver')
                                    ->update(['package' => $datapackage->packageName]);
                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['expectedEarning' => $expectedEarning]);
                                // create
                                buypackage::create([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'phoneNumber' => auth()->user()->userId,
                                    'amount' => $datapackage->packageAmount,
                                    'package' => $datapackage->packageName,
                                    'goldenBonus' => $goldenbonus,
                                    'goldenBonusStatus' => 'Pending',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                // bonus
                                $bonus1000 = 5000;
                                $bonus500 = 2500;
                                $data = DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('sponsor', '!=', 'Admin')
                                    ->first();
                                $point = 0.3;
                                $oldpoint = auth()->user()->point;
                                $newpoint = $oldpoint + $point;

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update(['point' => $newpoint]);

                                DB::table('points')->insert([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'point' => $point,
                                    'package' => 'None',
                                    'sponsor' => auth()->user()->username,
                                    'status' => 'CONFIRM',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);

                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->mySponsorId,
                                    'sponsorId' => auth()->user()->mySponsorId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus1000,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineOne,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus1000,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineTwo,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineThree,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineFour,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineFive,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                // email
                                return back()->with('toast_success', 'Transaction Successful');
                            } elseif ($request->package == 'Platinum') {
                                $goldenbonus = 0;
                                $expectedEarning =
                                    auth()->user()->expectedEarning +
                                    ($datapackage->packageAmount * 500) / 100;
                                // update
                                $point = 0.5;
                                $oldpoint = auth()->user()->point;
                                $newpoint = $oldpoint + $point;
                                // update
                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->where('package', 'Basic')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Bronze')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Silver')
                                    ->orWhere('userId', auth()->user()->userId)
                                    ->where('package', 'Gold')
                                    ->update(['package' => $datapackage->packageName]);

                                DB::table('users')
                                    ->where('userId', auth()->user()->userId)
                                    ->update([
                                        'point' => $newpoint,
                                        'expectedEarning' => $expectedEarning,
                                    ]);

                                DB::table('points')->insert([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'point' => $point,
                                    'package' => 'None',
                                    'sponsor' => auth()->user()->username,
                                    'status' => 'CONFIRM',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);

                                // create
                                buypackage::create([
                                    'transactionId' => $this->randomDigit(),
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'phoneNumber' => auth()->user()->userId,
                                    'amount' => $datapackage->packageAmount,
                                    'package' => $datapackage->packageName,
                                    'goldenBonus' => $goldenbonus,
                                    'goldenBonusStatus' => 'Pending',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                // bonus
                                $bonus1000 = 10000;
                                $bonus500 = 5000;
                                // $data = DB::table('users')
                                //     ->where('userId', auth()->user()->userId)
                                //     ->where('sponsor', '!=', 'Admin')
                                //     ->first();
                                // $sponsordata = $data->sponsor;
                                // $bronzepoint = 0.3;
                                // $oldpoint = $data->point;
                                // $newpoint = $oldpoint + $bronzepoint;

                                // DB::table('users')
                                //     ->where('userId', auth()->user()->userId)
                                //     ->update(['point' => $newpoint]);
                                // DB::table('users')
                                //     ->where('userId', auth()->user()->userId)
                                //     ->where('mySponsorId', $sponsordata)
                                //     ->update(['point' => $newpoint]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->mySponsorId,
                                    'sponsorId' => auth()->user()->mySponsorId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus1000,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineOne,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus1000,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineTwo,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineThree,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineFour,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineFive,
                                    'sponsorId' => auth()->user()->sponsor,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bonus500,
                                    'package' => $request->package,
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);

                                // email
                                return back()->with('toast_success', 'Transaction Successful');
                            } else {
                                return back()->with('toast_error', 'Invalid Transaction');
                            }
                        }
                    }
                }
            }
        } elseif ($request->migrate == "NO" && $request->payment == 'epin') {
            $dataepin = DB::table('epins')
                ->where('pinId', $request->epin)
                ->first();
            if ($dataepin == null) {
                return back()->with('toast_error', 'Failed transaction');
            } else {
                $amountEpin = $dataepin->amount - $dataepin->discount;

                if ($request->package == "NONE") {
                    return back()->with('toast_error', 'Failed transaction');
                } else {
                    if ($amountEpin >= $datapackage->packageAmount) {
                        $newamount = $dataepin->discount + $datapackage->packageAmount;
                        DB::table('epins')
                            ->where('pinId', $request->epin)
                            ->update([
                                'discount' => $newamount,
                            ]);

                        DB::table('epins')
                            ->where('pinId', $request->epin)
                            ->where('discount', $newamount)
                            ->update([
                                'status' => "EXPIRE",
                            ]);

                        if ($amountEpin < $datapackage->packageAmount) {
                            return redirect()
                                ->route('fund')
                                ->with('toast_error', 'Insufficient amount for the transaction');
                        } else {
                            DB::table('transactions')->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => auth()->user()->phoneNumber,
                                'amount' => 0,
                                'transactionType' => 'Package Transaction',
                                'transactionService' => $request->package . ' ' . 'Package',
                                'paymentMethod' => 'EPIN',
                                'status' => 'EPIN',
                                'Admin' => 'None',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);
                            DB::table('users')
                                ->where('userId', auth()->user()->userId)
                                ->update(['rank' => 'Paid Partner']);
                            if (
                                User::where('sponsor', auth()->user()->sponsor)->exists() &&
                                auth()->user()->sponsor != 'Admin'
                            ) {
                                if ($request->package == 'Bronze') {
                                    $goldenbonus = 0;
                                    $expectedEarning =
                                        auth()->user()->expectedEarning +
                                        ($datapackage->packageAmount * 500) / 100;
                                    // update
                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->where('package', 'Basic')
                                        ->update([
                                            'package' => $datapackage->packageName,
                                            'expectedEarning' => $expectedEarning,
                                        ]);
                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->update(['expectedEarning' => $expectedEarning]);
                                    // create
                                    buypackage::create([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->userId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'phoneNumber' => auth()->user()->userId,
                                        'amount' => $datapackage->packageAmount,
                                        'package' => $datapackage->packageName,
                                        'goldenBonus' => $goldenbonus,
                                        'goldenBonusStatus' => 'Pending',
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    // bonus
                                    $bonus1000 = 1000;
                                    $bonus500 = 500;

                                    // points for user
                                    $data = DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->where('sponsor', '!=', 'Admin')
                                        ->first();
                                    $sponsordata = $data->sponsor;
                                    $point = 0.1;
                                    $oldpoint = auth()->user()->point;
                                    $newpoint = $oldpoint + $point;

                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->update(['point' => $newpoint]);

                                    DB::table('points')->insert([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->userId,
                                        'username' => auth()->user()->username,
                                        'point' => $point,
                                        'package' => auth()->user()->package,
                                        'sponsor' => auth()->user()->username,
                                        'status' => 'CONFIRM',
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                    ]);

                                    // DB::table('users')
                                    // ->where('mySponsorId', auth()->user()->uplineOne)
                                    // ->where('mySponsorId', $sponsordata)
                                    // ->update(['point' => $newpoint]);

                                    // DB::table('points')->insert([
                                    //     'transactionId' => $this->randomDigit(),
                                    //     'userId' => $sponsordata,
                                    //     'username' => $sponsordata,
                                    //     'point' => $point,
                                    //     'package' => 'None',
                                    //     'sponsor' => $sponsordata,
                                    //     'status' => 'CONFIRM',
                                    //     "created_at" => date('Y-m-d H:i:s'),
                                    //     "updated_at" => date('Y-m-d H:i:s'),
                                    // ]);

                                    // add bonuses
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->mySponsorId,
                                        'sponsorId' => auth()->user()->mySponsorId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus1000,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineOne,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus1000,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineTwo,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineThree,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineFour,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineFive,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);

                                    // points for uplines
                                    // upline One
                                    $uplineOneData = DB::table('users')
                                        ->where('mySponsorId', auth()->user()->uplineOne)
                                        ->first();

                                    if ($uplineOneData != null) {
                                        $uplineOnePoint = $uplineOneData->point + $point;
                                        DB::table('users')
                                            ->where('mySponsorId', auth()->user()->uplineOne)
                                            ->update(['point' => $uplineOnePoint]);
                                        DB::table('points')->insert([
                                            'transactionId' => $this->randomDigit(),
                                            'userId' => auth()->user()->uplineOne,
                                            'username' => auth()->user()->uplineOne,
                                            'point' => $point,
                                            'package' => 'None',
                                            'sponsor' => auth()->user()->uplineOne,
                                            'status' => 'CONFIRM',
                                            "created_at" => date('Y-m-d H:i:s'),
                                            "updated_at" => date('Y-m-d H:i:s'),
                                        ]);
                                        // upline two
                                        $uplineTwoData = DB::table('users')
                                            ->where('mySponsorId', auth()->user()->uplineTwo)
                                            ->first();
                                        if ($uplineTwoData != null) {
                                            $uplineTwoPoint = $uplineTwoData->point + $point;
                                            DB::table('users')
                                                ->where('mySponsorId', auth()->user()->uplineTwo)
                                                ->update(['point' => $uplineTwoPoint]);

                                            DB::table('points')->insert([
                                                'transactionId' => $this->randomDigit(),
                                                'userId' => auth()->user()->uplineTwo,
                                                'username' => auth()->user()->uplineTwo,
                                                'point' => $point,
                                                'package' => 'None',
                                                'sponsor' => auth()->user()->uplineTwo,
                                                'status' => 'CONFIRM',
                                                "created_at" => date('Y-m-d H:i:s'),
                                                "updated_at" => date('Y-m-d H:i:s'),
                                            ]);
                                            // upline three
                                            $uplineThreeData = DB::table('users')
                                                ->where('mySponsorId', auth()->user()->uplineThree)
                                                ->first();
                                            if ($uplineThreeData != null) {
                                                $uplineThreePoint =
                                                    $uplineThreeData->point + $point;
                                                DB::table('users')
                                                    ->where(
                                                        'mySponsorId',
                                                        auth()->user()->uplineThree
                                                    )
                                                    ->update(['point' => $uplineThreePoint]);

                                                DB::table('points')->insert([
                                                    'transactionId' => $this->randomDigit(),
                                                    'userId' => auth()->user()->uplineThree,
                                                    'username' => auth()->user()->uplineThree,
                                                    'point' => $point,
                                                    'package' => 'None',
                                                    'sponsor' => auth()->user()->uplineThree,
                                                    'status' => 'CONFIRM',
                                                    "created_at" => date('Y-m-d H:i:s'),
                                                    "updated_at" => date('Y-m-d H:i:s'),
                                                ]);
                                                // upline four
                                                $uplineFourData = DB::table('users')
                                                    ->where(
                                                        'mySponsorId',
                                                        auth()->user()->uplineFour
                                                    )
                                                    ->first();
                                                if ($uplineFourData != null) {
                                                    $uplineFourPoint =
                                                        $uplineFourData->point + $point;
                                                    DB::table('users')
                                                        ->where(
                                                            'mySponsorId',
                                                            auth()->user()->uplineFour
                                                        )
                                                        ->update(['point' => $uplineFourPoint]);

                                                    DB::table('points')->insert([
                                                        'transactionId' => $this->randomDigit(),
                                                        'userId' => auth()->user()->uplineFour,
                                                        'username' => auth()->user()->uplineFour,
                                                        'point' => $point,
                                                        'package' => 'None',
                                                        'sponsor' => auth()->user()->uplineFour,
                                                        'status' => 'CONFIRM',
                                                        "created_at" => date('Y-m-d H:i:s'),
                                                        "updated_at" => date('Y-m-d H:i:s'),
                                                    ]);
                                                    // upline Five
                                                    $uplineFiveData = DB::table('users')
                                                        ->where(
                                                            'mySponsorId',
                                                            auth()->user()->uplineFive
                                                        )
                                                        ->first();
                                                    if ($uplineFiveData != null) {
                                                        $uplineFivePoint =
                                                            $uplineFiveData->point + $point;
                                                        DB::table('users')
                                                            ->where(
                                                                'mySponsorId',
                                                                auth()->user()->uplineFive
                                                            )
                                                            ->update(['point' => $uplineFivePoint]);

                                                        DB::table('points')->insert([
                                                            'transactionId' => $this->randomDigit(),
                                                            'userId' => auth()->user()->uplineFive,
                                                            'username' => auth()->user()
                                                                ->uplineFive,
                                                            'point' => $point,
                                                            'package' => 'None',
                                                            'sponsor' => auth()->user()->uplineFive,
                                                            'status' => 'CONFIRM',
                                                            "created_at" => date('Y-m-d H:i:s'),
                                                            "updated_at" => date('Y-m-d H:i:s'),
                                                        ]);
                                                        return back()->with(
                                                            'toast_success',
                                                            'Transaction Successful'
                                                        );
                                                    } else {
                                                        return back()->with(
                                                            'toast_success',
                                                            'Transaction Successful'
                                                        );
                                                    }
                                                } else {
                                                    return back()->with(
                                                        'toast_success',
                                                        'Transaction Successful'
                                                    );
                                                }
                                            } else {
                                                return back()->with(
                                                    'toast_success',
                                                    'Transaction Successful'
                                                );
                                            }
                                        } else {
                                            return back()->with(
                                                'toast_success',
                                                'Transaction Successful'
                                            );
                                        }
                                    } else {
                                        return back()->with(
                                            'toast_success',
                                            'Transaction Successful'
                                        );
                                    }
                                    // email
                                    // return back()->with('toast_success', 'Transaction Successful');
                                } elseif ($request->package == 'Silver') {
                                    // return dd('sds');
                                    $goldenbonus = 0;
                                    $expectedEarning =
                                        auth()->user()->expectedEarning +
                                        ($datapackage->packageAmount * 500) / 100;
                                    // update
                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->where('package', 'Basic')
                                        ->orWhere('userId', auth()->user()->userId)
                                        ->where('package', 'Bronze')
                                        ->update(['package' => $datapackage->packageName]);

                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->update(['expectedEarning' => $expectedEarning]);
                                    // create
                                    buypackage::create([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->userId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'phoneNumber' => auth()->user()->userId,
                                        'amount' => $datapackage->packageAmount,
                                        'package' => $datapackage->packageName,
                                        'goldenBonus' => $goldenbonus,
                                        'goldenBonusStatus' => 'Pending',
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    // bonus
                                    $bonus1000 = 2500;
                                    $bonus500 = 1250;
                                    $data = DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->where('sponsor', '!=', 'Admin')
                                        ->first();

                                    $sponsordata = $data->sponsor;
                                    $point = 0.2;
                                    $oldpoint = auth()->user()->point;
                                    $newpoint = $oldpoint + $point;

                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->update(['point' => $newpoint]);

                                    DB::table('points')->insert([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->userId,
                                        'username' => auth()->user()->username,
                                        'point' => $point,
                                        'package' => 'None',
                                        'sponsor' => auth()->user()->username,
                                        'status' => 'CONFIRM',
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                    ]);

                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->mySponsorId,
                                        'sponsorId' => auth()->user()->mySponsorId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus1000,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineOne,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus1000,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineTwo,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineThree,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineFour,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineFive,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);

                                    $uplineOneData = DB::table('users')
                                        ->where('mySponsorId', auth()->user()->uplineOne)
                                        ->first();

                                    if ($uplineOneData != null) {
                                        $uplineOnePoint = $uplineOneData->point + $point;
                                        DB::table('users')
                                            ->where('mySponsorId', auth()->user()->uplineOne)
                                            ->update(['point' => $uplineOnePoint]);

                                        DB::table('points')->insert([
                                            'transactionId' => $this->randomDigit(),
                                            'userId' => auth()->user()->uplineOne,
                                            'username' => auth()->user()->uplineOne,
                                            'point' => $point,
                                            'package' => 'None',
                                            'sponsor' => auth()->user()->uplineOne,
                                            'status' => 'CONFIRM',
                                            "created_at" => date('Y-m-d H:i:s'),
                                            "updated_at" => date('Y-m-d H:i:s'),
                                        ]);
                                        // upline two
                                        $uplineTwoData = DB::table('users')
                                            ->where('mySponsorId', auth()->user()->uplineTwo)
                                            ->first();
                                        if ($uplineTwoData != null) {
                                            $uplineTwoPoint = $uplineTwoData->point + $point;
                                            DB::table('users')
                                                ->where('mySponsorId', auth()->user()->uplineTwo)
                                                ->update(['point' => $uplineTwoPoint]);

                                            DB::table('points')->insert([
                                                'transactionId' => $this->randomDigit(),
                                                'userId' => auth()->user()->uplineTwo,
                                                'username' => auth()->user()->uplineTwo,
                                                'point' => $point,
                                                'package' => 'None',
                                                'sponsor' => auth()->user()->uplineTwo,
                                                'status' => 'CONFIRM',
                                                "created_at" => date('Y-m-d H:i:s'),
                                                "updated_at" => date('Y-m-d H:i:s'),
                                            ]);

                                            // upline three
                                            $uplineThreeData = DB::table('users')
                                                ->where('mySponsorId', auth()->user()->uplineThree)
                                                ->first();
                                            if ($uplineThreeData != null) {
                                                $uplineThreePoint =
                                                    $uplineThreeData->point + $point;
                                                DB::table('users')
                                                    ->where(
                                                        'mySponsorId',
                                                        auth()->user()->uplineThree
                                                    )
                                                    ->update(['point' => $uplineThreePoint]);

                                                DB::table('points')->insert([
                                                    'transactionId' => $this->randomDigit(),
                                                    'userId' => auth()->user()->uplineThree,
                                                    'username' => auth()->user()->uplineThree,
                                                    'point' => $point,
                                                    'package' => 'None',
                                                    'sponsor' => auth()->user()->uplineThree,
                                                    'status' => 'CONFIRM',
                                                    "created_at" => date('Y-m-d H:i:s'),
                                                    "updated_at" => date('Y-m-d H:i:s'),
                                                ]);
                                                // upline four
                                                $uplineFourData = DB::table('users')
                                                    ->where(
                                                        'mySponsorId',
                                                        auth()->user()->uplineFour
                                                    )
                                                    ->first();
                                                if ($uplineFourData != null) {
                                                    $uplineFourPoint =
                                                        $uplineFourData->point + $point;
                                                    DB::table('users')
                                                        ->where(
                                                            'mySponsorId',
                                                            auth()->user()->uplineFour
                                                        )
                                                        ->update(['point' => $uplineFourPoint]);

                                                    DB::table('points')->insert([
                                                        'transactionId' => $this->randomDigit(),
                                                        'userId' => auth()->user()->uplineFour,
                                                        'username' => auth()->user()->uplineFour,
                                                        'point' => $point,
                                                        'package' => 'None',
                                                        'sponsor' => auth()->user()->uplineFour,
                                                        'status' => 'CONFIRM',
                                                        "created_at" => date('Y-m-d H:i:s'),
                                                        "updated_at" => date('Y-m-d H:i:s'),
                                                    ]);
                                                    // upline Five
                                                    $uplineFiveData = DB::table('users')
                                                        ->where(
                                                            'mySponsorId',
                                                            auth()->user()->uplineFive
                                                        )
                                                        ->first();
                                                    if ($uplineFiveData != null) {
                                                        $uplineFivePoint =
                                                            $uplineFiveData->point + $point;
                                                        DB::table('users')
                                                            ->where(
                                                                'mySponsorId',
                                                                auth()->user()->uplineFive
                                                            )
                                                            ->update(['point' => $uplineFivePoint]);

                                                        DB::table('points')->insert([
                                                            'transactionId' => $this->randomDigit(),
                                                            'userId' => auth()->user()->uplineFive,
                                                            'username' => auth()->user()
                                                                ->uplineFive,
                                                            'point' => $point,
                                                            'package' => 'None',
                                                            'sponsor' => auth()->user()->uplineFive,
                                                            'status' => 'CONFIRM',
                                                            "created_at" => date('Y-m-d H:i:s'),
                                                            "updated_at" => date('Y-m-d H:i:s'),
                                                        ]);
                                                        return back()->with(
                                                            'toast_success',
                                                            'Transaction Successful'
                                                        );
                                                    } else {
                                                        return back()->with(
                                                            'toast_success',
                                                            'Transaction Successful'
                                                        );
                                                    }
                                                } else {
                                                    return back()->with(
                                                        'toast_success',
                                                        'Transaction Successful'
                                                    );
                                                }
                                            } else {
                                                return back()->with(
                                                    'toast_success',
                                                    'Transaction Successful'
                                                );
                                            }
                                        } else {
                                            return back()->with(
                                                'toast_success',
                                                'Transaction Successful'
                                            );
                                        }
                                    } else {
                                        return back()->with(
                                            'toast_success',
                                            'Transaction Successful'
                                        );
                                    }
                                    // email
                                    // return back()->with('toast_success', 'Transaction Successful');
                                } elseif ($request->package == 'Gold') {
                                    $goldenbonus = 0;
                                    $expectedEarning =
                                        auth()->user()->expectedEarning +
                                        ($datapackage->packageAmount * 500) / 100;
                                    // update
                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->where('package', 'Basic')
                                        ->orWhere('userId', auth()->user()->userId)
                                        ->where('package', 'Bronze')
                                        ->orWhere('userId', auth()->user()->userId)
                                        ->where('package', 'Silver')
                                        ->update(['package' => $datapackage->packageName]);
                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->update(['expectedEarning' => $expectedEarning]);
                                    // create
                                    buypackage::create([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->userId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'phoneNumber' => auth()->user()->userId,
                                        'amount' => $datapackage->packageAmount,
                                        'package' => $datapackage->packageName,
                                        'goldenBonus' => $goldenbonus,
                                        'goldenBonusStatus' => 'Pending',
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    // bonus
                                    $bonus1000 = 5000;
                                    $bonus500 = 2500;
                                    $data = DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->where('sponsor', '!=', 'Admin')
                                        ->first();
                                    $sponsordata = $data->sponsor;
                                    $point = 0.3;
                                    $oldpoint = auth()->user()->point;
                                    $newpoint = $oldpoint + $point;

                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->update(['point' => $newpoint]);

                                    DB::table('points')->insert([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->userId,
                                        'username' => auth()->user()->username,
                                        'point' => $point,
                                        'package' => 'None',
                                        'sponsor' => auth()->user()->username,
                                        'status' => 'CONFIRM',
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                    ]);

                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->mySponsorId,
                                        'sponsorId' => auth()->user()->mySponsorId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus1000,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineOne,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus1000,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineTwo,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineThree,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineFour,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineFive,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);

                                    $uplineOneData = DB::table('users')
                                        ->where('mySponsorId', auth()->user()->uplineOne)
                                        ->first();
                                    if ($uplineOneData != null) {
                                        $uplineOnePoint = $uplineOneData->point + $point;
                                        DB::table('users')
                                            ->where('mySponsorId', auth()->user()->uplineOne)
                                            ->update(['point' => $uplineOnePoint]);

                                        DB::table('points')->insert([
                                            'transactionId' => $this->randomDigit(),
                                            'userId' => auth()->user()->uplineOne,
                                            'username' => auth()->user()->uplineOne,
                                            'point' => $point,
                                            'package' => 'None',
                                            'sponsor' => auth()->user()->uplineOne,
                                            'status' => 'CONFIRM',
                                            "created_at" => date('Y-m-d H:i:s'),
                                            "updated_at" => date('Y-m-d H:i:s'),
                                        ]);
                                        // upline two
                                        $uplineTwoData = DB::table('users')
                                            ->where('mySponsorId', auth()->user()->uplineTwo)
                                            ->first();
                                        if ($uplineTwoData != null) {
                                            $uplineTwoPoint = $uplineTwoData->point + $point;
                                            DB::table('users')
                                                ->where('mySponsorId', auth()->user()->uplineTwo)
                                                ->update(['point' => $uplineTwoPoint]);

                                            DB::table('points')->insert([
                                                'transactionId' => $this->randomDigit(),
                                                'userId' => auth()->user()->uplineTwo,
                                                'username' => auth()->user()->uplineTwo,
                                                'point' => $point,
                                                'package' => 'None',
                                                'sponsor' => auth()->user()->uplineTwo,
                                                'status' => 'CONFIRM',
                                                "created_at" => date('Y-m-d H:i:s'),
                                                "updated_at" => date('Y-m-d H:i:s'),
                                            ]);

                                            // upline three
                                            $uplineThreeData = DB::table('users')
                                                ->where('mySponsorId', auth()->user()->uplineThree)
                                                ->first();
                                            if ($uplineThreeData != null) {
                                                $uplineThreePoint =
                                                    $uplineThreeData->point + $point;
                                                DB::table('users')
                                                    ->where(
                                                        'mySponsorId',
                                                        auth()->user()->uplineThree
                                                    )
                                                    ->update(['point' => $uplineThreePoint]);

                                                DB::table('points')->insert([
                                                    'transactionId' => $this->randomDigit(),
                                                    'userId' => auth()->user()->uplineThree,
                                                    'username' => auth()->user()->uplineThree,
                                                    'point' => $point,
                                                    'package' => 'None',
                                                    'sponsor' => auth()->user()->uplineThree,
                                                    'status' => 'CONFIRM',
                                                    "created_at" => date('Y-m-d H:i:s'),
                                                    "updated_at" => date('Y-m-d H:i:s'),
                                                ]);
                                                // upline four
                                                $uplineFourData = DB::table('users')
                                                    ->where(
                                                        'mySponsorId',
                                                        auth()->user()->uplineFour
                                                    )
                                                    ->first();
                                                if ($uplineFourData != null) {
                                                    $uplineFourPoint =
                                                        $uplineFourData->point + $point;
                                                    DB::table('users')
                                                        ->where(
                                                            'mySponsorId',
                                                            auth()->user()->uplineFour
                                                        )
                                                        ->update(['point' => $uplineFourPoint]);

                                                    DB::table('points')->insert([
                                                        'transactionId' => $this->randomDigit(),
                                                        'userId' => auth()->user()->uplineFour,
                                                        'username' => auth()->user()->uplineFour,
                                                        'point' => $point,
                                                        'package' => 'None',
                                                        'sponsor' => auth()->user()->uplineFour,
                                                        'status' => 'CONFIRM',
                                                        "created_at" => date('Y-m-d H:i:s'),
                                                        "updated_at" => date('Y-m-d H:i:s'),
                                                    ]);
                                                    // upline Five
                                                    $uplineFiveData = DB::table('users')
                                                        ->where(
                                                            'mySponsorId',
                                                            auth()->user()->uplineFive
                                                        )
                                                        ->first();
                                                    if ($uplineFiveData != null) {
                                                        $uplineFivePoint =
                                                            $uplineFiveData->point + $point;
                                                        DB::table('users')
                                                            ->where(
                                                                'mySponsorId',
                                                                auth()->user()->uplineFive
                                                            )
                                                            ->update(['point' => $uplineFivePoint]);

                                                        DB::table('points')->insert([
                                                            'transactionId' => $this->randomDigit(),
                                                            'userId' => auth()->user()->uplineFive,
                                                            'username' => auth()->user()
                                                                ->uplineFive,
                                                            'point' => $point,
                                                            'package' => 'None',
                                                            'sponsor' => auth()->user()->uplineFive,
                                                            'status' => 'CONFIRM',
                                                            "created_at" => date('Y-m-d H:i:s'),
                                                            "updated_at" => date('Y-m-d H:i:s'),
                                                        ]);
                                                        return back()->with(
                                                            'toast_success',
                                                            'Transaction Successful'
                                                        );
                                                    } else {
                                                        return back()->with(
                                                            'toast_success',
                                                            'Transaction Successful'
                                                        );
                                                    }
                                                } else {
                                                    return back()->with(
                                                        'toast_success',
                                                        'Transaction Successful'
                                                    );
                                                }
                                            } else {
                                                return back()->with(
                                                    'toast_success',
                                                    'Transaction Successful'
                                                );
                                            }
                                        } else {
                                            return back()->with(
                                                'toast_success',
                                                'Transaction Successful'
                                            );
                                        }
                                    } else {
                                        return back()->with(
                                            'toast_success',
                                            'Transaction Successful'
                                        );
                                    }
                                    // email
                                    // return back()->with('toast_success', 'Transaction Successful');
                                } elseif ($request->package == 'Platinum') {
                                    $goldenbonus = 0;
                                    $expectedEarning =
                                        auth()->user()->expectedEarning +
                                        ($datapackage->packageAmount * 500) / 100;
                                    // update
                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->where('package', 'Basic')
                                        ->orWhere('userId', auth()->user()->userId)
                                        ->where('package', 'Bronze')
                                        ->orWhere('userId', auth()->user()->userId)
                                        ->where('package', 'Silver')
                                        ->orWhere('userId', auth()->user()->userId)
                                        ->where('package', 'Gold')
                                        ->update(['package' => $datapackage->packageName]);

                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->update(['expectedEarning' => $expectedEarning]);

                                    // create
                                    buypackage::create([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->userId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'phoneNumber' => auth()->user()->userId,
                                        'amount' => $datapackage->packageAmount,
                                        'package' => $datapackage->packageName,
                                        'goldenBonus' => $goldenbonus,
                                        'goldenBonusStatus' => 'Pending',
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    // bonus
                                    $bonus1000 = 10000;
                                    $bonus500 = 5000;
                                    $data = DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->where('sponsor', '!=', 'Admin')
                                        ->first();
                                    $sponsordata = $data->sponsor;
                                    $point = 0.5;
                                    $oldpoint = auth()->user()->point;
                                    $newpoint = $oldpoint + $point;

                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->update(['point' => $newpoint]);

                                    DB::table('points')->insert([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->userId,
                                        'username' => auth()->user()->username,
                                        'point' => $point,
                                        'package' => 'None',
                                        'sponsor' => auth()->user()->username,
                                        'status' => 'CONFIRM',
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                    ]);

                                    // DB::table('users')
                                    //     ->where('userId', auth()->user()->userId)
                                    //     ->where('mySponsorId', $sponsordata)
                                    //     ->update(['point' => $newpoint]);

                                    // DB::table('points')->insert([
                                    //         'transactionId' => $this->randomDigit(),
                                    //         'userId' => $sponsordata,
                                    //         'username' => $sponsordata,
                                    //         'point' => $point,
                                    //         'package' => 'None',
                                    //         'sponsor' => $sponsordata,
                                    //         'status' => 'CONFIRM',
                                    //         "created_at" => date('Y-m-d H:i:s'),
                                    //         "updated_at" => date('Y-m-d H:i:s'),
                                    //     ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->mySponsorId,
                                        'sponsorId' => auth()->user()->mySponsorId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus1000,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineOne,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus1000,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineTwo,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineThree,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineFour,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineFive,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);

                                    $uplineOneData = DB::table('users')
                                        ->where('mySponsorId', auth()->user()->uplineOne)
                                        ->first();
                                    if ($uplineOneData != null) {
                                        $uplineOnePoint = $uplineOneData->point + $point;
                                        DB::table('users')
                                            ->where('mySponsorId', auth()->user()->uplineOne)
                                            ->update(['point' => $uplineOnePoint]);

                                        DB::table('points')->insert([
                                            'transactionId' => $this->randomDigit(),
                                            'userId' => auth()->user()->uplineOne,
                                            'username' => auth()->user()->uplineOne,
                                            'point' => $point,
                                            'package' => 'None',
                                            'sponsor' => auth()->user()->uplineOne,
                                            'status' => 'CONFIRM',
                                            "created_at" => date('Y-m-d H:i:s'),
                                            "updated_at" => date('Y-m-d H:i:s'),
                                        ]);

                                        // upline two
                                        $uplineTwoData = DB::table('users')
                                            ->where('mySponsorId', auth()->user()->uplineTwo)
                                            ->first();
                                        if ($uplineTwoData != null) {
                                            $uplineTwoPoint = $uplineTwoData->point + $point;
                                            DB::table('users')
                                                ->where('mySponsorId', auth()->user()->uplineTwo)
                                                ->update(['point' => $uplineTwoPoint]);

                                            DB::table('points')->insert([
                                                'transactionId' => $this->randomDigit(),
                                                'userId' => auth()->user()->uplineTwo,
                                                'username' => auth()->user()->uplineTwo,
                                                'point' => $point,
                                                'package' => 'None',
                                                'sponsor' => auth()->user()->uplineTwo,
                                                'status' => 'CONFIRM',
                                                "created_at" => date('Y-m-d H:i:s'),
                                                "updated_at" => date('Y-m-d H:i:s'),
                                            ]);
                                            // upline three
                                            $uplineThreeData = DB::table('users')
                                                ->where('mySponsorId', auth()->user()->uplineThree)
                                                ->first();
                                            if ($uplineThreeData != null) {
                                                $uplineThreePoint =
                                                    $uplineThreeData->point + $point;
                                                DB::table('users')
                                                    ->where(
                                                        'mySponsorId',
                                                        auth()->user()->uplineThree
                                                    )
                                                    ->update(['point' => $uplineThreePoint]);

                                                DB::table('points')->insert([
                                                    'transactionId' => $this->randomDigit(),
                                                    'userId' => auth()->user()->uplineThree,
                                                    'username' => auth()->user()->uplineThree,
                                                    'point' => $point,
                                                    'package' => 'None',
                                                    'sponsor' => auth()->user()->uplineThree,
                                                    'status' => 'CONFIRM',
                                                    "created_at" => date('Y-m-d H:i:s'),
                                                    "updated_at" => date('Y-m-d H:i:s'),
                                                ]);

                                                // upline four
                                                $uplineFourData = DB::table('users')
                                                    ->where(
                                                        'mySponsorId',
                                                        auth()->user()->uplineFour
                                                    )
                                                    ->first();
                                                if ($uplineFourData != null) {
                                                    $uplineFourPoint =
                                                        $uplineFourData->point + $point;
                                                    DB::table('users')
                                                        ->where(
                                                            'mySponsorId',
                                                            auth()->user()->uplineFour
                                                        )
                                                        ->update(['point' => $uplineFourPoint]);

                                                    DB::table('points')->insert([
                                                        'transactionId' => $this->randomDigit(),
                                                        'userId' => auth()->user()->uplineFour,
                                                        'username' => auth()->user()->uplineFour,
                                                        'point' => $point,
                                                        'package' => 'None',
                                                        'sponsor' => auth()->user()->uplineFour,
                                                        'status' => 'CONFIRM',
                                                        "created_at" => date('Y-m-d H:i:s'),
                                                        "updated_at" => date('Y-m-d H:i:s'),
                                                    ]);

                                                    // upline Five
                                                    $uplineFiveData = DB::table('users')
                                                        ->where(
                                                            'mySponsorId',
                                                            auth()->user()->uplineFive
                                                        )
                                                        ->first();
                                                    if ($uplineFiveData != null) {
                                                        $uplineFivePoint =
                                                            $uplineFiveData->point + $point;
                                                        DB::table('users')
                                                            ->where(
                                                                'mySponsorId',
                                                                auth()->user()->uplineFive
                                                            )
                                                            ->update(['point' => $uplineFivePoint]);

                                                        DB::table('points')->insert([
                                                            'transactionId' => $this->randomDigit(),
                                                            'userId' => auth()->user()->uplineFive,
                                                            'username' => auth()->user()
                                                                ->uplineFive,
                                                            'point' => $point,
                                                            'package' => 'None',
                                                            'sponsor' => auth()->user()->uplineFive,
                                                            'status' => 'CONFIRM',
                                                            "created_at" => date('Y-m-d H:i:s'),
                                                            "updated_at" => date('Y-m-d H:i:s'),
                                                        ]);
                                                        return back()->with(
                                                            'toast_success',
                                                            'Transaction Successful'
                                                        );
                                                    } else {
                                                        return back()->with(
                                                            'toast_success',
                                                            'Transaction Successful'
                                                        );
                                                    }
                                                } else {
                                                    return back()->with(
                                                        'toast_success',
                                                        'Transaction Successful'
                                                    );
                                                }
                                            } else {
                                                return back()->with(
                                                    'toast_success',
                                                    'Transaction Successful'
                                                );
                                            }
                                        } else {
                                            return back()->with(
                                                'toast_success',
                                                'Transaction Successful'
                                            );
                                        }
                                    } else {
                                        return back()->with(
                                            'toast_success',
                                            'Transaction Successful'
                                        );
                                    }
                                    // email
                                    // return back()->with('toast_success', 'Transaction Successful');
                                } else {
                                    return back()->with('toast_error', 'Invalid Transaction');
                                }
                            } else {
                                if ($request->package == 'Bronze') {
                                    $goldenbonus = 0;
                                    $point = 0.1;
                                    $oldpoint = auth()->user()->point;
                                    $newpoint = $oldpoint + $point;
                                    // update
                                    $expectedEarning =
                                        auth()->user()->expectedEarning +
                                        ($datapackage->packageAmount * 500) / 100;
                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->where('package', 'Basic')
                                        ->update(['package' => $datapackage->packageName]);

                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->update([
                                            'point' => $newpoint,
                                            'expectedEarning' => $expectedEarning,
                                        ]);

                                    DB::table('points')->insert([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->userId,
                                        'username' => auth()->user()->username,
                                        'point' => $point,
                                        'package' => 'None',
                                        'sponsor' => auth()->user()->username,
                                        'status' => 'CONFIRM',
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                    ]);
                                    // create
                                    buypackage::create([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->userId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'phoneNumber' => auth()->user()->userId,
                                        'amount' => $datapackage->packageAmount,
                                        'package' => $datapackage->packageName,
                                        'goldenBonus' => $goldenbonus,
                                        'goldenBonusStatus' => 'Pending',
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);

                                    // bonus
                                    $bonus1000 = 1000;
                                    $bonus500 = 500;

                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->mySponsorId,
                                        'sponsorId' => auth()->user()->mySponsorId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus1000,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);

                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineOne,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus1000,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineTwo,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);

                                    // email
                                    return back()->with('toast_success', 'Transaction Successful');
                                } elseif ($request->package == 'Silver') {
                                    $goldenbonus = 0;
                                    $expectedEarning =
                                        auth()->user()->expectedEarning +
                                        ($datapackage->packageAmount * 500) / 100;
                                    // update
                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->where('package', 'Basic')
                                        ->orWhere('userId', auth()->user()->userId)
                                        ->where('package', 'Bronze')
                                        ->update(['package' => $datapackage->packageName]);

                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->update(['expectedEarning' => $expectedEarning]);
                                    // create
                                    buypackage::create([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->userId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'phoneNumber' => auth()->user()->userId,
                                        'amount' => $datapackage->packageAmount,
                                        'package' => $datapackage->packageName,
                                        'goldenBonus' => $goldenbonus,
                                        'goldenBonusStatus' => 'Pending',
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    // bonus
                                    $bonus1000 = 2500;
                                    $bonus500 = 1250;
                                    $data = DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->where('sponsor', '!=', 'Admin')
                                        ->first();
                                    $point = 0.2;
                                    $oldpoint = auth()->user()->point;
                                    $newpoint = $oldpoint + $point;

                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->update(['point' => $newpoint]);

                                    DB::table('points')->insert([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->userId,
                                        'username' => auth()->user()->username,
                                        'point' => $point,
                                        'package' => 'None',
                                        'sponsor' => auth()->user()->username,
                                        'status' => 'CONFIRM',
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                    ]);

                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->mySponsorId,
                                        'sponsorId' => auth()->user()->mySponsorId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus1000,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineOne,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus1000,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineTwo,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineThree,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);

                                    // email
                                    return back()->with('toast_success', 'Transaction Successful');
                                } elseif ($request->package == 'Gold') {
                                    $goldenbonus = 0;
                                    $expectedEarning =
                                        auth()->user()->expectedEarning +
                                        ($datapackage->packageAmount * 500) / 100;
                                    // update
                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->where('package', 'Basic')
                                        ->orWhere('userId', auth()->user()->userId)
                                        ->where('package', 'Bronze')
                                        ->orWhere('userId', auth()->user()->userId)
                                        ->where('package', 'Silver')
                                        ->update(['package' => $datapackage->packageName]);
                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->update(['expectedEarning' => $expectedEarning]);
                                    // create
                                    buypackage::create([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->userId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'phoneNumber' => auth()->user()->userId,
                                        'amount' => $datapackage->packageAmount,
                                        'package' => $datapackage->packageName,
                                        'goldenBonus' => $goldenbonus,
                                        'goldenBonusStatus' => 'Pending',
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    // bonus
                                    $bonus1000 = 5000;
                                    $bonus500 = 2500;
                                    $data = DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->where('sponsor', '!=', 'Admin')
                                        ->first();
                                    $point = 0.3;
                                    $oldpoint = auth()->user()->point;
                                    $newpoint = $oldpoint + $point;

                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->update(['point' => $newpoint]);

                                    DB::table('points')->insert([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->userId,
                                        'username' => auth()->user()->username,
                                        'point' => $point,
                                        'package' => 'None',
                                        'sponsor' => auth()->user()->username,
                                        'status' => 'CONFIRM',
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                    ]);

                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->mySponsorId,
                                        'sponsorId' => auth()->user()->mySponsorId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus1000,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineOne,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus1000,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineTwo,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineThree,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineFour,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);

                                    // email
                                    return back()->with('toast_success', 'Transaction Successful');
                                } elseif ($request->package == 'Platinum') {
                                    $goldenbonus = 0;
                                    $expectedEarning =
                                        auth()->user()->expectedEarning +
                                        ($datapackage->packageAmount * 500) / 100;
                                    // update
                                    $point = 0.5;
                                    $oldpoint = auth()->user()->point;
                                    $newpoint = $oldpoint + $point;
                                    // update
                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->where('package', 'Basic')
                                        ->orWhere('userId', auth()->user()->userId)
                                        ->where('package', 'Bronze')
                                        ->orWhere('userId', auth()->user()->userId)
                                        ->where('package', 'Silver')
                                        ->orWhere('userId', auth()->user()->userId)
                                        ->where('package', 'Gold')
                                        ->update(['package' => $datapackage->packageName]);

                                    DB::table('users')
                                        ->where('userId', auth()->user()->userId)
                                        ->update([
                                            'point' => $newpoint,
                                            'expectedEarning' => $expectedEarning,
                                        ]);

                                    DB::table('points')->insert([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->userId,
                                        'username' => auth()->user()->username,
                                        'point' => $point,
                                        'package' => 'None',
                                        'sponsor' => auth()->user()->username,
                                        'status' => 'CONFIRM',
                                        "created_at" => date('Y-m-d H:i:s'),
                                        "updated_at" => date('Y-m-d H:i:s'),
                                    ]);

                                    // create
                                    buypackage::create([
                                        'transactionId' => $this->randomDigit(),
                                        'userId' => auth()->user()->userId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'phoneNumber' => auth()->user()->userId,
                                        'amount' => $datapackage->packageAmount,
                                        'package' => $datapackage->packageName,
                                        'goldenBonus' => $goldenbonus,
                                        'goldenBonusStatus' => 'Pending',
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    // bonus
                                    $bonus1000 = 10000;
                                    $bonus500 = 5000;
                                    // $data = DB::table('users')
                                    //     ->where('userId', auth()->user()->userId)
                                    //     ->where('sponsor', '!=', 'Admin')
                                    //     ->first();
                                    // $sponsordata = $data->sponsor;
                                    // $bronzepoint = 0.3;
                                    // $oldpoint = $data->point;
                                    // $newpoint = $oldpoint + $bronzepoint;

                                    // DB::table('users')
                                    //     ->where('userId', auth()->user()->userId)
                                    //     ->update(['point' => $newpoint]);
                                    // DB::table('users')
                                    //     ->where('userId', auth()->user()->userId)
                                    //     ->where('mySponsorId', $sponsordata)
                                    //     ->update(['point' => $newpoint]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->mySponsorId,
                                        'sponsorId' => auth()->user()->mySponsorId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus1000,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineOne,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus1000,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineTwo,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineThree,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineFour,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineFive,
                                        'sponsorId' => auth()->user()->sponsor,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bonus500,
                                        'package' => $request->package,
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);

                                    // email
                                    return back()->with('toast_success', 'Transaction Successful');
                                } else {
                                    return back()->with('toast_error', 'Invalid Transaction');
                                }
                            }
                        }
                    } else {
                        return back()->with(
                            'toast_error',
                            'Insufficient amount to complete this transaction'
                        );
                    }
                }
            }
        } else {
            return back()->with('toast_error', 'Migration only works for wallet payment');

        }
    }
}
