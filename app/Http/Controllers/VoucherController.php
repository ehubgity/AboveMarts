<?php

namespace App\Http\Controllers;

use App\Models\bonus;
use App\Http\Controllers\Networking;
use App\Http\Controllers\downline;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Voucher;
use Illuminate\Support\Str;


class VoucherController extends Controller
{
    public function index(Request $request)
    {

        $id = $request->id;
        if (isset($id)) {
            DB::table('users')
                ->where('userID', $id)
                ->update(['emailVerified' => 'YES']);
            return view('auth.giveawaylogin');
        } else {
            return view("auth.giveawaylogin");
        }
    }

    public function create_voucher()
    {
        // if(Auth::user()->email == 'fasanyafemi@gmail.com' || Auth::user()->email == 'steve.com') {
        $data['user'] = Auth::user();
        return view('voucher.create-voucher', $data);

        // }
    }
    
     public function manage_vouchers()
    {
        // if(Auth::user()->email == 'fasanyafemi@gmail.com' || Auth::user()->email == 'steve.com') {
        $data['user'] = Auth::user();
        $data['vouchers'] = Voucher::where('id', '!=', 0)->latest()->get();
        $data['voucher_rate'] = Voucher::find(0)->price;
        return view('voucher.manage_vouchers', $data);
  // }
    }
    
    
    
    public function my_vouchers()
    {
        $data['user'] = $user = Auth::user();
        $data['vouchers'] = Voucher::where('user_id', $user->id)->get();
        return view('voucher.my-vouchers', $data);

        
    }
    public function store_voucher(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            // 'voucher' => 'required',
            'price' => ' required',
        ]);
        if ($validator->fails()) {
            return back()
                ->with('toast_error', $validator->messages()->all()[0])
                ->withInput();
        }

        if (!empty($request->vouchers)) {
            $vouchersArray = explode(',', $request->vouchers);
        }
        if (!empty($request->voucher)) {
            $vouchersArray[] = $request->voucher;
        }
        $vouchersArray = array_unique($vouchersArray);

        foreach ($vouchersArray as $voucher) {
            if ($voucher !== '') {
                Voucher::create([
                    'voucher' => $voucher,
                    'price' => $request->price,
                    'creator_id' => Auth::user()->id
                ]);
            }
        }
        return redirect()->back()->with('message', 'Voucher Created Successfully');
    }

    public function buy_voucher()
    {
        $data['vouchers']  = Voucher::where('id', '!=', 0)->latest()->get();
        $uniquePrices = Voucher::distinct()
            ->pluck('price');

        // Convert the collection to an array (if needed)
        $uniquePricesArray = $uniquePrices->toArray();

        // Optionally, you can assign this to $data['vouchers'] if you need it there
        $data['prices'] = $uniquePricesArray;
        
        return view('voucher.buy-voucher', $data);
    }
    
      public function purchase_voucher(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'unit' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1',
            ]);

if ($validator->fails()) {
    $errors = $validator->messages()->all();
    return back()
        ->with('toast_error', $errors ? $errors[0] : 'Validation failed')
        ->withInput();
}

        if ($validator->fails()) {
            return back()
                ->with('toast_error', $validator->messages()->all()[0])
                ->withInput();
        }
        $user = Auth::user();
       

        $available_voucher = Voucher::where('id', '!=', 0)->where('price', $request->price)->where('status',0)->get();
        $dollar_rate = Voucher::find(0)->price;
        //check the user balance
        $totalPrice = $request->unit * $request->price *  $dollar_rate;
        if($user->package == "Bronze") {
            $totalPrice -= 0.05 * $totalPrice;
        }
        elseif($user->package == "Silver") {
            $totalPrice -= 0.1 * $totalPrice;
        }
        elseif($user->package == "Gold") {
            $totalPrice -= 0.15 * $totalPrice;
        }
        elseif($user->package == "Platinum") {
            $totalPrice -= 0.2 * $totalPrice;
        }
        else {
            $totalPrice = $totalPrice;
        }
        $owner = Auth::user();
             $expenses = DB::table('transactions')
                ->where('userId', $user->userId)
                ->where('transactionType', '!=', 'Deposit')
                 ->where('status', 'CONFIRM')
                ->sum('amount');
            $capital = DB::table('funds')
                ->where('userId', $user->userId)
                ->where('status', 'success')
                ->sum('amount');
            $bonusamount = DB::table('bonuses')
                ->where('sponsor', $user->mySponsorId)
                ->sum('amount');
            $balance = $capital + 0 - $expenses;
             if($balance < $totalPrice){
                
                return redirect()->back()->with('error', 'Insufficient Balance')
                ->withInput();
               
            }
              if(count($available_voucher) < $request->unit) {
                return redirect()->back()->with('error','Insufficient Vouchers, try again later');
            }
            
             $transactionId = $this->randomDigit();
                    $transactionServiceId = $this->randomDigit();
                    
                 
                    DB::table('transactions')->insert([
                        'transactionId' => $transactionId,
                        'userId' => $user->userId,
                        'username' => $user->username,
                        'email' => $user->email,
                        'phoneNumber' => $user->phoneNumber,
                        'amount' => $totalPrice,
                        'transactionType' => 'Abovefinex Voucher',
                        'transactionService' => 'Abovefinex Voucher Purchase',
                        'status' => 'CONFIRM',
                        'paymentMethod' => 'wallet',
                        'Admin' => 'None',
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s'),
                    ]);
                    
                                bonus::create([
                                    'bonusId' => $transactionId,
                                    'sponsor' => $owner->username,
                                    'sponsorId' => $owner->username,
                                    'username' => $owner->username,
                                    'email' => $owner->email,
                                    'amount' =>  $totalPrice,
                                    'package' => 'Abovefinex Voucher',
                                    'status' => 'Confirm',
                                    'dayCounter' => 0,
                                ]);
        
      
        for($i = 0; $i < $request->unit; $i++) {
            $available_voucher[$i]->status = 1;
            $available_voucher[$i]->user_id = Auth::user()->id;
            $available_voucher[$i]->save();
            
        }
       
        return redirect()->route('my_vouchers')->with('message', 'Voucher Purchased Successfully');
    
    }
    public function fun_giveaway_data()
    {

        $data['user'] = $user = Auth::user();
        $data['user'] = $user = Auth::user();
        $data['active'] = 'data';
        return response()->view('giveaway.create-giveaway', $data);
    }


    public function my_giveaway()
    {
        $data['user'] = $user = Auth::user();

        $data['giveaway'] = GiveAway::where('user_id', $user->id)
            ->latest()->get();
        $data['active'] = 'giveaway';
        return response()->view('giveaway.my-giveaway', $data);
    }
    public function randomDigit()
    {
        $pass = substr(str_shuffle("0123456789abcnost"), 0, 12);
        return $pass;
    }
    //Check this function and do the debitting
    public function createDataGiveaway(Request $request)
    {
        $expenses = DB::table('transactions')
            ->where('userId', auth()->user()->userId)
            ->where('transactionType', '!=', 'Deposit')
            ->where('status', 'CONFIRM')
            ->sum('amount');
        $capital = DB::table('funds')
            ->where('userId', auth()->user()->userId)
            ->where('status', 'success')
            ->sum('amount');
        $bonusamount = DB::table('bonuses')
            ->where('sponsor', auth()->user()->mySponsorId)
            ->sum('amount');
        $balance = $capital + 0 - $expenses;

        $user = Auth::user();

        if ($request->has('part_no') && $request->no_winner > $request->part_no) {
            $response = [
                'success' => false,
                'message' => 'Winner\'s cannot be higher than participants.',
                'auto_refund_status' => 'Nil'
            ];

            return response()->json($response);
        }

        //check balance
        if ($request->giveaway_type == "Data") {
            $amount = $request->no_winner * $request->winner_real_price;
        } else {
            if ($request->giveaway_type == 'Airtime') {
                if ($request->giveaway_type == "Cash") {
                    if ($request->type == 'question') {
                        $amount = $request->no_winner * $request->q_cash_price;
                    } else {
                        $amount = $request->no_winner * $request->raffle_cash_price;
                    }
                } else {
                    if ($request->type == 'question') {
                        $amount = $request->no_winner * $request->q_airtime_price;
                    } else {
                        $amount = $request->no_winner * $request->raffle_airtime_price;
                    }
                }
            } else {
                if ($request->giveaway_type == "Cash") {
                    if ($request->type == 'question') {
                        $amount = $request->no_winner * $request->q_cash_price;
                    } else {

                        $amount = $request->no_winner * $request->raffle_cash_price;
                    }
                } else {
                    if ($request->type == 'question') {
                        $amount = $request->no_winner * $request->question_airtime_price;
                    } else {
                        $amount = $request->no_winner * $request->raffle_airtime_price;
                    }
                }
            }
        }
        //service fee included here

        // dd($amount);

        if ($balance < $amount) {
            $response = [
                'success' => false,
                'message' => 'Insufficient Balance.',
                'auto_refund_status' => 'Nil'
            ];

            return response()->json($response);
        } else {
            $transactionId = $this->randomDigit();
            $transactionServiceId = $this->randomDigit();


            DB::table('transactions')->insert([
                'transactionId' => $transactionId,
                'userId' => auth()->user()->userId,
                'username' => auth()->user()->username,
                'email' => auth()->user()->email,
                'phoneNumber' => auth()->user()->phoneNumber,
                'amount' => $amount,
                'transactionType' => 'Giveaway',
                'transactionService' => 'Giveaway',
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
                'phoneNumber' => auth()->user()->phoneNumber,
                'amount' => 100,
                'transactionType' => 'Service Fee',
                'transactionService' => 'Giveaway',
                'status' => 'CONFIRM',
                'paymentMethod' => 'wallet',
                'Admin' => 'None',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ]);
        }
        // dd($amount,$request->all());
        //Sage check balance here
        // if ($user->balance < $amount || $amount <= 100) {
        //     $response = [
        //         'success' => false,
        //         'message' => 'Insufficient balance for the plan you want to get!',
        //         'auto_refund_status' => 'Nil'
        //     ];

        //     return response()->json($response);
        // }
        $rand = Str::random(5);
        if ($request->type == 'raffle') {
            if ($request->giveaway_type == 'Data') {
                $giveaway = GiveAway::create([
                    'user_id' => $user->id,
                    'slug' => str_replace(' ', '-', $request->name . "-" . $rand),
                    'name' => $request->name,
                    'part_no' => $request->part_no,
                    'no_of_winners' => $request->no_winner,
                    'data_price' => $request->winner_price,
                    'estimated_amount' => $amount,
                    'link' => $request->link,
                    'type' => 'raffle_data',
                    'entryfee' => $request->entryfee,
                    'giveaway_type' => 'data',
                ]);
            } elseif ($request->giveaway_type == "Cash") {
                $giveaway = GiveAway::create([
                    'user_id' => $user->id,
                    'slug' => str_replace(' ', '-', $request->name . "-" . $rand),
                    'name' => $request->name,
                    'part_no' => $request->part_no,
                    'no_of_winners' => $request->no_winner,
                    'link' => $request->link,
                    'airtime_price' => $request->raffle_cash_price,
                    'estimated_amount' => $amount,
                    'entryfee' => $request->entryfee,
                    'type' => 'raffle_cash',
                    'giveaway_type' => 'cash',

                ]);
            } else {
                $giveaway = GiveAway::create([
                    'user_id' => $user->id,
                    'slug' => str_replace(' ', '-', $request->name . "-" . $rand),
                    'name' => $request->name,
                    'part_no' => $request->part_no,
                    'link' => $request->link,
                    'no_of_winners' => $request->no_winner,
                    'airtime_price' => $request->raffle_airtime_price,
                    'estimated_amount' => $amount,
                    'type' => 'raffle_airtime',
                    'entryfee' => $request->entryfee,
                    'giveaway_type' => 'airtime',

                ]);
            }
            $availableNumbers = range(1, $giveaway->part_no);
            $existingNumbers = $giveaway->lucky_numbers ?? [];
            $availableNumbers = array_diff($availableNumbers, $existingNumbers);

            $selectedNumbers = array_rand($availableNumbers, $giveaway->no_of_winners);
            $selectedNumbers = array_values($selectedNumbers);

            // Add 1 to each selected number to shift the range from 0-based to 1-based
            $selectedNumbers = array_map(function ($num) {
                return $num + 1;
            }, $selectedNumbers);

            // Add selected numbers to existing numbers
            $existingNumbers = array_merge($existingNumbers, $selectedNumbers);

            $giveaway->update(['lucky_numbers' => $existingNumbers]);
            $giveaway->update(['lucky_numbers_confirm' => $existingNumbers]);
        } else {
            if ($request->giveaway_type == 'Data') {
                $giveaway = GiveAway::create([
                    'user_id' => $user->id,
                    'slug' => str_replace(' ', '-', $request->name . "-" . $rand),
                    'name' => $request->name,
                    'time' => $request->time,
                    'no_of_winners' => $request->no_winner,
                    'max_winners' => $request->no_winner,
                    'data_price' => $request->winner_price,
                    'estimated_amount' => $amount,
                    'type' => 'question_data',
                    'giveaway_type' => 'data',

                ]);
            } elseif ($request->giveaway_type == 'Cash') {
                $giveaway = GiveAway::create([
                    'user_id' => $user->id,
                    'slug' => str_replace(' ', '-', $request->name . "-" . $rand),
                    'name' => $request->name,
                    'time' => $request->time,
                    'no_of_winners' => $request->no_winner,
                    'max_winners' => $request->no_winner,
                    'airtime_price' => $request->q_cash_price,
                    'estimated_amount' => $amount,
                    'type' => 'question_cash',
                    'giveaway_type' => 'cash',

                ]);
            } else {
                $giveaway = GiveAway::create([
                    'user_id' => $user->id,
                    'slug' => str_replace(' ', '-', $request->name . "-" . $rand),
                    'name' => $request->name,
                    'time' => $request->time,
                    'no_of_winners' => $request->no_winner,
                    'max_winners' => $request->no_winner,
                    'airtime_price' => $request->q_airtime_price,
                    'estimated_amount' => $amount,
                    'type' => 'question_airtime',
                    'giveaway_type' => 'airtime',

                ]);
            }
        }
        $client_reference = $giveaway->slug . "_" . Str::random(5);
        $details = "Giveaway Link : https://vtubiz.com/" . $giveaway->slug . " | Amount: NGN" . $amount;
        // Sage create transactions here
        // $trans_id = $this->create_transaction('Giveaway', $client_reference, $details, 'debit', $amount, $user->id, 1);

        $response = [
            'success' => true,
            'message' => 'Giveaway Created Successfully!',
            'auto_refund_status' => 'Nil'
        ];
        return $response;
    }

    public function saveGiveAwayContacts(Request $request)
    {
        $owner = User::find($giveaway->user_id);
        $user = User::where('userId', $request->user_id)->firstOrFail();
        dd($user, $owner);

        $expenses = DB::table('transactions')
            ->where('userId', $user->userId)
            ->where('transactionType', '!=', 'Deposit')
            ->where('status', 'CONFIRM')
            ->sum('amount');
        $capital = DB::table('funds')
            ->where('userId', $user->userId)
            ->where('status', 'success')
            ->sum('amount');
        $bonusamount = DB::table('bonuses')
            ->where('sponsor', $user->mySponsorId)
            ->sum('amount');
        $balance = $capital + 0 - $expenses;

        $this->validate($request, [
            'giveaway_id' => 'required',
            'user_id' => 'required',
            'name' => 'required',
            'phone' => 'required'
        ]);
        $data['giveaway'] = $giveaway = GiveAway::find($request->giveaway_id);

        if ($giveaway->entryfee > 0) {
            //charge them
            if ($balance < $giveaway->entryfee) {
                $response = [
                    'success' => false,
                    'message' => 'Insufficient Balance.',
                    'auto_refund_status' => 'Nil'
                ];

                return response()->json($response);
            } else {

                $transactionId = $this->randomDigit();
                $transactionServiceId = $this->randomDigit();


                DB::table('transactions')->insert([
                    'transactionId' => $transactionId,
                    'userId' => $user->userId,
                    'username' => $user->username,
                    'email' => $user->email,
                    'phoneNumber' => $user->phoneNumber,
                    'amount' => $giveaway->entryfee,
                    'transactionType' => 'Giveaway Entry',
                    'transactionService' => 'Giveaway Entry',
                    'status' => 'CONFIRM',
                    'paymentMethod' => 'wallet',
                    'Admin' => 'None',
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s'),
                ]);

                bonus::create([
                    'bonusId' => $transactionId,
                    'sponsor' => $owner->username,
                    'sponsorId' => $owner->username,
                    'username' => $owner->username,
                    'email' => $owner->email,
                    'amount' =>  $giveaway->entryfee,
                    'package' => 'Giveaway Entry',
                    'status' => 'Confirm',
                    'dayCounter' => 0,
                ]);
            }
        }
        if (session()->has('participate_' . $giveaway->slug)) {
            return redirect()->back()->with('message', 'You have already participated in this giveaway');
        }
        $part = $data['participant'] = GiveAwayContacts::create([
            'giveaway_id' => $request->giveaway_id,
            'user_id' => $request->user_id,
            'name' => $request->name,
            'phone' => $request->phone
        ]);

        if ($giveaway->type == 'question_data' || $giveaway->type == 'question_airtime' ||  $giveaway->type == 'question_cash') {

            $data['time'] = $time = $giveaway->time;
            // dd($time,$giveaway);
            $data['giveawayQuestions'] = $testQuestions = Question::where('test_id', $giveaway->id)->with('answers')->get();
            // $data['authUserHasPlayedtest'] = $authUserHasPlayedtest = Result::where(['user_id' => $authUser, 'test_id' => $giveaway->id])->get();

            //has user played particular test
            // $wasCompleted = Result::where('user_id', $authUser)->whereIn('test_id', (new GiveAway)->hasTestAttempted())->pluck('test_id')->toArray();

            session()->put('participate_' . $giveaway->slug, $giveaway->slug);
            if ($giveaway->max_winners == 0) {
                return redirect()->back()->with('message', 'Giveaway ended already!');
            }


            return view('giveaway.testpage', $data);
        }
        $existingNumbers = $giveaway->all_numbers ?? [];
        if (count($giveaway->all_numbers ?? []) / $giveaway->part_no == 1) {
            return redirect()->back()->with('message', 'Giveaway Ended Already!');
        }
        if (count($existingNumbers) >= $giveaway->part_no) {
            $data['rand_no'] =  "xxx";
            $data['won'] = 0;
        } else {

            do {
                $randomNumber = mt_rand(1, $giveaway->part_no);
            } while (in_array($randomNumber, $existingNumbers));

            $existingNumbers[] = $randomNumber;
            $data['rand_no'] =  $randomNumber;
            $giveaway->update(['all_numbers' => $existingNumbers]);

            if (in_array($randomNumber, $giveaway->lucky_numbers)) {
                $part->is_win = 1;
                $part->lucky_number = $randomNumber;
                $part->save();
                $data['won'] = 1;
            } else {
                $part->lucky_number = $randomNumber;
                $part->save();
                $data['won'] = 0;
            }
        }
        session()->put('participate_' . $giveaway->slug, $giveaway->slug);
        $data['lucky_winners'] = GiveAwayContacts::where('giveaway_id', $giveaway->id)
            ->where('is_win', 1)->latest()->get();
        return view('giveaway.contest', $data);
    }
    public function giveaway_participants($slug)
    {
        $data['giveaway'] = $giveaway = GiveAway::where('slug', $slug)->first();
        $data['user'] = $user = Auth::user();
        if ($user->id == $giveaway->user_id || $user->email == 'fasanyafemi@gmail.com') {
            $data['participants'] = GiveAwayContacts::where('giveaway_id', $giveaway->id)->latest()->get();
            $data['active'] = 'giveaway';
            return response()->view('giveaway.giveaway_participants', $data);
        } else {
            return redirect()->back()->with('message', "Access Denied");
        }
    }
   
    public function delete_voucher($id) {
        $voucher = Voucher::find($id);
        $voucher->delete();
         return redirect()->back()->with('message', 'Voucher Deleted Successfully!');
    }
    public function updateRate(Request $request) {
        $this->validate($request, ['rate' => 'required']);
        $voucher = Voucher::find(0);
        $voucher->price = $request->rate;
        $voucher->save();
         return redirect()->back()->with('message', "Dollar rate updated successfully");
    }
}
