<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\bonus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class dataShare extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $api = getenv('TELECOM_API_EASYACCESS');
        $responsemtn = Http::withHeaders([
            'AuthorizationToken' => $api,
            'cache-control' => 'no-cache',
        ])->get('https://easyaccessapi.com.ng/api/get_plans.php', [
            'product_type' => 'mtn_sme',
        ]);
        $responsemtn->body();
        $datamtn = $responsemtn->json();

        $mtnplans = $datamtn['MTN'];
        // Airtel
        $responseairtel = Http::withHeaders([
            'AuthorizationToken' => $api,
            'cache-control' => 'no-cache',
        ])->get('https://easyaccessapi.com.ng/api/get_plans.php', [
            'product_type' => 'airtel_cg',
        ]);
        $responseairtel->body();
        $dataairtel = $responseairtel->json();
        $airtelplans = $dataairtel['AIRTEL'];
        // Glo
        $responseglo = Http::withHeaders([
            'AuthorizationToken' => $api,
            'cache-control' => 'no-cache',
        ])->get('https://easyaccessapi.com.ng/api/get_plans.php', [
            'product_type' => 'glo_cg',
        ]);
        $responseglo->body();
        $dataglo = $responseglo->json();

        $gloplans = $dataglo['GLO'];
        // 9mobile
        $response9mobile = Http::withHeaders([
            'AuthorizationToken' => $api,
            'cache-control' => 'no-cache',
        ])->get('https://easyaccessapi.com.ng/api/get_plans.php', [
            'product_type' => '9mobile_sme',
        ]);
        $response9mobile->body();
        $data9mobile = $response9mobile->json();
        $mobileplans = $data9mobile['9MOBILE'];

        return view('user.datashare')->with('mobileplans', $mobileplans)->with('gloplans', $gloplans)->with('airtelplans', $airtelplans)->with('mtnplans', $mtnplans);
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
            // 'amount' => 'required|numeric|min:50',
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
            ->where('sponsorId', auth()->user()->mySponsorId)
            ->sum('amount');
        $bronzeamount = ($request->amount * 2) / 100;
        $silveramount = ($request->amount * 2) / 100;
        $goldamount = ($request->amount * 2) / 100;
        $platinumamount = ($request->amount * 2) / 100;
        // $bonus = DB::table('transactions')
        //     ->where('userId', auth()->user()->userId)
        //     ->where('transactionService', 'Commission')
        //     ->sum('amount');
        if ($request->amount < 0) {
            return back()->with('toast_error', "Oops !! Amount Can't be zero");
        } else {
            $balance = $capital - $expenses;
            if ($balance < $request->amount) {
                return back()->with('toast_error', 'Insufficient Funds');
            } else {
                if ($request->network == 'mtn' && $balance >= $request->amount) {
                    // adding amount to db
                    $transactionId = $this->randomDigit();
                    $datapurchasesId = $transactionId;
                    
                    
                          DB::table('transactions')->insert([
                                'transactionId' => $transactionId,
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => $request->phoneNumber,
                                'amount' => $request->amount,
                                'transactionType' => 'Data Share',
                                'transactionService' => $request->network,
                                'status' => 'PENDING',
                                'paymentMethod' => 'wallet',
                                'Admin' => 'None',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);
                            DB::table('datapurchases')
                                ->where('userId', auth()->user()->userId)
                                ->insert([
                                    'transactionId' => $datapurchasesId,
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'phoneNumber' => $request->phoneNumber,
                                    'amount' => $request->amount,
                                    'network' => $request->network,
                                    'product' => $request->amount,
                                    'status' => 'PENDING',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);


                    $api = getenv('TELECOM_API_EASYACCESS');
                    $phoneNumber = $request->phoneNumber;
                    $productCode = $request->packageMTN;
                    $amount = $request->amount;

                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://easyaccessapi.com.ng/api/data.php",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => array(
                            'network' => 01,
                            'mobileno' =>  $request->phoneNumber,
                            'dataplan' => $request->packageMTN,
                            'client_reference' => $datapurchasesId,
                            'max_amount_payable' => $request->amount
                        ),
                        CURLOPT_HTTPHEADER => array(
                            "AuthorizationToken: $api",
                            "cache-control: no-cache"
                        ),
                    ));
                    $result = curl_exec($curl);
                    curl_close($curl);
                    $response = json_decode($result);
                    if ($response != null && $response->success != 'false_disabled') {
                        if ($response->status == true && $balance > $request->amount) {
                            // 
                      
                            DB::table('transactions')->where('transactionId', $transactionId)->update([
                                'apiStatus' => $response->status,
                            ]);
                            if (auth()->user()->package == 'Bronze') {
                                if ($request->payment == 'wallet') {
                                    $request->session()->put('form_submitted', true);
                                    // 
                                    if ($bonusamount >= auth()->user()->expectedEarning) {
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
                                            'amount' => $bronzeamount,
                                            'package' => 'Discounted Data',
                                            'status' => 'Confirm',
                                            'dayCounter' => 0,
                                        ]);
                                        return back()->with(
                                            'toast_success',
                                            'Transaction Successful !!'
                                        );
                                    }
                                } elseif ($request->payment == 'epin') {
                                    DB::table('transactions')->where('transactionId', $transactionId)->update([
                                        'paymentMethod' => 'epin',

                                    ]);
                                    return back()->with(
                                        'toast_success',
                                        'Transaction Successful !!'
                                    );
                                } elseif ($request->payment == 'promo') {
                                    DB::table('transactions')->where('transactionId', $transactionId)->update([
                                        'paymentMethod' => 'promo',

                                    ]);
                                    return back()->with(
                                        'toast_success',
                                        'Transaction Successful !!'
                                    );
                                } else {
                                    return back()->with(
                                        'toast_error',
                                        'Oops!!, Service Temporarily Unavailable'
                                    );
                                }
                            } elseif (auth()->user()->package == 'Silver') {
                                $request->session()->put('form_submitted', true);

                                if ($request->payment == 'wallet') {

                                    if ($bonusamount >= auth()->user()->expectedEarning) {
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
                                            'amount' => $silveramount,
                                            'package' => 'Discounted Data',
                                            'status' => 'Confirm',
                                            'dayCounter' => 0,
                                        ]);
                                        return back()->with(
                                            'toast_success',
                                            'Transaction Successful !!'
                                        );
                                    }
                                } elseif ($request->payment == 'epin') {
                                    DB::table('transactions')->where('transactionId', $transactionId)->update([
                                        'paymentMethod' => 'epin',

                                    ]);
                                    return back()->with(
                                        'toast_success',
                                        'Transaction Successful !!'
                                    );
                                } elseif ($request->payment == 'promo') {
                                    DB::table('transactions')->where('transactionId', $transactionId)->update([
                                        'paymentMethod' => 'promo',
                                    ]);
                                    return back()->with(
                                        'toast_success',
                                        'Transaction Successful !!'
                                    );
                                } else {
                                    return back()->with(
                                        'toast_error',
                                        'Oops!!, Service Temporarily Unavailable'
                                    );
                                }
                            } elseif (auth()->user()->package == 'Gold') {
                                $request->session()->put('form_submitted', true);

                                if ($request->payment == 'wallet') {

                                    if ($bonusamount >= auth()->user()->expectedEarning) {
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
                                            'amount' => $goldamount,
                                            'package' => 'Discounted Data',
                                            'status' => 'Confirm',
                                            'dayCounter' => 0,
                                        ]);
                                        return back()->with(
                                            'toast_success',
                                            'Transaction Successful !!'
                                        );
                                    }
                                } elseif ($request->payment == 'epin') {
                                    DB::table('transactions')->where('transactionId', $transactionId)->update([
                                        'paymentMethod' => 'epin',

                                    ]);
                                    return back()->with(
                                        'toast_success',
                                        'Transaction Successful !!'
                                    );
                                } elseif ($request->payment == 'promo') {
                                    DB::table('transactions')->where('transactionId', $transactionId)->update([
                                        'paymentMethod' => 'promo',

                                    ]);
                                    return back()->with(
                                        'toast_success',
                                        'Transaction Successful !!'
                                    );
                                } else {
                                    return back()->with(
                                        'toast_error',
                                        'Oops!!, Service Temporarily Unavailable'
                                    );
                                }
                            } elseif (auth()->user()->package == 'Platinum') {
                                $request->session()->put('form_submitted', true);

                                if ($request->payment == 'wallet') {

                                    if ($bonusamount >= auth()->user()->expectedEarning) {
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
                                            'amount' => $platinumamount,
                                            'package' => 'Discounted Data',
                                            'status' => 'Confirm',
                                            'dayCounter' => 0,
                                        ]);
                                        return back()->with(
                                            'toast_success',
                                            'Transaction Successful !!'
                                        );
                                    }
                                } elseif ($request->payment == 'epin') {
                                    DB::table('transactions')->where('transactionId', $transactionId)->update([
                                        'paymentMethod' => 'epin',

                                    ]);
                                    return back()->with(
                                        'toast_success',
                                        'Transaction Successful !!'
                                    );
                                } elseif ($request->payment == 'promo') {
                                    DB::table('transactions')->where('transactionId', $transactionId)->update([
                                        'paymentMethod' => 'promo',

                                    ]);
                                    return back()->with(
                                        'toast_success',
                                        'Transaction Successful !!'
                                    );
                                } else {
                                    return back()->with(
                                        'toast_error',
                                        'Oops!!, Service Temporarily Unavailable'
                                    );
                                }
                            } else {
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->uplineOne,
                                    'sponsorId' => auth()->user()->mySponsorId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $platinumamount,
                                    'package' => 'Discounted Data',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                if ($request->payment == 'wallet') {
                                    $request->session()->put('form_submitted', true);

                                    return back()->with(
                                        'toast_success',
                                        'Transaction Successful !!'
                                    );
                                } elseif ($request->payment == 'epin') {
                                    DB::table('transactions')->where('transactionId', $transactionId)->update([
                                        'paymentMethod' => 'epin',

                                    ]);
                                    return back()->with(
                                        'toast_success',
                                        'Transaction Successful !!'
                                    );
                                } elseif ($request->payment == 'promo') {
                                    DB::table('transactions')->where('transactionId', $transactionId)->update([
                                        'paymentMethod' => 'promo',

                                    ]);
                                    return back()->with(
                                        'toast_success',
                                        'Transaction Successful !!'
                                    );
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
                            DB::table('datapurchases')->where('transactionId', $datapurchasesId)->update([
                                'status' => 'Failed',

                            ]);
                            return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                        }
                    } else {
                        DB::table('transactions')->where('transactionId', $transactionId)->update([
                            'status' => 'Failed',

                        ]);
                        DB::table('datapurchases')->where('transactionId', $datapurchasesId)->update([
                            'status' => 'Failed',

                        ]);
                        return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                    }
                } elseif ($request->network == 'glo' && $balance >= $request->amount) {
                    $transactionId = $this->randomDigit();
                    $datapurchasesId = $transactionId;


                        DB::table('transactions')
                                ->where('userId', auth()->user()->userId)
                                ->insert([
                                    'transactionId' => $transactionId,
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'phoneNumber' => $request->phoneNumber,
                                    'amount' => $request->amount,
                                    'transactionType' => 'Data Share',
                                    'transactionService' => $request->network,
                                    'status' => 'PENDING',
                                    'paymentMethod' => 'wallet',
                                    'Admin' => 'None',

                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);
                            DB::table('datapurchases')
                                ->where('userId', auth()->user()->userId)
                                ->insert([
                                    'transactionId' => $datapurchasesId,
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'phoneNumber' => $request->phoneNumber,
                                    'amount' => $request->amount,
                                    'network' => $request->network,
                                    'product' => $request->amount,
                                    'status' => 'PENDING',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);
                    //  Update User Balance
                    // $user = User::findOrFail(auth()->id());

                    // $user->update([
                    //     'beforeBalance' => $balance,
                    //     'currentBalance' => $balance - $request->amount,
                    // ]);

                    $api = getenv('TELECOM_API_EASYACCESS');
                    $phoneNumber = $request->phoneNumber;
                    $productCode = $request->packageGLO;
                    $amount = $request->amount;

                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://easyaccessapi.com.ng/api/data.php",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => array(
                            'network' => 02,
                            'mobileno' =>  $request->phoneNumber,
                            'dataplan' => $request->packageGLO,
                            'client_reference' => $datapurchasesId,
                            'max_amount_payable' => $request->amount
                        ),
                        CURLOPT_HTTPHEADER => array(
                            "AuthorizationToken: $api",
                            "cache-control: no-cache"
                        ),
                    ));
                    $result = curl_exec($curl);
                    curl_close($curl);
                    $response = json_decode($result);
                    if ($response != null && $response->success != 'false_disabled') {
                        if ($response->status == true && $balance > $request->amount) {
                            // 
                           
                            if ($request->payment == 'wallet') {
                                $request->session()->put('form_submitted', true);
                                // 

                                if ($bonusamount >= auth()->user()->expectedEarning) {
                                    return back()->with('toast_success', 'Transaction Successful !!');
                                } else {
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->mySponsorId,
                                        'sponsorId' => auth()->user()->mySponsorId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bronzeamount,
                                        'package' => 'Discounted Data',
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    return back()->with('toast_success', 'Transaction Successful !!');
                                }
                            } elseif ($request->payment == 'epin') {
                                DB::table('transactions')->where('transactionId', $transactionId)->update([
                                    'epin' => 'epin',

                                ]);
                                return back()->with('toast_success', 'Transaction Successful !!');
                            } elseif ($request->payment == 'promo') {
                                DB::table('transactions')->where('transactionId', $transactionId)->update([
                                    'promo' => 'promo',

                                ]);
                                return back()->with('toast_success', 'Transaction Successful !!');
                            } else {
                                DB::table('transactions')->where('transactionId', $transactionId)->update([
                                    'status' => 'Failed',

                                ]);
                                DB::table('datapurchases')->where('transactionId', $datapurchasesId)->update([
                                    'status' => 'Failed',

                                ]);
                                return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                            }
                        } else {
                            DB::table('transactions')->where('transactionId', $transactionId)->update([
                                'status' => 'Failed',

                            ]);
                            DB::table('datapurchases')->where('transactionId', $datapurchasesId)->update([
                                'status' => 'Failed',

                            ]);
                            return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                        }
                    } else {
                        DB::table('transactions')->where('transactionId', $transactionId)->update([
                            'status' => 'Failed',

                        ]);
                        DB::table('datapurchases')->where('transactionId', $datapurchasesId)->update([
                            'status' => 'Failed',

                        ]);
                        return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                    }
                } elseif ($request->network == 'airtel' && $balance >= $request->amount) {
                    $transactionId = $this->randomDigit();
                    $datapurchasesId = $transactionId;


                            DB::table('transactions')
                                ->where('userId', auth()->user()->userId)
                                ->insert([
                                    'transactionId' => $transactionId,
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'phoneNumber' => $request->phoneNumber,
                                    'amount' => $request->amount,
                                    'transactionType' => 'Data Share',
                                    'transactionService' => $request->network,
                                    'status' => 'PENDING',
                                    'paymentMethod' => 'wallet',
                                    'Admin' => 'None',

                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);
                            DB::table('datapurchases')
                                ->where('userId', auth()->user()->userId)
                                ->insert([
                                    'transactionId' => $datapurchasesId,
                                    'userId' => auth()->user()->userId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'phoneNumber' => $request->phoneNumber,
                                    'amount' => $request->amount,
                                    'network' => $request->network,
                                    'product' => $request->amount,
                                    'status' => 'PENDING',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);
                    // $api = getenv('TELECOM_API');
                    // $phoneNumber = $request->phoneNumber;
                    // $productCode = $request->packageAirtel;
                    // $amount = $request->amount;
                    // $ch = curl_init();
                    // curl_setopt(
                    //     $ch,
                    //     CURLOPT_URL,
                    //     "https://mobileone.ng/api/v2/data_card/?api_key={$api}&product_code={$productCode}&phone={$phoneNumber}&callback=mobileone.ng/callback.php"
                    // );
                    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    // curl_setopt($ch, CURLOPT_POST, true);
                    // curl_setopt($ch, CURLOPT_POST, true);
                    // $result = curl_exec($ch);
                    // $response = json_decode($result);

                    $api = getenv('TELECOM_API_EASYACCESS');
                    $phoneNumber = $request->phoneNumber;
                    $productCode = $request->packageAirtel;
                    $amount = $request->amount;
                    $transactionId = $this->randomDigit();
                    $datapurchasesId = $transactionId;
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://easyaccessapi.com.ng/api/data.php",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => array(
                            'network' => 03,
                            'mobileno' =>  $request->phoneNumber,
                            'dataplan' => $request->packageAirtel,
                            'client_reference' => $datapurchasesId,
                            'max_amount_payable' => $request->amount
                        ),
                        CURLOPT_HTTPHEADER => array(
                            "AuthorizationToken: $api",
                            "cache-control: no-cache"
                        ),
                    ));
                    $result = curl_exec($curl);
                    curl_close($curl);
                    $response = json_decode($result);
                    // return [$response->client_reference, $datapurchasesId,  $transactionId];

                    if ($response != null && $response->success != 'false_disabled') {
                        if ($response->status == true && $balance > $request->amount) {

                            //  Update User Balance
                            // $user = User::findOrFail(auth()->id());

                            // $user->update([
                            //     'beforeBalance' => $balance,
                            //     'currentBalance' => $balance - $request->amount,
                            // ]);
                            // 
                            if ($request->payment == 'wallet') {
                                $request->session()->put('form_submitted', true);

                                // 
                                if ($bonusamount >= auth()->user()->expectedEarning) {
                                    return back()->with('toast_success', 'Transaction Successful !!');
                                } else {
                                    bonus::create([
                                        'bonusId' => $this->randomDigit(),
                                        'sponsor' => auth()->user()->mySponsorId,
                                        'sponsorId' => auth()->user()->mySponsorId,
                                        'username' => auth()->user()->username,
                                        'email' => auth()->user()->email,
                                        'amount' => $bronzeamount,
                                        'package' => 'Discounted Data',
                                        'status' => 'Confirm',
                                        'dayCounter' => 0,
                                    ]);
                                    return back()->with('toast_success', 'Transaction Successful !!');
                                }
                            } elseif ($request->payment == 'epin') {
                                DB::table('transactions')->where('transactionId', $transactionId)->update([
                                    'paymentMethod' => 'epin',

                                ]);
                                return back()->with('toast_success', 'Transaction Successful !!');
                            } elseif ($request->payment == 'promo') {
                                DB::table('transactions')->where('transactionId', $transactionId)->update([
                                    'paymentMethod' => 'promo',

                                ]);
                                return back()->with('toast_success', 'Transaction Successful !!');
                            } else {
                                DB::table('transactions')->where('transactionId', $transactionId)->update([
                                    'status' => 'Failed',

                                ]);
                                DB::table('datapurchases')->where('transactionId', $datapurchasesId)->update([
                                    'status' => 'Failed',

                                ]);
                                return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                            }
                        } else {
                            DB::table('transactions')->where('transactionId', $transactionId)->update([
                                'status' => 'Failed',

                            ]);
                            DB::table('datapurchases')->where('transactionId', $datapurchasesId)->update([
                                'status' => 'Failed',

                            ]);
                                return "asdsddsds";
                            return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                        }
                    } else {
                    
                        DB::table('transactions')->where('transactionId', $transactionId)->update([
                            'status' => 'Failed',

                        ]);
                        DB::table('datapurchases')->where('transactionId', $datapurchasesId)->update([
                            'status' => 'Failed',

                        ]);
                        return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                    }
                } elseif ($request->network == '9mobile' && $balance >= $request->amount) {
                    $transactionId = $this->randomDigit();
                    $datapurchasesId = $transactionId;

                        DB::table('datapurchases')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $datapurchasesId,
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => $request->phoneNumber,
                                'amount' => $request->amount,
                                'network' => $request->network,
                                'product' => $request->amount,
                                'status' => 'PENDING',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);

                        DB::table('transactions')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $transactionId,
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => $request->phoneNumber,
                                'amount' => $request->amount,
                                'transactionType' => 'Data Share',
                                'transactionService' => $request->network,
                                'status' => 'PENDING',
                                'paymentMethod' => 'wallet',
                                'Admin' => 'None',

                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);
                    //  Update User Balance
                    // $user = User::findOrFail(auth()->id());

                    // $user->update([
                    //     'beforeBalance' => $balance,
                    //     'currentBalance' => $balance - $request->amount,
                    // ]);

                    // $api = getenv('TELECOM_API');
                    // $phoneNumber = $request->phoneNumber;
                    // $productCode = $request->package9MOBILE;
                    // $amount = $request->amount;
                    // $ch = curl_init();
                    // curl_setopt(
                    //     $ch,
                    //     CURLOPT_URL,
                    //     "https://mobileone.ng/api/v2/data_card/?api_key={$api}&product_code={$productCode}&phone={$phoneNumber}&callback=mobileone.ng/callback.php"
                    // );
                    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    // curl_setopt($ch, CURLOPT_POST, true);
                    // curl_setopt($ch, CURLOPT_POST, true);
                    // $result = curl_exec($ch);
                    // $response = json_decode($result);

                    $api = getenv('TELECOM_API_EASYACCESS');
                    $phoneNumber = $request->phoneNumber;
                    $productCode = $request->package9MOBILE;
                    $amount = $request->amount;

                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://easyaccessapi.com.ng/api/data.php",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => array(
                            'network' => 04,
                            'mobileno' =>  $request->phoneNumber,
                            'dataplan' => $request->package9MOBILE,
                            'client_reference' => $datapurchasesId,
                            'max_amount_payable' => $request->amount
                        ),
                        CURLOPT_HTTPHEADER => array(
                            "AuthorizationToken: $api",
                            "cache-control: no-cache"
                        ),
                    ));
                    $result = curl_exec($curl);
                    curl_close($curl);
                    $response = json_decode($result);

                    if ($response->status == true) {
                        //    
                        

                        if ($request->payment == 'wallet') {
                            $request->session()->put('form_submitted', true);
                            // 
                            if ($bonusamount >= auth()->user()->expectedEarning) {
                                return back()->with('toast_success', 'Transaction Successful !!');
                            } else {
                                bonus::create([
                                    'bonusId' => $this->randomDigit(),
                                    'sponsor' => auth()->user()->mySponsorId,
                                    'sponsorId' => auth()->user()->mySponsorId,
                                    'username' => auth()->user()->username,
                                    'email' => auth()->user()->email,
                                    'amount' => $bronzeamount,
                                    'package' => 'Discounted Data',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
                                return back()->with('toast_success', 'Transaction Successful !!');
                            }
                        } elseif ($request->payment == 'epin') {
                            DB::table('transactions')->where('transactionId', $transactionId)->update([
                                'paymentMethod' => 'epin',

                            ]);
                            return back()->with('toast_success', 'Transaction Successful !!');
                        } elseif ($request->payment == 'promo') {
                            DB::table('transactions')->where('transactionId', $transactionId)->update([
                                'paymentMethod' => 'promo',

                            ]);
                            return back()->with('toast_success', 'Transaction Successful !!');
                        } else {
                            DB::table('transactions')->where('transactionId', $transactionId)->update([
                                'status' => 'Failed',

                            ]);
                            DB::table('datapurchases')->where('transactionId', $datapurchasesId)->update([
                                'status' => 'Failed',

                            ]);
                            return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                        }
                    } else {
                        DB::table('transactions')->where('transactionId', $transactionId)->update([
                            'status' => 'Failed',

                        ]);
                        DB::table('datapurchases')->where('transactionId', $datapurchasesId)->update([
                            'status' => 'Failed',

                        ]);
                        return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                    }
                } else {
                    return back()->with('toast_error', 'Contact Admin');
                }
            }
        }
    }
}
