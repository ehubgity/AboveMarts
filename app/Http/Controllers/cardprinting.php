<?php

namespace App\Http\Controllers;

use App\Helpers\TransactionHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;

class cardprinting extends Controller
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

    public function index(Request $request)
    {
        $id = $request->id;
        $data = DB::table('rechargeprintings')
            ->where('transactionId', $id)
            ->first();

        $datausedcard = DB::table('usedcards')
            ->orderByDesc('id')
            ->first();

        if ($data->photo == 'Sample') {
            if ($data->quantity > 15) {
                return redirect()
                    ->route('rechargeprinting')
                    ->with('toast_error', 'Oops!! You can only print 15 at a time');
            } else {
                $cards = DB::table('samplecards')
                    ->where('status', 'CONFIRM')
                    ->where('amount', $data->amount)
                    ->where('network', $data->network)
                    ->take($data->quantity)
                    ->get();

                if ($data == null) {
                    return redirect()
                        ->route('rechargeprinting')
                        ->with('toast_error', 'Oops!! Invalid Recharge Card Id');
                } else {
                    DB::table('rechargeprintings')
                        ->where('transactionId', $data->transactionId)
                        ->update([
                            'status' => 'CONFIRM',
                        ]);
                    return view('user.cardprinting')
                        ->with('data', $data)
                        ->with('cards', $cards);
                }
            }
        } else {
            $cards = DB::table('cards')
                ->where('status', 'CONFIRM')
                ->where('userId', auth()->user()->userId)
                ->where('amount', $data->amount)
                ->where('network', $data->network)
                ->first();
            if ($cards != null) {
                if ($data->quantity > 15) {
                    return redirect()
                        ->route('rechargeprinting')
                        ->with('toast_error', 'Oops!! You can only print 15 at a time');
                } else {
                    $cards = DB::table('cards')
                        ->where('status', 'CONFIRM')
                        ->where('userId', auth()->user()->userId)
                        ->where('amount', $data->amount)
                        ->where('network', $data->network)
                        ->take($data->quantity)
                        ->get();

                    if ($data == null) {
                        return redirect()
                            ->route('rechargeprinting')
                            ->with('toast_error', 'Oops!! Invalid Recharge Card Id');
                    } else {
                        DB::table('rechargeprintings')
                            ->where('transactionId', $data->transactionId)
                            ->update([
                                'status' => 'CONFIRM',
                            ]);

                        DB::table('transactions')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => auth()->user()->phoneNumber,
                                'amount' => $data->cost,
                                'paymentMethod' => 'wallet',
                                'transactionType' => 'Recharge Printing',
                                'transactionService' => $data->network,
                                'status' => 'CONFIRM',
                                'Admin' => 'None',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);
                        // TransactionHelper::updateAccountManagerTotals(auth()->user()->userId, $request->amount, 'Recharge Printing');


                        DB::table('usedcards')
                            ->where('transactionId', $datausedcard->transactionId)
                            ->update([
                                'status' => 'CONFIRM',
                            ]);
                        DB::table('cards')
                            ->where('status', 'CONFIRM')
                            ->where('userId', auth()->user()->userId)
                            ->where('amount', $data->amount)
                            ->where('network', $data->network)
                            ->take($data->quantity)
                            ->update(['status' => 'USED']);

                        return view('user.cardprinting')
                            ->with('data', $data)
                            ->with('cards', $cards);
                    }
                }
            } else {
                return redirect()
                    ->route('rechargeprinting')
                    ->with('toast_error', 'Oops!! No Sufficient Recharge Card. Contact Admin');
            }
        }
    }
}
