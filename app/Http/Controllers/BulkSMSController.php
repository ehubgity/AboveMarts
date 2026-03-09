<?php

namespace App\Http\Controllers;

use App\Models\Smstransaction;
use Illuminate\Support\Str;
use App\Models\ContactGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\ContactImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class BulkSMSController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index()
    {
        // return the default page
        $data['user'] = $user = Auth::user();
        $data['contacts'] = ContactGroup::where('userId', $user->id)
            ->latest()
            ->get();
        return view('bulksms.index', $data);
    }
    public function contactGroup()
    {
        // return the contact group page
        $data['user'] = $user = Auth::user();
        $data['contacts'] = ContactGroup::where('userId', $user->id)
            ->latest()
            ->get();
        return view('bulksms.contact_group', $data);
    }
    public function viewGroup($id)
    {

        // return the contact group page
        $data['user'] = $user = Auth::user();
        $data['contact'] = $contact = ContactGroup::find($id);
        if ($contact->type == 'import') {
            $data['contacts'] = implode(',', unserialize($contact->contacts));
        } else {
            $data['contacts'] = $contact->contacts;
        }
        return view('bulksms.view_group', $data);
    }
    public function viewDetails($id)
    {
        // return the contact group page
        $data['user'] = $user = Auth::user();
        $data['transaction'] = $contact = Smstransaction::find($id);

        return view('bulksms.view_details', $data);
    }
    public function transactions()
    {
        // rethr the transactions page
        $data['user'] = $user = Auth::user();
        $data['transactions'] = Smstransaction::where('userId', $user->id)
            ->latest()
            ->get();
        return view('bulksms.transactions', $data);
    }

    public function saveContacts(Request $rq)
    {
        // function to save contacts for future SMS transactions
        try {
            $user = Auth::user();

            $this->validate($rq, [
                'name' => 'required',
                'description' => 'required',
                // 'contacts' => 'required',
            ]);

            $data = $rq->all();
            // dd($data);
            $data['userId'] = $user->id;
            // for imported contacts through excel and csv.
            if ($rq->has('import_file')) {
                $contacts = Excel::toArray(
                    new ContactImport($user->id, $rq->sender_name, $rq->message),
                    $rq->import_file
                )[0];
                $contact = [];

                foreach ($contacts as $subContact) {
                    foreach ($subContact as $value) {
                        if (Str::length($value) == 10) {
                            $value = "0" . $value;
                            $contact[] = $value;
                        } elseif (
                            Str::length($value) == 11 ||
                            Str::length($value) == 13 ||
                            Str::length($value) == 14
                        ) {
                            $contact[] = $value;
                        } else {
                            $value = null;
                        }
                    }
                }

                $data['contacts'] = serialize($contact);

                ContactGroup::create([
                    'userId' => $user->id,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'name' => $data['name'],
                    'contacts' => $data['contacts'],
                    'type' => 'import',
                ]);
            } else {
                //for manually typed contacts.
                ContactGroup::create([
                    'userId' => $user->id,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'name' => $data['name'],
                    'contacts' => $data['contacts'],
                    'type' => 'manual',
                ]);
                // ContactGroup::create($data);
            }

            return redirect()
                ->back()
                ->with('message', 'Contacts created successfully');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function deleteGroup($id)
    {
        // function to delete created contact group.
        $contact = ContactGroup::find($id);
        $contact->delete();
        return redirect()
            ->back()
            ->with("message", "Contact group deleted succcesfully!");
    }

    public function submitSMSForm(Request $rq)
    {
        // dd($rq->all());
        //function to get the details of the SMS about to send
        $this->validate($rq, [
            'sender_name' => 'required',
            'contact_type' => 'required',
            'message' => 'required',
            'message_type' => 'required',
        ]);
        // dd($rq->all());

        $user = Auth::user();
        $expenses = DB::table('transactions')
            ->where('userId', $user->userId)
            ->where('transactionType', '!=', 'Deposit')
            ->where('status', 'CONFIRM')
            ->sum('amount');

        $walletamount = DB::table('funds')
            ->where('userId', $user->userId)
            ->where('status', 'success')
            ->sum('amount');

        $balance = $walletamount - $expenses;

        // dd($rq->all());
        if ($rq->contact_type == 'import_file') {
            $contacts = Excel::toArray(
                new ContactImport($user->id, $rq->sender_name, $rq->message),
                $rq->import_file
            )[0];
            $contact = [];
            $contact_count = 0;
            foreach ($contacts as $subContact) {
                foreach ($subContact as $value) {
                    if (Str::length($value) == 10) {
                        $value = "0" . $value;
                        $contact[] = $value;
                        $contact_count += 1;
                    } elseif (
                        Str::length($value) == 11 ||
                        Str::length($value) == 13 ||
                        Str::length($value) == 14
                    ) {
                        $contact[] = $value;
                        $contact_count += 1;
                    } else {
                        $value = null;
                    }
                }
            }
            $unique_contact = array_unique($contact);
            $formatted_contacts = implode(',', $unique_contact);
        } elseif ($rq->contact_type == 'select_group') {
            $contact = ContactGroup::find($rq->selected_group);
            if ($contact->type == 'manual') {
                $rq_contacts = $contact->contacts;
                $contacts = explode(',', $rq_contacts);
                // dd($contacts);
                $r_contacts = [];
                $contact_count = 0;
                foreach ($contacts as $value) {
                    if (Str::length($value) == 10) {
                        $value = "0" . $value;
                        $r_contacts[] = $value;
                        $contact_count += 1;
                    } elseif (
                        Str::length($value) == 11 ||
                        Str::length($value) == 13 ||
                        Str::length($value) == 14
                    ) {
                        $r_contacts[] = $value;
                        $contact_count += 1;
                    } else {
                        $value = null;
                    }
                }
                $unique_contact = array_unique($r_contacts);
                $formatted_contacts = implode(',', $unique_contact);
            } else {
                $contacts = unserialize($contact->contacts);

                $contact = [];
                $contact_count = 0;
                foreach ($contacts as $value) {
                    if (Str::length($value) == 10) {
                        $value = "0" . $value;
                        $contact[] = $value;
                        $contact_count += 1;
                    } elseif (
                        Str::length($value) == 11 ||
                        Str::length($value) == 13 ||
                        Str::length($value) == 14
                    ) {
                        $contact[] = $value;
                        $contact_count += 1;
                    } else {
                        $value = null;
                    }
                }

                $unique_contact = array_unique($contact);
                $formatted_contacts = implode(',', $unique_contact);
            }
        } else {
            $rq_contacts = $rq->contact_field;
            $contacts = explode(',', $rq_contacts);
            // dd($contacts);
            $r_contacts = [];
            $contact_count = 0;
            foreach ($contacts as $value) {
                if (Str::length($value) == 10) {
                    $value = "0" . $value;
                    $r_contacts[] = $value;
                    $contact_count += 1;
                } elseif (
                    Str::length($value) == 11 ||
                    Str::length($value) == 13 ||
                    Str::length($value) == 14
                ) {
                    $r_contacts[] = $value;
                    $contact_count += 1;
                } else {
                    $value = null;
                }
            }
            $unique_contact = array_unique($r_contacts);
            $formatted_contacts = implode(',', $unique_contact);
        }
        //calculate the charge before sending as a second validation for the amount to be charged, 160 words per page and get the total numnber of recipients.

        //check the user balance
        $message_count = intval(Str::length($rq->message) / 160);
        if ($message_count == 0) {
            $message_count = 1;
        }
        $count_recipient = count(explode(',', $formatted_contacts));
        // For the admin charge (3.75), we can create a column in the admin table and name it admin_bulksms_charge, so the charge can be called like this:
        // $charge = User::find('admin_user_id')->admin_bulksms_charge;

        $real_amount = number_format(3.75 * intval($count_recipient) * $message_count, 2); //3.75 should be changed to the admin specified amount.

        // dd($rq->all(),$real_amount,$formatted_contacts);

        // dd($real_amount,$contact_count, $rq->all());
        if ($balance < $real_amount) {
            $response = [
                'success' => false,
                'message' => 'Insufficient Funds!',
            ];

            return response()->json($response);
        }

        // dd(count($count_recipient));
        $response = [
            'success' => true,
            'message' => 'Details Fetched!',
            'sms' => $rq->message,
            'contacts' => $formatted_contacts,
            'amount' => $real_amount,
            'message_type' => $rq->message_type,
            'sender_name' => $rq->sender_name,
            'count_recipient' => $count_recipient,
        ];

        if ($rq->schedule_date) {
            $response['schedule'] = $rq->schedule_date . ' ' . $rq->schedule_time;
        }

        // Now $response contains all the common data, and you conditionally add 'schedule_date' if needed.

        return response()->json($response);
    }

    public function sendSMS2(Request $rq)
    {
        // dd($rq->all());

        $username = env('SMS_USERNAME');
        $password = env('SMS_PASSWORD');
        $sender = $rq->sender_name;
        $recipient = $rq->contacts;
        $message = $rq->message;
        $amount = $rq->amount;
        $message_type = $rq->message_type;
        return $recipient;
        // THE API URL with parameters
        if ($rq->schedule) {
            $schedule = $rq->schedule;
            // $apiUrl = "http://www.estoresms.com/smsapi.php?username=$username&password=$password&sender=$sender&recipient=$recipient&message=$message&dnd=true";
            $apiUrl = "http://www.estoresms.com/smsapi.php?username=$username&password=$password&sender=$sender&recipient=$recipient&message=$message&schedule=$schedule";
        } else {
            $apiUrl = "http://www.estoresms.com/smsapi.php?username=$username&password=$password&sender=$sender&recipient=$recipient&message=$message";
        }

        try {
            $response = Http::get($apiUrl);
            $statusCode = $response->status();
            $responseData = $response->body();
            $responseCode = abs($response->json());
            //try and save all the response in txt file for references.
            file_put_contents(
                __DIR__ . '/smslog.txt',
                json_encode($responseData, JSON_PRETTY_PRINT),
                FILE_APPEND
            );

            // dd($response, $statusCode,  $responseData, $responseCode);

            if ($statusCode == 200 && $responseCode == 0) {
                if ($rq->schedule) {
                    $title = 'Successful Scheduled Bulk SMS';
                    $details =
                        'Scheduled Bulk SMS to be sent to ' .
                        $recipient .
                        ' by ' .
                        $rq->schedule .
                        ', Amount: NGN' .
                        $amount .
                        '. Message: ' .
                        $message;
                } else {
                    $title = 'Successful Instant Bulk SMS';
                    $details =
                        'Bulk SMS sent to ' .
                        $recipient .
                        ', Amount: NGN' .
                        $amount .
                        '. Message: ' .
                        $message;
                }
                //save the record for succesfully sent SMS
                $this->create_transaction(
                    $title,
                    $details,
                    $sender,
                    $message,
                    $recipient,
                    $amount,
                    1,
                    $statusCode,
                    null,
                    $message_type
                );
                $response = [
                    'success' => true,
                    'message' => 'Sent Successfully!',
                ];
                return response()->json($response);
            } else {
                $statusCode = 0;
                $title = "Failed Bulk SMS";
                $details =
                    'Failed Bulk SMS, recipient: ' .
                    $recipient .
                    ' Amount: NGN0.00, Message: ' .
                    $message;
                //save the record for non successfully sent SMS due to error from the API
                $this->create_transaction(
                    $title,
                    $details,
                    $sender,
                    $message,
                    $recipient,
                    0,
                    0,
                    $statusCode,
                    null,
                    $message_type
                );
                $response = [
                    'success' => false,
                    'message' => 'Unable to send SMS, Try again later!',
                ];
                return response()->json($response);
            }
        } catch (\Exception $e) {
            $statusCode = 0;
            // Handle any exceptions or errors here
            $title = "Failed Bulk SMS";
            $details =
                'Failed Bulk SMS, recipient: ' .
                $recipient .
                ' Amount: NGN0.00, Message: ' .
                $message;
            // Save the record for non successully sent SMS due to an internal error from the application.
            $this->create_transaction(
                $title,
                $details,
                $sender,
                $message,
                $recipient,
                0,
                0,
                $statusCode,
                null,
                $message_type
            );

            $response = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            return response()->json($response);
        }
    }
    public function sendSMS($sender, $recipient, $message, $amount, $message_type)
    {
        // dd($rq->all());

        $username = env('SMS_USERNAME');
        $password = env('SMS_PASSWORD');

        // THE API URL with parameters

        $apiUrl = "http://www.estoresms.com/smsapi.php?username=$username&password=$password&sender=$sender&recipient=$recipient&message=$message";

        try {
            $response = Http::get($apiUrl);
            $statusCode = $response->status();
            $responseData = $response->body();
            $responseCode = abs($response->json());
            //try and save all the response in txt file for references.
            file_put_contents(
                __DIR__ . '/smslog.txt',
                json_encode($responseData, JSON_PRETTY_PRINT),
                FILE_APPEND
            );

            // dd($response, $statusCode,  $responseData, $responseCode);

            if ($statusCode == 200 && $responseCode == 0) {
                $title = 'Successful Instant Bulk SMS';
                $details =
                    'Bulk SMS sent to ' .
                    $recipient .
                    ', Amount: NGN' .
                    $amount .
                    '. Message: ' .
                    $message;

                //save the record for succesfully sent SMS
                $this->create_transaction(
                    $title,
                    $details,
                    $sender,
                    $message,
                    $recipient,
                    $amount,
                    1,
                    $statusCode,
                    null,
                    $message_type
                );
                $response = [
                    'success' => true,
                    'message' => 'Sent Successfully!',
                ];
                return response()->json($response);
            } else {
                $statusCode = 0;
                $title = "Failed Bulk SMS";
                $details =
                    'Failed Bulk SMS, recipient: ' .
                    $recipient .
                    ' Amount: NGN0.00, Message: ' .
                    $message;
                //save the record for non successfully sent SMS due to error from the API
                $this->create_transaction(
                    $title,
                    $details,
                    $sender,
                    $message,
                    $recipient,
                    0,
                    0,
                    $statusCode,
                    null,
                    $message_type
                );
                $response = [
                    'success' => false,
                    'message' => 'Unable to send SMS, Try again later!',
                ];
                return response()->json($response);
            }
        } catch (\Exception $e) {
            $statusCode = 0;
            // Handle any exceptions or errors here
            $title = "Failed Bulk SMS";
            $details =
                'Failed Bulk SMS, recipient: ' .
                $recipient .
                ' Amount: NGN0.00, Message: ' .
                $message;
            // Save the record for non successully sent SMS due to an internal error from the application.
            $this->create_transaction(
                $title,
                $details,
                $sender,
                $message,
                $recipient,
                0,
                0,
                $statusCode,
                null,
                $message_type
            );

            $response = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            return response()->json($response);
        }
    }

    public function resendSMS($id)
    {
        $tranx = Smstransaction::find($id);
        // dd($tranx);
        $the_work = $this->sendSMS(
            $tranx->sender,
            $tranx->recipient,
            $tranx->message,
            $tranx->amount,
            $tranx->message_type
        );

        if ($the_work == true) {
            //sms sent successfully
            return redirect()
                ->back()
                ->with('message', 'Bulk SMS sent successfully!');
        } elseif ($the_work == false) {
            //sms not sent due to an error encountered from the API
            return redirect()
                ->back()
                ->with('error', 'Error encountered while sending SMS!');
        } else {
            // INTERNAL ERROR FROM ME
            dd($the_work);
        }
    }

    public function create_transaction(
        $title,
        $details,
        $sender,
        $message,
        $recipient,
        $amount,
        $status,
        $statusCode,
        $schedule,
        $message_type
    ) {
        $user = Auth::user();
        // dd( $title, $details, $sender, $message, $recipient, $amount, $status, $statusCode, $schedule, $message_type);
        //create the Smstransaction
        $tranx = Smstransaction::create([
            'userId' => $user->id,
            'title' => $title,
            'description' => $details,
            'sender' => $sender,
            'message' => $message,
            'recipient' => $recipient,
            'amount' => $amount,
            'status' => $status,
            'status_code' => $statusCode,
            'scheduled_for' => $schedule,
            'before' => $user->balance,
            'message_type' => $message_type,

        ]);
        //charging the user
        // $user->balance -= $amount;
        // $user->save();
        // $tranx->after = $user->balance;
        // $tranx->save();

        DB::table('transactions')
            ->where('userId', $user->userId)
            ->insert([
                'transactionId' => $this->randomDigit(),
                'userId' => $user->userId,
                'username' => $user->username,
                'email' => $user->email,
                'phoneNumber' => $user->phoneNumber,
                'amount' => $amount,
                'transactionType' => 'SMS',
                'transactionService' => 'SMS',
                'status' => 'CONFIRM',
                'paymentMethod' => 'wallet',
                'Admin' => 'None',
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ]);
    }
    public function randomDigit()
    {
        $pass = substr(str_shuffle("01234561089abcDEfadsasfdasdfasdsfa3425542FGHIJnostXYZ"), 0, 30);
        return $pass;
    }
}
