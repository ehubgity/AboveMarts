<?php

namespace App\Http\Controllers;

use App\Helpers\TransactionHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\recharge;
use App\Models\bonus;
use App\Models\User;

use App\Services\EBillsService;
use Illuminate\Http\JsonResponse;

class rechargepurchase extends Controller
{
    //
    private $eBillsService;

    public function __construct(EBillsService $eBillsService)
    {
        $this->middleware('auth');
        $this->eBillsService = $eBillsService;
    }

    public function index()
    {
        return view('user.rechargepurchase');
    }
    public function randomDigit()
    {
        $pass = substr(str_shuffle("01234561089abcDEfadsasfdasdfasdsfa3425542FGHIJnostXYZ"), 0, 30);
        return $pass;
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phoneNumber' => 'required|numeric|digits:11',
            'package' => 'required',
            'amount' => 'required|numeric|min:50',
            'payment' => 'required|in:wallet,epin,promo',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('toast_error', $validator->messages()->all()[0])
                ->withInput();
        }

        $expenses = DB::table('transactions')
            ->where('userId', auth()->user()->userId)
            ->where('status', 'CONFIRM')
            ->where('transactionType', '!=', 'Deposit')
            ->sum('amount');
        $capital = DB::table('funds')
            ->where('userId', auth()->user()->userId)
            ->where('status', 'success')
            ->sum('amount');
        $bonusamount = DB::table('bonuses')
            ->where('sponsor', auth()->user()->mySponsorId)
            ->sum('amount');
        // $bonus = DB::table('transactions')
        //     ->where('userId', auth()->user()->userId)
        //     ->where('transactionService', 'Commission')
        //     ->sum('amount');
        $balance = $capital + 0 - $expenses;
        $commissionamount = $request->amount * (2 / 100);
        if ($balance < $request->amount) {
            return back()->with('toast_error', 'Insufficient Funds');
        } else {
            if ($request->package == "none") {
                return back()->with('toast_error', "Enter a network");
            } else {
                if ($request->amount < 50) {
                    return back()->with('toast_error', "Can't recharge below #50");
                } else {
                    $rcpurchasesTransactionId = $this->randomDigit();
                    $transactionId = $this->randomDigit();

                    DB::table('rcpurchases')
                        ->where('userId', auth()->user()->userId)
                        ->insert([
                            'transactionId' => $rcpurchasesTransactionId,
                            'userId' => auth()->user()->userId,
                            'username' => auth()->user()->username,
                            'email' => auth()->user()->email,
                            'phoneNumber' => $request->phoneNumber,
                            'amount' => $request->amount,
                            'network' => $request->package,
                            'status' => 'CONFIRM',
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        ]);
                    DB::table('transactions')->insert([
                        'transactionId' => $transactionId,
                        'userId' => auth()->user()->userId,
                        'username' => auth()->user()->username,
                        'email' => auth()->user()->email,
                        'phoneNumber' => $request->phoneNumber,
                        'amount' => $request->amount,
                        'transactionType' => 'Recharge Card Purchase',
                        'transactionService' => $request->package,
                        'status' => 'CONFIRM',
                        'paymentMethod' => 'wallet',
                        'Admin' => 'None',

                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s'),
                    ]);
                    // TransactionHelper::updateAccountManagerTotals(auth()->user()->userId, $request->amount, 'Recharge Card Purchase');

                    //  Update User Balance
                    $user = User::findOrFail(auth()->id());

                    $user->update([
                        'beforeBalance' => $balance,
                        'currentBalance' => $balance - $request->amount,
                    ]);

                    // $api = getenv('TELECOM_API');
                    // $phoneNumber = $request->phoneNumber;
                    // $productCode = $request->package;
                    // $amount = $request->amount;
                    // $ch = curl_init();
                    // curl_setopt(
                    //     $ch,
                    //     CURLOPT_URL,
                    //     "https://mobileone.ng/api/v2/airtime/?api_key={$api}&product_code={$productCode}&phone={$phoneNumber}&amount={$amount}&callback=mobileone.ng/callback.php"
                    // );
                    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    // curl_setopt($ch, CURLOPT_POST, true);
                    // curl_setopt($ch, CURLOPT_POST, true);
                    // $result = curl_exec($ch);
                    // $response = json_decode($result);

                    try {
                        $response = $this->eBillsService->purchaseAirtime(
                            $request->phoneNumber,
                            $request->package,
                            $request->amount,
                            $rcpurchasesTransactionId
                        );
                    } catch (\Exception $e) {
                        return back()->with(
                            'toast_error',
                            'Failed Transaction'
                        );
                        //  return back()->with(
                        //                 'toast_error',
                        //                 $e->getMessage()
                        //             );
                        // return response()->json([
                        //     'success' => false,
                        //     'message' => 'Failed to purchase airtime',
                        //     'error' => $e->getMessage()
                        // ], 400);
                    }

                    if ($response == null) {
                        return back()->with(
                            'toast_error',
                            'Oops!!, Service Temporarily Unavailable'
                        );
                    } else {
                        DB::table('transactions')->where('transactionId', $transactionId)->update([
                            'apiStatus' => $response['code'],
                        ]);
                        if ($response['code'] == "success") {
                            //   
                            // if(auth()->user()->package == 'Basic'){

                            // }else{

                            // }
                            if ($request->payment == 'wallet') {
                                // 
                                if (auth()->user()->package == 'Basic') {
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->uplineOne,
                                        'sponsorId' => auth()->user()->mySponsorId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $commissionamount,
                                        'package' => 'Recharge Card Purchase',
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    // email......
                                    $userData = DB::table('users')
                                        ->where('userId', auth()->user()->username)
                                        ->first();

                                    $details = [
                                        'name' =>
                                        auth()->user()->firstName .
                                            ' ' .
                                            auth()->user()->lastName,
                                        'amount' => $request->amount,
                                        'network' => $request->package,
                                        'date' => date('Y-m-d H:i:s'),
                                    ];

                                    Mail::to(auth()->user()->email)->send(new recharge($details));

                                    return back()->with(
                                        'toast_success',
                                        'Transaction Successful !!'
                                    );
                                } else {
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->mySponsorId,
                                        'sponsorId' => auth()->user()->mySponsorId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $commissionamount,
                                        'package' => 'Recharge Card Purchase',
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    // email......
                                    $userData = DB::table('users')
                                        ->where('userId', auth()->user()->username)
                                        ->first();

                                    $details = [
                                        'name' =>
                                        auth()->user()->firstName .
                                            ' ' .
                                            auth()->user()->lastName,
                                        'amount' => $request->amount,
                                        'network' => $request->package,
                                        'date' => date('Y-m-d H:i:s'),
                                    ];
                                    Mail::to(auth()->user()->email)->send(new recharge($details));
                                    return back()->with(
                                        'toast_success',
                                        'Transaction Successful !!'
                                    );
                                }
                            } elseif ($request->payment == 'epin') {
                                DB::table('transactions')->where('transactionId', $transactionId)->update([
                                    'paymentMethod' => 'epin',

                                ]);
                                // email......
                                $userData = DB::table('users')
                                    ->where('userId', auth()->user()->username)
                                    ->first();

                                $details = [
                                    'name' =>
                                    auth()->user()->firstName . ' ' . auth()->user()->lastName,
                                    'amount' => $request->amount,
                                    'network' => $request->package,
                                    'date' => date('Y-m-d H:i:s'),
                                ];
                                Mail::to(auth()->user()->email)->send(new recharge($details));
                                return back()->with('toast_success', 'Transaction Successful !!');
                            } elseif ($request->payment == 'promo') {
                                DB::table('transactions')->where('transactionId', $transactionId)->update([
                                    'paymentMethod' => 'promo',

                                ]);
                                // email......
                                $userData = DB::table('users')
                                    ->where('userId', auth()->user()->username)
                                    ->first();

                                $details = [
                                    'name' =>
                                    auth()->user()->firstName . ' ' . auth()->user()->lastName,
                                    'amount' => $request->amount,
                                    'network' => $request->package,
                                    'date' => date('Y-m-d H:i:s'),
                                ];
                                Mail::to(auth()->user()->email)->send(new recharge($details));
                                return back()->with('toast_success', 'Transaction Successful !!');
                            } else {

                                return back()->with(
                                    'toast_error',
                                    'Oops!!, Service Temporarily Unavailable'
                                );
                            }
                        } else {
                            DB::table('transactions')->where('transactionId', $transactionId)->update([
                                'status' => 'Failed',
                            ]);
                            DB::table('rcpurchases')->where('transactionId', $rcpurchasesTransactionId)->update([
                                'status' => 'Failed',

                            ]);
                            return back()->with(
                                'toast_error',
                                'Oops!!, Service Temporarily Unavailable'
                            );
                        }
                    }
                }
            }
        }
    }
}
