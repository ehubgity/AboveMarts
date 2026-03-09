<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class preordercard extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function randomDigit()
    {
        $pass = substr(str_shuffle("01234561089abcDEfadsasfdasdfasdsfa3425542FGHIJnostXYZ"), 0, 30);
        return $pass;
    }

    public function randomDigit16()
    {
        $pass = substr(str_shuffle("01234561089abcDEfadsasfdasdfasdsfa3425542FGHIJnostXYZ"), 0, 16);
        return $pass;
    }

    public function index()
    {
        return view('user.preordercard');
    }

    public function store(Request $request)
    {
        $expenses = DB::table('transactions')
            ->where('userId', auth()->user()->userId)
            ->where('status', 'CONFIRM')
            ->where('transactionType', '!=', 'Deposit')
            ->sum('amount');
        $capital = DB::table('funds')
            ->where('userId', auth()->user()->userId)
            ->where('status', 'success')
            ->sum('amount');

        $balance = $capital - $expenses;

        $card100 = 98;
        $card200 = 196;
        $card500 = 490;
        if ($request->quantity < 100) {
            return back()->with('toast_error', 'Invalid. Can only preorder 100 at a time');
        } else {
            if ($request->amount == 100) {
                $realamount = $card100 * $request->quantity;
                if ($balance < $realamount) {
                    return back()->with('toast_error', 'Insufficient Funds');
                } else {
                    if ($request->network == 'None') {
                        return back()->with('toast_error', 'Select Network');
                    } else {
                        $cardId = $this->randomDigit16();
                        DB::table('transactions')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => auth()->user()->phoneNumber,
                                'amount' => $realamount,
                                'paymentMethod' => 'wallet',
                                'Admin' => 'None',

                                'transactionType' => 'Recharge Printing',
                                'transactionService' => $request->network,
                                'status' => 'CONFIRM',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);

                        DB::table('rechargeprintings')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => auth()->user()->phoneNumber,
                                'amount' => $request->amount,
                                'network' => $request->network,
                                'networkPlan' => $request->network,
                                'businessName' => $request->businessName,
                                'photo' => '',
                                'quantity' => $request->quantity,
                                'status' => 'CONFIRM',
                                'cost' => $realamount,
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);

                        DB::table('preordercards')->insert([
                            'transactionId' => $this->randomDigit(),
                            'userId' => auth()->user()->userId,
                            'amount' => $request->amount,
                            'network' => $request->network,
                            'quantity' => $request->quantity,
                            'cardId' => $cardId,
                            'email' => auth()->user()->email,
                            'username' => auth()->user()->username,
                            'status' => "PENDING",
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        ]);

                        DB::table('usedcards')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'amount' => $request->amount,
                                'network' => $request->network,
                                'businessName' => $request->businessName,
                                'pin' => '',
                                'serialNumber' => '',
                                'cardId' => $cardId,
                                'quantity' => $request->quantity,
                                'status' => 'PREORDER',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);

                        return back()->with(
                            'toast_success',
                            'Pre-order Created Successfully. You will be reach out via email address'
                        );
                    }
                }
            } elseif ($request->amount == 200) {
                $realamount = $card200 * $request->quantity;
                if ($balance < $realamount) {
                    return back()->with('toast_error', 'Insufficient Funds');
                } else {
                    if ($request->network == 'None') {
                        return back()->with('toast_error', 'Select Network');
                    } else {
                        $cardId = $this->randomDigit16();
                        DB::table('transactions')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => auth()->user()->phoneNumber,
                                'amount' => $realamount,
                                'paymentMethod' => 'wallet',
                                'Admin' => 'None',

                                'transactionType' => 'Recharge Printing',
                                'transactionService' => $request->network,
                                'status' => 'CONFIRM',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);

                        DB::table('rechargeprintings')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => auth()->user()->phoneNumber,
                                'amount' => $request->amount,
                                'network' => $request->network,
                                'networkPlan' => $request->network,
                                'businessName' => $request->businessName,
                                'photo' => '',
                                'quantity' => $request->quantity,
                                'status' => 'CONFIRM',
                                'cost' => $realamount,
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);

                        DB::table('preordercards')->insert([
                            'transactionId' => $this->randomDigit(),
                            'userId' => auth()->user()->userId,
                            'amount' => $request->amount,
                            'network' => $request->network,
                            'cardId' => $cardId,
                            'quantity' => $request->quantity,
                            'email' => auth()->user()->email,
                            'username' => auth()->user()->username,
                            'status' => "PENDING",
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        ]);

                        DB::table('usedcards')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'amount' => $request->amount,
                                'network' => $request->network,
                                'businessName' => $request->businessName,
                                'pin' => '',
                                'serialNumber' => '',
                                'cardId' => $cardId,
                                'quantity' => $request->quantity,
                                'status' => 'PREORDER',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);

                        return back()->with(
                            'toast_success',
                            'Pre-order Created Successfully. You will be reach out via email address'
                        );
                    }
                }
            } elseif ($request->amount == 500) {
                $realamount = $card500 * $request->quantity;
                if ($balance < $realamount) {
                    return back()->with('toast_error', 'Insufficient Funds');
                } else {
                    if ($request->network == 'None') {
                        return back()->with('toast_error', 'Select Network');
                    } else {
                        $cardId = $this->randomDigit16();
                        DB::table('transactions')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => auth()->user()->phoneNumber,
                                'amount' => $realamount,
                                'paymentMethod' => 'wallet',
                                'Admin' => 'None',

                                'transactionType' => 'Recharge Printing',
                                'transactionService' => $request->network,
                                'status' => 'CONFIRM',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);

                        DB::table('rechargeprintings')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => auth()->user()->phoneNumber,
                                'amount' => $request->amount,
                                'network' => $request->network,
                                'networkPlan' => $request->network,
                                'businessName' => $request->businessName,
                                'photo' => '',
                                'quantity' => $request->quantity,
                                'status' => 'CONFIRM',
                                'cost' => $realamount,
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);

                        DB::table('preordercards')->insert([
                            'transactionId' => $this->randomDigit(),
                            'userId' => auth()->user()->userId,
                            'amount' => $request->amount,
                            'network' => $request->network,
                            'cardId' => $cardId,
                            'quantity' => $request->quantity,
                            'email' => auth()->user()->email,
                            'username' => auth()->user()->username,
                            'status' => "PENDING",
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        ]);

                        DB::table('usedcards')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'amount' => $request->amount,
                                'network' => $request->network,
                                'businessName' => $request->businessName,
                                'pin' => '',
                                'serialNumber' => '',
                                'cardId' => $cardId,
                                'quantity' => $request->quantity,
                                'status' => 'PREORDER',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);

                        return back()->with(
                            'toast_success',
                            'Pre-order Created Successfully. You will be reach out via email address'
                        );
                    }
                }
            } else {
                return back()->with('toast_error', 'Invalid. Contact Admin');
            }
        }
    }
}
