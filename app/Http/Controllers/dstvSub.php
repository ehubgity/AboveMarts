<?php

namespace App\Http\Controllers;

use App\Helpers\TransactionHelper;
use App\Services\EBillsService;
use Illuminate\Support\Facades\DB;
use App\Models\bonus;
use  App\Models\User;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class dstvSub extends Controller
{
    //
    private $eBillsService;

    public function __construct(EBillsService $eBillsService)
    {
        $this->middleware('auth');
        $this->eBillsService = $eBillsService;
    }

    public function index(Request $request)
    {
        $ebillsService = new EbillsService();

        try {
            // Get variations for all cable services
            $dstvVariations = $ebillsService->getTvVariations('dstv');
            $gotvVariations = $ebillsService->getTvVariations('gotv');
            $startimesVariations = $ebillsService->getTvVariations('startimes');

            $cableVariations = [
                'dstv' => $dstvVariations['variations'] ?? [],
                'gotv' => $gotvVariations['variations'] ?? [],
                'startimes' => $startimesVariations['variations'] ?? [],
            ];
        } catch (Exception $e) {
            Log::error('Cable variations fetch error: ' . $e->getMessage());
            $cableVariations = [
                'dstv' => [],
                'gotv' => [],
                'startimes' => [],
            ];
        }

        return view('user.dstvsub', compact('cableVariations'));
    }
    public function randomDigit()
    {
        $pass = substr(str_shuffle("01234561089abcDEfadsasfdasdfasdsfa3425542FGHIJnostXYZ"), 0, 30);
        return $pass;
    }
    public function verify(Request $request)
    {
        $id = $request->id;

        if (isset($id)) {
            $data = DB::table('cables')
                ->where('userId', auth()->user()->userId)
                ->where('transactionId', $id)
                ->orderBy('id', 'desc')
                ->first();
            return view('user.verifycable')->with('data', $data);
        } else {
            return view('user.dstvsub');
        }
    }

    public function verifycable(Request $request)
    {
        // return $request->package_bouquet;
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
            ->where('sponsorId', auth()->user()->mySponsorId)
            ->sum('amount');

        $balance = $capital - $expenses;
        $serviceFee = 50;
        $bronzeamount = ($serviceFee * 15) / 100;
        $silveramount = ($serviceFee * 20) / 100;
        $goldamount = ($serviceFee * 25) / 100;
        $platinumamount = ($serviceFee * 30) / 100;

        if ($request->amount == 0) {
            return back()->with('toast_error', "Oops !! amount can't be zero");
        } else {
            if ($balance < $request->amount) {
                // return [$balance, $request->amount];
                return back()->with('toast_error', 'Insufficient Funds');
            } else {
                if ($request->packagecable == 'none') {
                    return back()->with('toast_error', 'Select an Cable services');
                } else {
                    $customer_id = $request->input('smartNumber');
                    $service_id = $request->input('packagecable');
                    $gotv = $request->input('packageGotv');
                    $dstvtv = $request->input('packageDstv');
                    $startimes = $request->input('packageStarTime');


                    try {
                        // return [$balance, $request->amount];

                        $result = $this->eBillsService->verifyCustomer(
                            $request->smartNumber,
                            $request->packagecable,
                            "prepaid"
                        );

                        // return   $result;
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

                        $cableName = $customerData['customer_name'];
                        $product = $customerData['service_name'];
                        DB::table('cables')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => auth()->user()->phoneNumber,
                                'amount' => $request->amount,
                                'smartNumber' => $request->smartNumber,
                                'cableName' => $cableName,
                                'product' => $product,
                                'productVariation' => $request->package_bouquet,
                                'productVariationId' => $request->variation_id,
                                'status' => 'CONFIRM',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);
                        $data = DB::table('cables')
                            ->where('userId', auth()->user()->userId)
                            ->where('smartNumber', $request->smartNumber)
                            ->orderBy('id', 'desc')
                            ->first();

                        return redirect()->route('verifycable', ['id' => $data->transactionId]);
                    } else {
                        return back()->with(
                            'toast_error',
                            'Oops!!, Smart number failed to verify'
                        );
                    }

                    // if ($request->input('packagecable') == "gotv") {
                    //     // Make the API request
                    //     $ch = curl_init();
                    //     curl_setopt(
                    //         $ch,
                    //         CURLOPT_URL,
                    //         "https://mobileone.ng/api/v2/tv/?api_key={$api}&smartcard_number={$customer_id}&product_code={$gotv}&task=verify"
                    //     );
                    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    //     curl_setopt($ch, CURLOPT_POST, true);
                    //     curl_setopt($ch, CURLOPT_POST, true);
                    //     $result = curl_exec($ch);
                    //     $verificationResult = json_decode($result);

                    //     if ($verificationResult->status === true) {
                    //         DB::table('cables')
                    //             ->where('userId', auth()->user()->userId)
                    //             ->insert([
                    //                 'transactionId' => $this->randomDigit(),
                    //                 'userId' => auth()->user()->userId,
                    //                 'username' => auth()->user()->username,
                    //                 'email' => auth()->user()->email,
                    //                 'phoneNumber' => auth()->user()->phoneNumber,
                    //                 'amount' => $request->amount,
                    //                 'smartNumber' => $request->smartNumber,
                    //                 'cableName' => $verificationResult->data->name,
                    //                 'product' => $request->packagecable,
                    //                 'productVariation' => $gotv,
                    //                 'status' => 'CONFIRM',
                    //                 "created_at" => date('Y-m-d H:i:s'),
                    //                 "updated_at" => date('Y-m-d H:i:s'),
                    //             ]);
                    //         $data = DB::table('cables')
                    //             ->where('userId', auth()->user()->userId)
                    //             ->where('smartNumber', $request->smartNumber)
                    //             ->orderBy('id', 'desc')
                    //             ->first();

                    //         return redirect()->route('verifycable', ['id' => $data->transactionId]);
                    //     } else {
                    //         return back()->with(
                    //             'toast_error',
                    //             'Oops!!, Smart number failed to verify'
                    //         );
                    //     }
                    // } elseif ($request->input('packagecable') == "dstv") {
                    //     // Make the API request
                    //     $ch = curl_init();
                    //     curl_setopt(
                    //         $ch,
                    //         CURLOPT_URL,
                    //         "https://mobileone.ng/api/v2/tv/?api_key={$api}&smartcard_number={$customer_id}&product_code={$dstvtv}&task=verify"
                    //     );
                    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    //     curl_setopt($ch, CURLOPT_POST, true);
                    //     curl_setopt($ch, CURLOPT_POST, true);
                    //     $result = curl_exec($ch);
                    //     $verificationResult = json_decode($result);

                    //     if ($verificationResult->status === true) {

                    //         DB::table('cables')
                    //             ->where('userId', auth()->user()->userId)
                    //             ->insert([
                    //                 'transactionId' => $this->randomDigit(),
                    //                 'userId' => auth()->user()->userId,
                    //                 'username' => auth()->user()->username,
                    //                 'email' => auth()->user()->email,
                    //                 'phoneNumber' => auth()->user()->phoneNumber,
                    //                 'amount' => $request->amount,
                    //                 'smartNumber' => $request->smartNumber,
                    //                 'cableName' => $verificationResult->data->name,
                    //                 'product' => $request->packagecable,
                    //                 'productVariation' => $dstvtv,
                    //                 'status' => 'CONFIRM',
                    //                 "created_at" => date('Y-m-d H:i:s'),
                    //                 "updated_at" => date('Y-m-d H:i:s'),
                    //             ]);
                    //         $data = DB::table('cables')
                    //             ->where('userId', auth()->user()->userId)
                    //             ->where('smartNumber', $request->smartNumber)
                    //             ->orderBy('id', 'desc')
                    //             ->first();

                    //         return redirect()->route('verifycable', ['id' => $data->transactionId]);
                    //     } else {
                    //         return back()->with(
                    //             'toast_error',
                    //             'Oops!!, Smart number failed to verify'
                    //         );
                    //     }
                    // } elseif ($request->input('packagecable') == "startimes") {
                    //     // Make the API request
                    //     $ch = curl_init();
                    //     curl_setopt(
                    //         $ch,
                    //         CURLOPT_URL,
                    //         "https://mobileone.ng/api/v2/tv/?api_key={$api}&smartcard_number={$customer_id}&product_code={$startimes}&task=verify"
                    //     );
                    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    //     curl_setopt($ch, CURLOPT_POST, true);
                    //     curl_setopt($ch, CURLOPT_POST, true);
                    //     $result = curl_exec($ch);
                    //     $verificationResult = json_decode($result);

                    //     if ($verificationResult->status === true) {

                    //         DB::table('cables')
                    //             ->where('userId', auth()->user()->userId)
                    //             ->insert([
                    //                 'transactionId' => $this->randomDigit(),
                    //                 'userId' => auth()->user()->userId,
                    //                 'username' => auth()->user()->username,
                    //                 'email' => auth()->user()->email,
                    //                 'phoneNumber' => auth()->user()->phoneNumber,
                    //                 'amount' => $request->amount,
                    //                 'smartNumber' => $request->smartNumber,
                    //                 'cableName' => $verificationResult->data->name,
                    //                 'product' => $request->packagecable,
                    //                 'productVariation' => $startimes,
                    //                 'status' => 'CONFIRM',
                    //                 "created_at" => date('Y-m-d H:i:s'),
                    //                 "updated_at" => date('Y-m-d H:i:s'),
                    //             ]);
                    //         $data = DB::table('cables')
                    //             ->where('userId', auth()->user()->userId)
                    //             ->where('smartNumber', $request->smartNumber)
                    //             ->orderBy('id', 'desc')
                    //             ->first();

                    //         return redirect()->route('verifycable', ['id' => $data->transactionId]);
                    //     } else {
                    //         return back()->with(
                    //             'toast_error',
                    //             'Oops!!, Smart number failed to verify'
                    //         );
                    //     }
                    // } else {
                    //     return back()->with('toast_error', 'Select an Cable services');
                    // }
                }
            }
        }
    }
    public function store(Request $request)
    {
        $expenses = DB::table('transactions')
            ->where('userId', auth()->user()->userId)
            ->where('transactionType', '!=', 'Deposit')
            ->where('status', 'CONFIRM')
            ->sum('amount');
        $capital = DB::table('funds')
            ->where('userId', auth()->user()->userId)
            ->sum('amount');
        $bonusamount = DB::table('bonuses')
            ->where('sponsorId', auth()->user()->mySponsorId)
            ->sum('amount');

        $balance = $capital - $expenses;
        $serviceFee = 50;
        $bronzeamount = ($serviceFee * 15) / 100;
        $silveramount = ($serviceFee * 20) / 100;
        $goldamount = ($serviceFee * 25) / 100;
        $platinumamount = ($serviceFee * 30) / 100;
        $data = DB::table('cables')
            ->where('userId', auth()->user()->userId)
            ->where('transactionId', $request->id)
            ->orderBy('id', 'desc')
            ->first();

        // return dd($verificationResult);
        // $aa = "Ff";
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
                'phoneNumber' => $request->smartNumber,
                'amount' => $request->amount,
                'transactionType' => 'Cable Purchase',
                'transactionService' => $data->product,
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
                'phoneNumber' => $request->smartNumber,
                'amount' => $serviceFee,
                'transactionType' => 'Service Fee',
                'transactionService' => $data->product,
                'status' => 'CONFIRM',
                'paymentMethod' => 'wallet',
                'Admin' => 'None',

                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ]);
            // TransactionHelper::updateAccountManagerTotals(auth()->user()->userId, $request->amount, 'Cable Purchase');

            //  Update User Balance
            $user = User::findOrFail(auth()->id());

            $user->update([
                'beforeBalance' => $balance,
                'currentBalance' => $balance - $request->amount,
            ]);


            // $ch = curl_init();
            // curl_setopt(
            //     $ch,
            //     CURLOPT_URL,
            //     "https://mobileone.ng/api/v2//tv/?api_key={$api}&product_code={$data->productVariation}&smartcard_number={$request->smartNumber}&callback=https://mobileone.ng/webhook.php"
            // );
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_POST, true);
            // curl_setopt($ch, CURLOPT_POST, true);
            // $result = curl_exec($ch);
            // $verificationResult = json_decode($result);

            try {
                $result = $this->eBillsService->purchaseTvSubscription(
                    $data->smartNumber,
                    $data->product,
                    $data->productVariationId,
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

            if ($result['code'] === 'success') {

                DB::table('cables')
                    ->where('userId', auth()->user()->userId)
                    ->where('transactionId', $data->transactionId)
                    ->update(['status' => 'CONFIRM']);

                if (auth()->user()->package == 'Bronze') {
                    if ($request->payment == 'wallet') {
                        if ($bonusamount >= auth()->user()->expectedEarning) {
                            return redirect()
                                ->route('tvsub')
                                ->with('toast_success', 'Transaction Successful !!');
                        } else {
                            bonus::create([
                                'bonusId' => $this->randomDigit(),
                                'sponsor' => auth()->user()->mySponsorId,
                                'sponsorId' => auth()->user()->mySponsorId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'amount' => $bronzeamount,
                                'package' => 'Cable',
                                'status' => 'Confirm',
                                'dayCounter' => 0,
                            ]);
                            return redirect()
                                ->route('tvsub')
                                ->with('toast_success', 'Transaction Successful !!');
                        }
                    } elseif ($request->payment == 'epin') {
                        DB::table('transactions')->where('transactionId', $transactionId)->update([
                            'paymentMethod' => 'epin',
                        ]);
                        DB::table('transactions')->where('transactionId', $transactionServiceId)->update([
                            'paymentMethod' => 'epin',
                        ]);
                        return redirect()
                            ->route('tvsub')
                            ->with('toast_success', 'Transaction Successful !!');
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
                            return redirect()
                                ->route('tvsub')
                                ->with('toast_success', 'Transaction Successful !!');
                        } else {
                            bonus::create([
                                'bonusId' => $this->randomDigit(),
                                'sponsor' => auth()->user()->mySponsorId,
                                'sponsorId' => auth()->user()->mySponsorId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'amount' => $silveramount,
                                'package' => 'Cable',
                                'status' => 'Confirm',
                                'dayCounter' => 0,
                            ]);
                            return redirect()
                                ->route('tvsub')
                                ->with('toast_success', 'Transaction Successful !!');
                        }
                    } elseif ($request->payment == 'epin') {
                        DB::table('transactions')->where('transactionId', $transactionId)->update([
                            'paymentMethod' => 'epin',
                        ]);
                        DB::table('transactions')->where('transactionId', $transactionServiceId)->update([
                            'paymentMethod' => 'epin',
                        ]);
                        return redirect()
                            ->route('tvsub')
                            ->with('toast_success', 'Transaction Successful !!');
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
                            return redirect()
                                ->route('tvsub')
                                ->with('toast_success', 'Transaction Successful !!');
                        } else {
                            bonus::create([
                                'bonusId' => $this->randomDigit(),
                                'sponsor' => auth()->user()->mySponsorId,
                                'sponsorId' => auth()->user()->mySponsorId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'amount' => $goldamount,
                                'package' => 'Cable',
                                'status' => 'Confirm',
                                'dayCounter' => 0,
                            ]);
                            return redirect()
                                ->route('tvsub')
                                ->with('toast_success', 'Transaction Successful !!');
                        }
                    } elseif ($request->payment == 'epin') {
                        DB::table('transactions')->where('transactionId', $transactionId)->update([
                            'paymentMethod' => 'epin',
                        ]);
                        DB::table('transactions')->where('transactionId', $transactionServiceId)->update([
                            'paymentMethod' => 'epin',
                        ]);
                        return redirect()
                            ->route('tvsub')
                            ->with('toast_success', 'Transaction Successful !!');
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
                            return redirect()
                                ->route('tvsub')
                                ->with('toast_success', 'Transaction Successful !!');
                        } else {
                            bonus::create([
                                'bonusId' => $this->randomDigit(),
                                'sponsor' => auth()->user()->mySponsorId,
                                'sponsorId' => auth()->user()->mySponsorId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'amount' => $platinumamount,
                                'package' => 'Cable',
                                'status' => 'Confirm',
                                'dayCounter' => 0,
                            ]);
                            return redirect()
                                ->route('tvsub')
                                ->with('toast_success', 'Transaction Successful !!');
                        }
                    } elseif ($request->payment == 'epin') {
                        DB::table('transactions')->where('transactionId', $transactionId)->update([
                            'paymentMethod' => 'epin',
                        ]);
                        DB::table('transactions')->where('transactionId', $transactionServiceId)->update([
                            'paymentMethod' => 'epin',
                        ]);
                        return redirect()
                            ->route('tvsub')
                            ->with('toast_success', 'Transaction Successful !!');
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

                        return redirect()
                            ->route('tvsub')
                            ->with('toast_success', 'Transaction Successful !!');
                    } elseif ($request->payment == 'epin') {
                        DB::table('transactions')->where('transactionId', $transactionId)->update([
                            'paymentMethod' => 'epin',
                        ]);
                        DB::table('transactions')->where('transactionId', $transactionServiceId)->update([
                            'paymentMethod' => 'epin',
                        ]);
                        return redirect()
                            ->route('tvsub')
                            ->with('toast_success', 'Transaction Successful !!');
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
                return back()->with('toast_error', 'Oops!!, Service temporarily unavaliable');
            }
        }
    }
}
