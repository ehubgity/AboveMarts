<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class rechargeprinting extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('user.rechargeprinting');
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'network' => 'required',
            'quantity' => 'required|numeric|max:40',
            'amount' => 'required|numeric|min:100',
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

        $card100 = 100;
        $card200 = 200;
        $card500 = 500;

        // $bonus = DB::table('bonuses')
        //     ->where('sponsorId', auth()->user()->mySponsorId)
        //     ->sum('amount');
        $balance = $capital - $expenses;
        if ($request->amount == 100) {
            // $realamount = $card100 * $request->quantity;
            
            $realamount = 0;
            
            if($request->network == "mtn"){
                $realamount = ($card100 - ($card100 * 0.005)) * $request->quantity;
            }elseif($request->network == "glo"){
                $realamount = ($card100 - ($card100 * 0.01)) * $request->quantity;

                
            }elseif($request->network == "9mobile"){
                $realamount = ($card100 - ($card100 * 0.04)) * $request->quantity;

                
            }elseif($request->network == "airtel"){
                
                $realamount = ($card100 - ($card100 * 0.01)) * $request->quantity;

            }else{
                return back()->with('toast_error', 'Select Network');

            }

            if ($balance < $realamount) {
                return back()->with('toast_error', 'Insufficient Funds');
            } else {

                if ($request->network == 'None') {
                    return back()->with('toast_error', 'Select Network');
                } else {
                    if (auth()->user()->package == 'Basic') {
                        if ($request->quantity > 2) {
                            return back()->with('toast_error', 'Oops !! Free Member can only print 3 pieces');
                        } else {
                            $cardId = $this->randomDigit16();

                            $response = $this->buyEpins($cardId,  $request->network, $request->amount, $request->quantity);
                            $data = $response['data'];
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
                                    'cost' => $realamount,
                                    'status' => 'PENDING',
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
                                    'status' => 'PENDING',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);

                            foreach ($data['epins'] as $epin) {
                                DB::table('cards')->insert([
                                    'userId' => auth()->user()->userId,
                                    'transactionId' => $this->randomDigit(),
                                    'cardId' => $cardId,
                                    'status' => 'CONFIRM',
                                    'network' => $request->network,
                                    'amount' => $request->amount,
                                    'pin' => $epin['pin'],
                                    'serialNumber' => $epin['serial'],
                                    'created_at' => date('Y-m-d H:i:s')
                                ]);
                            }
                            $data = DB::table('rechargeprintings')
                                ->where('userId', auth()->user()->userId)
                                ->orderBy('id', 'desc')
                                ->first();
                            return redirect()->route('cardprinting', ['id' => $data->transactionId]);
                        }
                    } else {
                        $cardId = $this->randomDigit16();
                        $response = $this->buyEpins($cardId,  $request->network, $request->amount, $request->quantity);
                        $data = $response['data'];
                        // Log::info($response);

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
                                'cost' => $realamount,
                                'quantity' => $request->quantity,
                                'status' => 'PENDING',
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
                                'status' => 'PENDING',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);

                        foreach ($data['epins'] as $epin) {
                            DB::table('cards')->insert([
                                'userId' => auth()->user()->userId,
                                'transactionId' => $this->randomDigit(),
                                'cardId' => $cardId,
                                'status' => 'CONFIRM',
                                'network' => $request->network,
                                'amount' => $request->amount,
                                'pin' => $epin['pin'],
                                'serialNumber' => $epin['serial'],
                                'created_at' => date('Y-m-d H:i:s')

                            ]);
                        }

                        $data = DB::table('rechargeprintings')
                            ->where('userId', auth()->user()->userId)
                            ->orderBy('id', 'desc')
                            ->first();

                        return redirect()->route('cardprinting', ['id' => $data->transactionId]);
                    }
                }
            }
        } else if ($request->amount == 200) {
            // $realamount = $card200 * $request->quantity;
            
                        $realamount = 0;
         if($request->network == "mtn"){
                $realamount = ($card200 - ($card200 * 0.005)) * $request->quantity;
            }elseif($request->network == "glo"){
                $realamount = ($card200 - ($card200 * 0.01)) * $request->quantity;

                
            }elseif($request->network == "9mobile"){
                $realamount = ($card200 - ($card200 * 0.04)) * $request->quantity;

                
            }elseif($request->network == "airtel"){
                
                $realamount = ($card200 - ($card200 * 0.01)) * $request->quantity;

            }else{
                return back()->with('toast_error', 'Select Network');

            }
            if ($balance < $realamount) {
                return back()->with('toast_error', 'Insufficient Funds');
            } else {
                if ($request->network == 'None') {
                    return back()->with('toast_error', 'Select Network');
                } else {
                    if (auth()->user()->package == 'Basic') {
                        if ($request->quantity > 3) {
                            return back()->with('toast_error', 'Oops !! Free Member can only print 3 pieces');
                        } else {
                            $cardId = $this->randomDigit16();
                            $response = $this->buyEpins($cardId,  $request->network, $request->amount, $request->quantity);
                            $data = $response['data'];
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
                                    'cost' => $realamount,
                                    'status' => 'PENDING',
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
                                    'status' => 'PENDING',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);
                            foreach ($data['epins'] as $epin) {
                                DB::table('cards')->insert([
                                    'userId' => auth()->user()->userId,
                                    'transactionId' => $this->randomDigit(),
                                    'cardId' => $cardId,
                                    'status' => 'CONFIRM',
                                    'network' => $request->network,
                                    'amount' => $request->amount,
                                    'pin' => $epin['pin'],
                                    'serialNumber' => $epin['serial'],
                                    'created_at' => date('Y-m-d H:i:s')

                                ]);
                            }
                            $data = DB::table('rechargeprintings')
                                ->where('userId', auth()->user()->userId)
                                ->orderBy('id', 'desc')
                                ->first();
                            return redirect()->route('cardprinting', ['id' => $data->transactionId]);
                        }
                    } else {
                        $cardId = $this->randomDigit16();
                        $response = $this->buyEpins($cardId,  $request->network, $request->amount, $request->quantity);
                        $data = $response['data'];
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
                                'cost' => $realamount,
                                'quantity' => $request->quantity,
                                'status' => 'PENDING',
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
                                'status' => 'PENDING',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);
                        foreach ($data['epins'] as $epin) {
                            DB::table('cards')->insert([
                                'userId' => auth()->user()->userId,
                                'transactionId' => $this->randomDigit(),
                                'cardId' => $cardId,
                                'status' => 'CONFIRM',
                                'network' => $request->network,
                                'amount' => $request->amount,
                                'pin' => $epin['pin'],
                                'serialNumber' => $epin['serial'],
                                'created_at' => date('Y-m-d H:i:s')

                            ]);
                        }
                        $data = DB::table('rechargeprintings')
                            ->where('userId', auth()->user()->userId)
                            ->orderBy('id', 'desc')
                            ->first();
                        return redirect()->route('cardprinting', ['id' => $data->transactionId]);
                    }
                }
            }
        } else if ($request->amount == 500) {

            // $realamount = $card500 * $request->quantity;
                           $realamount = 0;
         if($request->network == "mtn"){
                $realamount = ($card500 - ($card500 * 0.005)) * $request->quantity;
            }elseif($request->network == "glo"){
                $realamount = ($card500 - ($card500 * 0.01)) * $request->quantity;

                
            }elseif($request->network == "9mobile"){
                $realamount = ($card500 - ($card500 * 0.04)) * $request->quantity;

                
            }elseif($request->network == "airtel"){
                
                $realamount = ($card500 - ($card500 * 0.01)) * $request->quantity;


            }else{
                return back()->with('toast_error', 'Select Network');

            }
            if ($balance < $realamount) {
                return back()->with('toast_error', 'Insufficient Funds');
            } else {
                if ($request->network == 'None') {
                    return back()->with('toast_error', 'Select Network');
                } else {

                    if (auth()->user()->package == 'Basic') {
                        if ($request->quantity > 3) {
                            return back()->with('toast_error', 'Oops !! Free Member can only print 3 pieces');
                        } else {
                            $cardId = $this->randomDigit16();
                            $response = $this->buyEpins($cardId,  $request->network, $request->amount, $request->quantity);
                            $data = $response['data'];
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
                                    'cost' => $realamount,
                                    'status' => 'PENDING',
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
                                    'status' => 'PENDING',
                                    "created_at" => date('Y-m-d H:i:s'),
                                    "updated_at" => date('Y-m-d H:i:s'),
                                ]);
                            foreach ($data['epins'] as $epin) {
                                DB::table('cards')->insert([
                                    'userId' => auth()->user()->userId,
                                    'transactionId' => $this->randomDigit(),
                                    'cardId' => $cardId,
                                    'status' => 'CONFIRM',
                                    'network' => $request->network,
                                    'amount' => $request->amount,
                                    'pin' => $epin['pin'],
                                    'serialNumber' => $epin['serial'],
                                    'created_at' => date('Y-m-d H:i:s')

                                ]);
                            }
                            $data = DB::table('rechargeprintings')
                                ->where('userId', auth()->user()->userId)
                                ->orderBy('id', 'desc')
                                ->first();
                            return redirect()->route('cardprinting', ['id' => $data->transactionId]);
                        }
                    } else {
                        $cardId = $this->randomDigit16();
                        $response = $this->buyEpins($cardId,  $request->network, $request->amount, $request->quantity);
                        $data = $response['data'];
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
                                'cost' => $realamount,
                                'quantity' => $request->quantity,
                                'status' => 'PENDING',
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
                                'status' => 'PENDING',
                                "created_at" => date('Y-m-d H:i:s'),
                                "updated_at" => date('Y-m-d H:i:s'),
                            ]);

                        foreach ($data['epins'] as $epin) {
                            DB::table('cards')->insert([
                                'userId' => auth()->user()->userId,
                                'transactionId' => $this->randomDigit(),
                                'cardId' => $cardId,
                                'status' => 'CONFIRM',
                                'network' => $request->network,
                                'amount' => $request->amount,
                                'pin' => $epin['pin'],
                                'serialNumber' => $epin['serial'],
                                'created_at' => date('Y-m-d H:i:s')

                            ]);
                        }

                        $data = DB::table('rechargeprintings')
                            ->where('userId', auth()->user()->userId)
                            ->orderBy('id', 'desc')
                            ->first();
                        return redirect()->route('cardprinting', ['id' => $data->transactionId]);
                    }
                }
            }
        } else {
            return back()->with('toast_error', 'Invalid. Contact Admin');
        }
    }


    private function buyEpins($requestId, $network, $amount, $quantity)
    {
        // Step 1: Authenticate
        $authResponse = Http::post('https://ebills.africa/wp-json/jwt-auth/v1/token', [
            'username' => env('EBILLS_USERNAME'),  // replace this
            'password' => env('EBILLS_PASSWORD'),           // replace this
        ]);

        if ($authResponse->failed() || !isset($authResponse['token'])) {
            return response()->json(['error' => 'Authentication failed'], 401);
        }

        $token = $authResponse['token'];
        $payload = [
            'request_id' => $requestId, // You can use your own unique ID logic
            'service_id' => $network,             // Change to 'glo', 'airtel', etc., as needed
            'value'      => $amount,
            'quantity'   => $quantity,
        ];

        $response = Http::withToken($token)
            ->acceptJson()
            ->post('https://ebills.africa/wp-json/api/v2/epins', $payload);

        if ($response->failed()) {
            return back()->with('errors', 'Failed to buy recharge pin! Contact Admin');
            // throw new \Exception('Failed to buy epin: ' . json_encode($response->json()));
        }

        return $response->json();
    }
}
