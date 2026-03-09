<?php

namespace App\Http\Controllers;

use App\Models\withdraw as ModelsWithdraw;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class internaltransfer extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('user.internaltransfer');
    }
    public function randomDigit()
    {
        $pass = substr(str_shuffle("0123456789abcDEFGHIJnostXYZ"), 0, 15);
        return $pass;
    }

    public function store(Request $request)
    {
        // check if money is on the bonus wallet
        $user = DB::table('users')
            ->where('username', $request->username)
            ->first();

        $expenses = DB::table('transactions')
            ->where('userId', auth()->user()->userId)
            ->where('status', 'CONFIRM')
            ->where('transactionType', '!=', 'Deposit')
            ->sum('amount');

        $capital = DB::table('funds')
            ->where('userId', auth()->user()->userId)
            ->where('status', 'success')
            ->sum('amount');

        $serviceFee = $request->fee;

        $balance = $capital - $expenses;

        if ($user == null) {
            return back()->with('toast_error', "Oops!! User not found");
        } else {
            if ($user->username == auth()->user()->username) {
                return back()->with('toast_error', "Oops!! Can't transfer to yourself");
            } else {
                if (auth()->user()->username == "Abovemartsadmin") {
                    if ($balance < $request->amount || $request->amount == 0) {
                        return back()->with('toast_error', 'Insufficient fund');
                    } else {
                        DB::table('funds')->insert([
                            'transactionId' => $this->randomDigit(),
                            'userId' => $user->userId,
                            'name' => $user->username,
                            'email' => $user->email,
                            'amount' => $request->amount,
                            'paymentType' => 'Shared',
                            'accountName' => 'Shared',
                            'accountNumber' => 'Shared',
                            'bankName' => 'Shared',
                            'status' => 'success',
                            'Admin' => 'None',
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        ]);
                        DB::table('transactions')->insert([
                            'transactionId' => $this->randomDigit(),
                            'userId' => $user->userId,
                            'username' => $user->username,
                            'email' => $user->email,
                            'phoneNumber' => auth()->user()->username,
                            'amount' => $request->amount,
                            'transactionType' => 'Deposit',
                            'transactionService' => 'External Transfer',
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
                            'phoneNumber' => $user->username,
                            'amount' => $request->amount,
                            'transactionType' => 'External Transfer',
                            'transactionService' => 'External Transfer',
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
                            'amount' => $request->amount,
                            'paymentType' => 'Shared',
                            'status' => 'CONFIRM',
                            'accountName' => 'Transfer',
                            'bankAddress' => 'Transfer',
                            'accountNumber' => 'Transfer',
                            'bankName' => 'Transfer',
                        ]);
                        return back()->with('toast_success', 'Transaction Successful !!');
                    }
                } else {
                    if (auth()->user()->package == "Basic") {
                        return back()->with('toast_error', "Oops!! You are not a Paid Partner");
                    } else {
                        if ($balance < $request->amount || $request->amount == 0) {
                            return back()->with('toast_error', 'Insufficient fund');
                        } else {
                            DB::table('funds')->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => $user->userId,
                                'name' => $user->username,
                                'email' => $user->email,
                                'amount' => $request->amount,
                                'paymentType' => 'Shared',
                                'accountName' => 'Shared',
                                'accountNumber' => 'Shared',
                                'bankName' => 'Shared',
                                'status' => 'success',
                                'Admin' => 'None',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);
                            DB::table('transactions')->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => $user->userId,
                                'username' => $user->username,
                                'email' => $user->email,
                                'phoneNumber' => auth()->user()->username,
                                'amount' => $request->amount,
                                'transactionType' => 'Deposit',
                                'transactionService' => 'External Transfer',
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
                                'phoneNumber' => $user->username,
                                'amount' => $request->amount,
                                'transactionType' => 'External Transfer',
                                'transactionService' => 'External Transfer',
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
                                'amount' => $request->amount,
                                'paymentType' => 'Shared',
                                'status' => 'CONFIRM',
                                'accountName' => 'Transfer',
                                'bankAddress' => 'Transfer',
                                'accountNumber' => 'Transfer',
                                'bankName' => 'Transfer',
                            ]);
                            return back()->with('toast_success', 'Transaction Successful !!');
                        }
                    }
                }
            }
        }
    }
}
