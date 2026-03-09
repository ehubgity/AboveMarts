<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\withdraw as ModelsWithdraw;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\bonus;

class transfer extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('user.transfer');
    }
    public function randomDigit()
    {
        $pass = substr(str_shuffle("0123456789abcDEFGHIJnostXYZ"), 0, 15);
        return $pass;
    }

    public function store(Request $request)
    {
        // check if money is on the bonus wallet
        $databonus = DB::table('bonuses')
            ->where('sponsor', auth()->user()->mySponsorId)
            ->where('amount', '>', 0)
            ->sum('amount');
        $withbon = DB::table('withdraws')
            ->where('userId', auth()->user()->userId)
            ->where('status', 'CONFIRM')
            ->where('paymentType', 'Transfer')
            ->sum('amount');

        $newbon = $databonus - $withbon;
        $serviceFee = ($request->amountinitial * 3) / 100;
        $adminFee = ($request->amountinitial * 2) / 100;
        $commissionFee = ($request->amountinitial * 1) / 100;
        // $newamount = $request->amountinitial - $serviceFee;

        if (auth()->user()->package == 'Basic') {
            return back()->with(
                'toast_error',
                "Oops!! Transfers are for only paid partners. Kindly Upgrade"
            );
        } else {
            if ($withbon + $request->amount > auth()->user()->expectedEarning) {
                return back()->with(
                    'toast_error',
                    "Oops!! You can't transfer more than your expected earning. Kindly Upgrade"
                );
            } else {
                if ($newbon >= $request->amount) {
                    if ($request->amountinitial >= 100) {
                        // transfer to fund wallet
                        DB::table('funds')->insert([
                            'transactionId' => $this->randomDigit(),
                            'userId' => auth()->user()->userId,
                            'name' => auth()->user()->username,
                            'email' => auth()->user()->email,
                            'amount' => $request->amountinitial,
                            'paymentType' => 'Transfer',
                            'accountName' => 'Transfer',
                            'accountNumber' => 'Transfer',
                            'bankName' => 'Transfer',
                            'status' => 'success',
                            'Admin' => 'None',
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        ]);
                        DB::table('transactions')->insert([
                            'transactionId' => $this->randomDigit(),
                            'userId' => auth()->user()->userId,
                            'username' => auth()->user()->username,
                            'email' => auth()->user()->email,
                            'phoneNumber' => auth()->user()->phoneNumber,
                            'amount' => $request->amount,
                            'transactionType' => 'Deposit',
                            'transactionService' => 'Transfer Commission',
                            'status' => 'CONFIRM',
                            'paymentMethod' => 'wallet',
                            'Admin' => 'None',
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        ]);
                        DB::table('transactions')->insert([
                            'transactionId' => $this->randomDigit(),
                            'userId' => auth()->user()->userId,
                            'username' => auth()->user()->username,
                            'email' => auth()->user()->email,
                            'phoneNumber' => auth()->user()->phoneNumber,
                            'amount' => $serviceFee,
                            'transactionType' => 'Charges',
                            'transactionService' => 'Transfer Charges',
                            'status' => 'CONFIRM',
                            'paymentMethod' => 'wallet',
                            'Admin' => 'None',
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        ]);
                        ModelsWithdraw::create([
                            'transactionId' => $this->randomDigit(),
                            'userId' => auth()->user()->userId,
                            'username' => auth()->user()->username,
                            'phoneNumber' => auth()->user()->phoneNumber,
                            'email' => auth()->user()->email,
                            'amount' => $request->amountinitial,
                            'paymentType' => 'Transfer',
                            'status' => 'CONFIRM',
                            'accountName' => 'Transfer',
                            'bankAddress' => 'Transfer',
                            'accountNumber' => 'Transfer',
                            'bankName' => 'Transfer',
                        ]);

                        bonus::create([
                            'bonusId' => $this->randomDigit(),
                            'sponsor' => auth()->user()->uplineOne,
                            'sponsorId' => auth()->user()->sponsor,
                            'username' => auth()->user()->username,
                            'email' => auth()->user()->email,
                            'amount' => $commissionFee,
                            'package' => 'Commission',
                            'status' => 'Confirm',
                            'dayCounter' => 0,
                        ]);

                        bonus::create([
                            'bonusId' => $this->randomDigit(),
                            'sponsor' => 'Admin',
                            'sponsorId' => auth()->user()->sponsor,
                            'username' => auth()->user()->username,
                            'email' => auth()->user()->email,
                            'amount' => $adminFee,
                            'package' => 'Commission',
                            'status' => 'Confirm',
                            'dayCounter' => 0,
                        ]);

                        return back()->with('toast_success', 'Transaction Successful !!');
                    } else {
                        return back()->with('toast_error', 'Insufficient fund');
                    }
                } else {
                    return back()->with('toast_error', 'Insufficient fund');
                }
            }
        }
    }
}
