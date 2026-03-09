<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\bonus;
use App\Models\User;
use Illuminate\Http\Request;

class datapurchase extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('user.datapurchase');
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
        // $bonus = DB::table('transaction')
        //     ->where('userId', auth()->user()->userId)
        //     ->where('transactionService', 'Commission')
        //     ->sum('amount');
        $balance = $capital - $expenses;
        $commissionamount = $request->amount * (2 / 100);

        if ($balance < $request->amount) {
            return back()->with('toast_error', 'Insufficient Funds');
        } else {
            if ($request->network == 'mtn') {
                $api = getenv('TELECOM_API');
                $phoneNumber = $request->phoneNumber;
                $productCode = $request->packageMTN;
                $amount = $request->amount;
                // return dd($productCode);
                $ch = curl_init();
                curl_setopt(
                    $ch,
                    CURLOPT_URL,
                    "https://smartrecharge.ng/api/v2/directdata/?api_key={$api}&product_code={$productCode}&phone={$phoneNumber}"
                );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POST, true);
                $result = curl_exec($ch);
                $response = json_decode($result);

                if ($response->status == true) {
                    DB::table('datapurchases')
                        ->where('userId', auth()->user()->userId)
                        ->insert([
                            'transactionId' => $this->randomDigit(),
                            'userId' => auth()->user()->userId,
                            'username' => auth()->user()->username,
                            'email' => auth()->user()->email,
                            'phoneNumber' => $request->phoneNumber,
                            'amount' => $request->amount,
                            'network' => $request->network,
                            'product' => $request->amount,
                            'status' => 'CONFIRM',
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        ]);
                    if ($request->payment == 'wallet') {
                        DB::table('transactions')->insert([
                            'transactionId' => $this->randomDigit(),
                            'userId' => auth()->user()->userId,
                            'username' => auth()->user()->username,
                            'email' => auth()->user()->email,
                            'phoneNumber' => $request->phoneNumber,
                            'amount' => $request->amount,
                            'transactionType' => 'Data Purchase',
                            'transactionService' => $request->network,
                            'status' => 'CONFIRM',
                            'paymentMethod' => 'wallet',
                            'Admin' => 'None',
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        ]);
                            //  Update User Balance
                            $user = User::findOrFail(auth()->id());
                            
                            $user->update([
                                    'beforeBalance' => $balance,
                                    'currentBalance' => $balance - $request->amount,
                                ]);
                            

                        if ($bonusamount >= auth()->user()->expectedEarning) {
                            return back()->with('toast_success', 'Transaction Successful !!');
                        } else {
                            bonus::create([
                                'bonusId' => $this->randomDigit(),
                                'sponsor' => auth()->user()->mySponsorId,
                                'sponsorId' => auth()->user()->mySponsorId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'amount' => $commissionamount,
                                'package' => 'Data',
                                'status' => 'Confirm',
                                'dayCounter' => 0,
                            ]);

                            return back()->with('toast_success', 'Transaction Successful !!');
                        }
                    } elseif ($request->payment == 'epin') {
                        DB::table('transactions')->insert([
                            'transactionId' => $this->randomDigit(),
                            'userId' => auth()->user()->userId,
                            'username' => auth()->user()->username,
                            'email' => auth()->user()->email,
                            'phoneNumber' => $request->phoneNumber,
                            'amount' => $request->amount,
                            'transactionType' => 'Data Purchase',
                            'transactionService' => $request->network,
                            'status' => 'CONFIRM',
                            'paymentMethod' => 'epin',
                            'Admin' => 'None',
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        ]);
                        return back()->with('toast_success', 'Transaction Successful !!');
                    } elseif ($request->payment == 'promo') {
                        DB::table('transactions')->insert([
                            'transactionId' => $this->randomDigit(),
                            'userId' => auth()->user()->userId,
                            'username' => auth()->user()->username,
                            'email' => auth()->user()->email,
                            'phoneNumber' => $request->phoneNumber,
                            'amount' => $request->amount,
                            'transactionType' => 'Data Purchase',
                            'transactionService' => $request->network,
                            'status' => 'CONFIRM',
                            'paymentMethod' => 'promo',
                            'Admin' => 'None',

                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        ]);
                        return back()->with('toast_success', 'Transaction Successful !!');
                    } else {
                        return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                    }
                } else {
                    return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                }
            } elseif ($request->network == 'glo') {
                $api = getenv('TELECOM_API');
                $phoneNumber = $request->phoneNumber;
                $productCode = $request->packageGLO;
                $amount = $request->amount;
                $ch = curl_init();
                curl_setopt(
                    $ch,
                    CURLOPT_URL,
                    "https://smartrecharge.ng/api/v2/directdata/?api_key={$api}&product_code={$productCode}&phone={$phoneNumber}"
                );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POST, true);
                $result = curl_exec($ch);
                $response = json_decode($result);

                if ($response->status == true) {
                    
                    DB::table('datapurchases')
                        ->where('userId', auth()->user()->userId)
                        ->insert([
                            'transactionId' => $this->randomDigit(),
                            'userId' => auth()->user()->userId,
                            'username' => auth()->user()->username,
                            'email' => auth()->user()->email,
                            'phoneNumber' => $request->phoneNumber,
                            'amount' => $request->amount,
                            'network' => $request->network,
                            'product' => $request->amount,
                            'status' => 'CONFIRM',
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        ]);
                    if ($request->payment == 'wallet') {
                        DB::table('transactions')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => $request->phoneNumber,
                                'amount' => $request->amount,
                                'transactionType' => 'Data Purchase',
                                'transactionService' => $request->network,
                                'status' => 'CONFIRM',
                                'paymentMethod' => 'wallet',
                                'Admin' => 'None',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);
                             //  Update User Balance
                            $user = User::findOrFail(auth()->id());
                            
                            $user->update([
                                    'beforeBalance' => $balance,
                                    'currentBalance' => $balance - $request->amount,
                                ]);
                            
                        return back()->with('toast_success', 'Transaction Successful !!');
                    } elseif ($request->payment == 'epin') {
                        DB::table('transactions')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => $request->phoneNumber,
                                'amount' => $request->amount,
                                'transactionType' => 'Data Purchase',
                                'transactionService' => $request->network,
                                'status' => 'CONFIRM',
                                'paymentMethod' => 'epin',
                                'Admin' => 'None',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);
                        return back()->with('toast_success', 'Transaction Successful !!');
                    } elseif ($request->payment == 'promo') {
                        DB::table('transactions')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => $request->phoneNumber,
                                'amount' => $request->amount,
                                'transactionType' => 'Data Purchase',
                                'transactionService' => $request->network,
                                'status' => 'CONFIRM',
                                'paymentMethod' => 'promo',
                                'Admin' => 'None',

                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);
                        return back()->with('toast_success', 'Transaction Successful !!');
                    } else {
                        return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                    }
                } else {
                    return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                }
            } elseif ($request->network == 'airtel') {
                $api = getenv('TELECOM_API');
                $phoneNumber = $request->phoneNumber;
                $productCode = $request->packageAirtel;
                $amount = $request->amount;
                $ch = curl_init();
                curl_setopt(
                    $ch,
                    CURLOPT_URL,
                    "https://smartrecharge.ng/api/v2/directdata/?api_key={$api}&product_code={$productCode}&phone={$phoneNumber}"
                );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POST, true);
                $result = curl_exec($ch);
                $response = json_decode($result);
                if ($response->status == true) {
                    DB::table('datapurchases')
                        ->where('userId', auth()->user()->userId)
                        ->insert([
                            'transactionId' => $this->randomDigit(),
                            'userId' => auth()->user()->userId,
                            'username' => auth()->user()->username,
                            'email' => auth()->user()->email,
                            'phoneNumber' => $request->phoneNumber,
                            'amount' => $request->amount,
                            'network' => $request->network,
                            'product' => $request->amount,
                            'status' => 'CONFIRM',
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        ]);
                    if ($request->payment == 'wallet') {
                        DB::table('transactions')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => $request->phoneNumber,
                                'amount' => $request->amount,
                                'transactionType' => 'Data Purchase',
                                'transactionService' => $request->network,
                                'status' => 'CONFIRM',
                                'paymentMethod' => 'wallet',
                                'Admin' => 'None',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);
                             //  Update User Balance
                            $user = User::findOrFail(auth()->id());
                            
                            $user->update([
                                    'beforeBalance' => $balance,
                                    'currentBalance' => $balance - $request->amount,
                                ]);
                            
                        return back()->with('toast_success', 'Transaction Successful !!');
                    } elseif ($request->payment == 'epin') {
                        DB::table('transactions')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => $request->phoneNumber,
                                'amount' => $request->amount,
                                'transactionType' => 'Data Purchase',
                                'transactionService' => $request->network,
                                'status' => 'CONFIRM',
                                'paymentMethod' => 'epin',
                                'Admin' => 'None',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);
                        return back()->with('toast_success', 'Transaction Successful !!');
                    } elseif ($request->payment == 'promo') {
                        DB::table('transactions')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => $request->phoneNumber,
                                'amount' => $request->amount,
                                'transactionType' => 'Data Purchase',
                                'transactionService' => $request->network,
                                'status' => 'CONFIRM',
                                'paymentMethod' => 'promo',
                                'Admin' => 'None',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);
                        return back()->with('toast_success', 'Transaction Successful !!');
                    } else {
                        return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                    }
                } else {
                    return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                }
            } elseif ($request->network == '9mobile') {
                $api = getenv('TELECOM_API');
                $phoneNumber = $request->phoneNumber;
                $productCode = $request->package9MOBILE;
                $amount = $request->amount;
                $ch = curl_init();
                curl_setopt(
                    $ch,
                    CURLOPT_URL,
                    "https://smartrecharge.ng/api/v2/directdata/?api_key={$api}&product_code={$productCode}&phone={$phoneNumber}"
                );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POST, true);
                $result = curl_exec($ch);
                $response = json_decode($result);
                if ($response->status == true) {
                    DB::table('datapurchases')
                        ->where('userId', auth()->user()->userId)
                        ->insert([
                            'transactionId' => $this->randomDigit(),
                            'userId' => auth()->user()->userId,
                            'username' => auth()->user()->username,
                            'email' => auth()->user()->email,
                            'phoneNumber' => $request->phoneNumber,
                            'amount' => $request->amount,
                            'network' => $request->network,
                            'product' => $request->amount,
                            'status' => 'CONFIRM',
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                        ]);
                    if ($request->payment == 'wallet') {
                        DB::table('transactions')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => $request->phoneNumber,
                                'amount' => $request->amount,
                                'transactionType' => 'Data Purchase',
                                'transactionService' => $request->network,
                                'status' => 'CONFIRM',
                                'paymentMethod' => 'wallet',
                                'Admin' => 'None',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);
                             //  Update User Balance
                            $user = User::findOrFail(auth()->id());
                            
                            $user->update([
                                    'beforeBalance' => $balance,
                                    'currentBalance' => $balance - $request->amount,
                                ]);
                            
                        return back()->with('toast_success', 'Transaction Successful !!');
                    } elseif ($request->payment == 'epin') {
                        DB::table('transactions')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => $request->phoneNumber,
                                'amount' => $request->amount,
                                'transactionType' => 'Data Purchase',
                                'transactionService' => $request->network,
                                'status' => 'CONFIRM',
                                'paymentMethod' => 'epin',
                                'Admin' => 'None',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);
                        return back()->with('toast_success', 'Transaction Successful !!');
                    } elseif ($request->payment == 'promo') {
                        DB::table('transactions')
                            ->where('userId', auth()->user()->userId)
                            ->insert([
                                'transactionId' => $this->randomDigit(),
                                'userId' => auth()->user()->userId,
                                'username' => auth()->user()->username,
                                'email' => auth()->user()->email,
                                'phoneNumber' => $request->phoneNumber,
                                'amount' => $request->amount,
                                'transactionType' => 'Data Purchase',
                                'transactionService' => $request->network,
                                'status' => 'CONFIRM',
                                'paymentMethod' => 'promo',
                                'Admin' => 'None',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);
                        return back()->with('toast_success', 'Transaction Successful !!');
                    } else {
                        return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                    }
                } else {
                    return back()->with('toast_error', 'Oops!!, Service Temporarily Unavailable');
                }
            } else {
                return back()->with('toast_error', 'Contact Admin');
            }
        }
        
        
    }
}
