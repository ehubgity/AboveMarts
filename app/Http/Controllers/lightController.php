<?php

namespace App\Http\Controllers;

use App\Helpers\TransactionHelper;
use Illuminate\Support\Facades\Validator;
use App\Models\bonus;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

use App\Services\EBillsService;
use Illuminate\Http\JsonResponse;

class lightController extends Controller
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
        return view('user.lightpurchase');
    }
    public function token(Request $request)
    {
        if (isset($request->id)) {
            $tokendata = DB::table('lightpurchases')
                ->where('userId', auth()->user()->userId)
                ->where('transactionId', $request->id)
                ->orderBy('id', 'desc')
                ->first();
            return view('user.token')->with('tokendata', $tokendata);
        } else {
            return view('user.lightpurchase');
        }
    }
    public function verify(Request $request)
    {
        $id = $request->id;

        if (isset($id)) {
            $data = DB::table('lightpurchases')
                ->where('userId', auth()->user()->userId)
                ->where('transactionId', $id)
                ->orderBy('id', 'desc')
                ->first();
            return view('user.verify')->with('data', $data);
        } else {
            return view('user.lightpurchase');
        }
    }

    public function randomDigit()
    {
        $pass = substr(str_shuffle("01234561089abcDEfadsasfdasdfasdsfa3425542FGHIJnostXYZ"), 0, 30);
        return $pass;
    }
    public function verifystore(Request $request)
    {
        // try {
        $validator = Validator::make($request->all(), [
            'meterNumber' => 'required|numeric',
            'amount' => 'required|numeric|min:1000',
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
        $balance = $capital + 0 - $expenses;
        $serviceFee = 50;
        if ($request->amount < 1000) {
            return back()->with('toast_error', 'You can only buy from 1000 naira ');
        } else {
            if ($balance < $request->amount + $serviceFee) {
                return back()->with('toast_error', 'Insufficient Funds');
            } else {
                if ($request->package == 'none') {
                    return back()->with('toast_error', 'Select an electricity services');
                } else {
                    $api = getenv('TELECOM_API');
                    $productCode = $request->package;
                    $amount = $request->amount;
                    $meterNumber = $request->meterNumber;

                    $customer_id = $request->input('meterNumber');
                    $service_id = $request->input('package');
                    // Make the API request
                    // https://smartrecharge.ng/api/v2/
                    // $ch = curl_init();
                    // curl_setopt(
                    //     $ch,
                    //     CURLOPT_URL,
                    //     "https://mobileone.ng/api/v2/electric/?api_key={$api}&meter_number={$meterNumber}&product_code={$productCode}&task=verify"
                    // );
                    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    // curl_setopt($ch, CURLOPT_POST, true);
                    // curl_setopt($ch, CURLOPT_POST, true);
                    // $result = curl_exec($ch);
                    // $verificationResult = json_decode($result);

                    try {
                        $result = $this->eBillsService->verifyCustomer(
                            $request->meterNumber,
                            $request->package,
                            "prepaid"
                        );

                        // return response()->json([
                        //     'success' => $result['code'] === 'success',
                        //     'code' => $result['code'],
                        //     'message' => $result['message'],
                        //     'customer_info' => $result['customer_info'],
                        //     'data' => $result['raw_response']
                        // ]);

                    } catch (\Exception $e) {
                        return back()->with(
                            'toast_error',
                            $e->getMessage()
                        );
                        // return response()->json([
                        //     'success' => false,
                        //     'message' => 'Failed to verify customer',
                        //     'error' => $e->getMessage()
                        // ], 400);
                    }

                    if ($result['code'] === 'success') {
                        $customerData = $result['customer_info'];

                        $meterName = $customerData['customer_name'];
                        $meterAddress = $customerData['customer_address'];
                        DB::table('lightpurchases')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => auth()->user()->phoneNumber,
                                'amount' => $request->amount,
                                'meter' => $request->meterNumber,
                                'product' => $request->package,
                                'meterName' => $meterName,
                                'meterAddress' => $meterAddress,
                                'token' => "NONE",
                                'status' => 'PENDING',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);

                        $data = DB::table('lightpurchases')
                            ->where('userId', auth()->user()->userId)
                            ->where('meter', $request->meterNumber)
                            ->orderBy('id', 'desc')
                            ->first();

                        return redirect()->route('verify', ['id' => $data->transactionId]);
                    } else {
                        return back()->with('toast_error', 'Oops!!, Meter number failed to verify');
                    }
                }
            }
        }
        // } catch (\Throwable $th) {
        //     return redirect()
        //         ->route('lightpurchase')
        //         ->with('toast_error', 'Service Temporary Unavailable');
        // }
    }
    public function store(Request $request)
    {
        // try {
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
        $balance = $capital + 0 - $expenses;
        $serviceFee = 50;
        $bronzeamount = ($serviceFee * 15) / 100;
        $silveramount = ($serviceFee * 20) / 100;
        $goldamount = ($serviceFee * 25) / 100;
        $platinumamount = ($serviceFee * 30) / 100;

        $data = DB::table('lightpurchases')
            ->where('userId', auth()->user()->userId)
            ->where('transactionId', $request->id)
            ->orderBy('id', 'desc')
            ->first();

        if ($data == null) {
            return redirect()
                ->route('lightpurchase')
                ->with('toast_error', 'Try again');
        } else {
            // $url = 'https://mobileone.ng/api/v2/electric';
            // $api = getenv('TELECOM_API');
            $productCode = $data->product;
            $amount = $data->amount;
            $meterNumber = $data->meter;
            if ($balance < $request->amount + $serviceFee) {
                return back()->with('toast_error', 'Insufficient Funds');
            } else {

                $transactionId = $this->randomDigit();
                $transactionServiceId = $this->randomDigit();

                DB::table('transactions')->insert([
                    'transactionId' => $transactionId,
                    'userId' => auth()->user()->userId,
                    'username' => auth()->user()->username,
                    'email' => auth()->user()->email,
                    'phoneNumber' => $meterNumber,
                    'amount' => $request->amount,
                    'transactionType' => 'Electricity',
                    'transactionService' => 'Electricity',
                    'status' => 'CONFIRM',
                    'paymentMethod' => 'wallet',
                    'Admin' => 'None',
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s'),
                ]);

                DB::table('transactions')->insert([
                    'transactionId' => $transactionServiceId,
                    'userId' => auth()->user()->userId,
                    'username' => auth()->user()->username,
                    'email' => auth()->user()->email,
                    'phoneNumber' => $meterNumber,
                    'amount' => $serviceFee,
                    'transactionType' => 'Service Fee',
                    'transactionService' => 'Electricity',
                    'status' => 'CONFIRM',
                    'paymentMethod' => 'wallet',
                    'Admin' => 'None',
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s'),
                ]);
                // TransactionHelper::updateAccountManagerTotals(auth()->user()->userId, $request->amount, 'Electricity');

                //  Update User Balance
                $user = User::findOrFail(auth()->id());

                $user->update([
                    'beforeBalance' => $balance,
                    'currentBalance' => $balance - $request->amount,
                ]);

                // Make the API request
                // $ch = curl_init();
                // curl_setopt(
                //     $ch,
                //     CURLOPT_URL,
                //     "https://mobileone.ng/api/v2/electric/?api_key={$api}&product_code={$data->product}&meter_number={$meterNumber}&amount={$request->amount}&callback=https://mobileone.ng/webhook.php"
                // );
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // curl_setopt($ch, CURLOPT_POST, true);
                // curl_setopt($ch, CURLOPT_POST, true);
                // $result = curl_exec($ch);
                // $verificationResult = json_decode($result);


                try {
                    $result = $this->eBillsService->purchaseElectricity(
                        $meterNumber,
                        $productCode,
                        "prepaid",
                        $amount,
                        $transactionId
                    );

                    // return response()->json([
                    //     'success' => $result['code'] === 'success',
                    //     'code' => $result['code'],
                    //     'message' => $result['message'],
                    //     'transaction_id' => $result['transaction_id'],
                    //     'meter_token' => $result['token'],
                    //     'units' => $result['units'],
                    //     'customer_info' => $result['customer_info'],
                    //     'data' => $result['raw_response']
                    // ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to purchase electricity',
                        'error' => $e->getMessage()
                    ], 400);
                }

                DB::table('transactions')->where('transactionId', $transactionId)->update([
                    'apiStatus' => $result['code'],
                ]);

                if ($result['code'] === "success") {

                    // if ($a = 223) {
                    DB::table('lightpurchases')
                        ->where('userId', auth()->user()->userId)
                        ->where('transactionId', $data->transactionId)
                        ->update([
                            'status' => 'CONFIRM',
                            'token' => $result['token'],
                        ]);

                    if (auth()->user()->package == 'Bronze') {
                        if ($request->payment == 'wallet') {

                            bonus::create([
                                'bonusId' => $this->randomDigit(),
                                'sponsor' => auth()->user()->username,
                                'sponsorId' => auth()->user()->username,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'amount' => $bronzeamount,
                                'package' => 'Electricity',
                                'status' => 'Confirm',
                                'dayCounter' => 0,
                            ]);


                            return redirect()->route('token', ['id' => $data->transactionId]);

                            // return redirect()
                            //     ->route('lightpurchase')
                            //     ->withToastSuccess('Transaction Successful !!');
                        } elseif ($request->payment == 'epin') {
                            DB::table('transactions')->where('transactionId', $transactionId)->update([
                                'paymentMethod' => 'epin',
                            ]);
                            DB::table('transactions')->where('transactionId', $transactionServiceId)->update([
                                'paymentMethod' => 'epin',
                            ]);
                            return redirect()->route('token', ['id' => $data->transactionId]);
                            // return redirect()
                            //     ->route('lightpurchase')
                            //     ->withToastSuccess('Transaction Successful !!');
                        } elseif ($request->payment == 'promo') {
                            DB::table('transactions')->where('transactionId', $transactionId)->update([
                                'paymentMethod' => 'promo',
                            ]);
                            DB::table('transactions')->where('transactionId', $transactionServiceId)->update([
                                'paymentMethod' => 'promo',
                            ]);
                            return redirect()->route('token', ['id' => $data->transactionId]);

                            // return redirect()
                            //     ->route('lightpurchase')
                            //     ->withToastSuccess('Transaction Successful !!');
                        } else {
                            return back()->with(
                                'toast_error',
                                'Oops!!, Service Temporarily Unavailable'
                            );
                        }
                    } elseif (auth()->user()->package == 'Silver') {
                        if ($request->payment == 'wallet') {

                            if ($bonusamount >= auth()->user()->totalEarning) {
                                // return redirect()
                                //     ->route('lightpurchase')
                                //     ->withToastSuccess('Transaction Successful !!');
                                return redirect()->route('token', ['id' => $data->transactionId]);
                            } else {
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->username,
                                    'sponsorId' => auth()->user()->username,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $silveramount,
                                    'package' => 'Electricity',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                // return redirect()
                                //     ->route('lightpurchase')
                                //     ->withToastSuccess('Transaction Successful !!');
                                return redirect()->route('token', ['id' => $data->transactionId]);
                            }
                        } elseif ($request->payment == 'epin') {
                            DB::table('transactions')->where('transactionId', $transactionId)->update([
                                'paymentMethod' => 'epin',
                            ]);
                            DB::table('transactions')->where('transactionId', $transactionServiceId)->update([
                                'paymentMethod' => 'epin',
                            ]);
                            return redirect()->route('token', ['id' => $data->transactionId]);
                            // return redirect()
                            //     ->route('lightpurchase')
                            //     ->withToastSuccess('Transaction Successful !!');
                        } elseif ($request->payment == 'promo') {
                            DB::table('transactions')->where('transactionId', $transactionId)->update([
                                'paymentMethod' => 'promo',
                            ]);
                            DB::table('transactions')->where('transactionId', $transactionServiceId)->update([
                                'paymentMethod' => 'promo',
                            ]);
                            return redirect()->route('token', ['id' => $data->transactionId]);

                            // return redirect()
                            //     ->route('lightpurchase')
                            //     ->withToastSuccess('Transaction Successful !!');
                        } else {
                            return back()->with(
                                'toast_error',
                                'Oops!!, Service Temporarily Unavailable'
                            );
                        }
                    } elseif (auth()->user()->package == 'Gold') {
                        if ($request->payment == 'wallet') {

                            if ($bonusamount >= auth()->user()->totalEarning) {
                                // return redirect()
                                //     ->route('lightpurchase')
                                //     ->withToastSuccess('Transaction Successful !!');
                                return redirect()->route('token', ['id' => $data->transactionId]);
                            } else {
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->username,
                                    'sponsorId' => auth()->user()->username,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $goldamount,
                                    'package' => 'Electricity',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                // return redirect()
                                //     ->route('lightpurchase')
                                //     ->withToastSuccess('Transaction Successful !!');
                                return redirect()->route('token', ['id' => $data->transactionId]);
                            }
                        } elseif ($request->payment == 'epin') {
                            DB::table('transactions')->where('transactionId', $transactionId)->update([
                                'paymentMethod' => 'epin',
                            ]);
                            DB::table('transactions')->where('transactionId', $transactionServiceId)->update([
                                'paymentMethod' => 'epin',
                            ]);
                            return redirect()->route('token', ['id' => $data->transactionId]);
                            // return redirect()
                            //     ->route('lightpurchase')
                            //     ->withToastSuccess('Transaction Successful !!');
                        } elseif ($request->payment == 'promo') {
                            DB::table('transactions')->where('transactionId', $transactionId)->update([
                                'paymentMethod' => 'promo',
                            ]);
                            DB::table('transactions')->where('transactionId', $transactionServiceId)->update([
                                'paymentMethod' => 'promo',
                            ]);
                            return redirect()->route('token', ['id' => $data->transactionId]);

                            // return redirect()
                            //     ->route('lightpurchase')
                            //     ->withToastSuccess('Transaction Successful !!');
                        } else {
                            return back()->with(
                                'toast_error',
                                'Oops!!, Service Temporarily Unavailable'
                            );
                        }
                    } elseif (auth()->user()->package == 'Platinum') {
                        if ($request->payment == 'wallet') {
                            if ($bonusamount >= auth()->user()->totalEarning) {
                                // return redirect()
                                //     ->route('lightpurchase')
                                //     ->withToastSuccess('Transaction Successful !!');
                                return redirect()->route('token', ['id' => $data->transactionId]);
                            } else {
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->username,
                                    'sponsorId' => auth()->user()->username,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $platinumamount,
                                    'package' => 'Electricity',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                // return redirect()
                                //     ->route('lightpurchase')
                                //     ->withToastSuccess('Transaction Successful !!');
                                return redirect()->route('token', ['id' => $data->transactionId]);
                            }
                        } elseif ($request->payment == 'epin') {
                            DB::table('transactions')->where('transactionId', $transactionId)->update([
                                'paymentMethod' => 'epin',
                            ]);
                            DB::table('transactions')->where('transactionId', $transactionServiceId)->update([
                                'paymentMethod' => 'epin',
                            ]);
                            return redirect()->route('token', ['id' => $data->transactionId]);
                            // return redirect()
                            //     ->route('lightpurchase')
                            //     ->withToastSuccess('Transaction Successful !!');
                        } elseif ($request->payment == 'promo') {
                            DB::table('transactions')->where('transactionId', $transactionId)->update([
                                'paymentMethod' => 'promo',
                            ]);
                            DB::table('transactions')->where('transactionId', $transactionServiceId)->update([
                                'paymentMethod' => 'promo',
                            ]);
                            return redirect()->route('token', ['id' => $data->transactionId]);

                            // return redirect()
                            //     ->route('lightpurchase')
                            //     ->withToastSuccess('Transaction Successful !!');
                        } else {
                            return back()->with(
                                'toast_error',
                                'Oops!!, Service Temporarily Unavailable'
                            );
                        }
                    } else {
                        if ($request->payment == 'wallet') {

                            bonus::create([
                                'bonusId' => $this->randomDigit(),
                                'sponsor' => auth()->user()->uplineOne,
                                'sponsorId' => auth()->user()->mySponsorId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'amount' => $platinumamount,
                                'package' => 'Electricity',
                                'status' => 'Confirm',
                                'dayCounter' => 0,
                            ]);
                            return redirect()->route('token', ['id' => $data->transactionId]);
                        } elseif ($request->payment == 'epin') {
                            DB::table('transactions')->where('transactionId', $transactionId)->update([
                                'paymentMethod' => 'epin',
                            ]);
                            DB::table('transactions')->where('transactionId', $transactionServiceId)->update([
                                'paymentMethod' => 'epin',
                            ]);
                            return redirect()->route('token', ['id' => $data->transactionId]);
                            // return redirect()
                            //     ->route('lightpurchase')
                            //     ->withToastSuccess('Transaction Successful !!');
                        } elseif ($request->payment == 'promo') {
                            DB::table('transactions')->where('transactionId', $transactionId)->update([
                                'paymentMethod' => 'promo',
                            ]);
                            DB::table('transactions')->where('transactionId', $transactionServiceId)->update([
                                'paymentMethod' => 'promo',
                            ]);
                            return redirect()->route('token', ['id' => $data->transactionId]);

                            // return redirect()
                            //     ->route('lightpurchase')
                            //     ->withToastSuccess('Transaction Successful !!');
                        } else {
                            return back()->with(
                                'toast_error',
                                'Oops!!, Service Temporarily Unavailable'
                            );
                        }
                    }
                } else {
                    DB::table('transactions')->where('transactionId', $transactionId)->update([
                        'status' => 'Failed',
                    ]);
                    DB::table('transactions')->where('transactionId', $transactionServiceId)->update([
                        'status' => 'Failed',
                    ]);
                    return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                }
            }
        }


        // } catch (\Throwable $th) {
        //     return redirect()
        //         ->route('lightpurchase')
        //         ->with('toast_error', 'Contact Admin');
        // }
    }
}
