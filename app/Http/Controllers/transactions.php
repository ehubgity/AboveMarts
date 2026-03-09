<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use  App\Models\User;


class transactions extends Controller
{
    public function __construct()
    {
        $this->middleware('subadmin');
    }

    public function index(Request $request)
    {
        $datadeposits = DB::table('transactions')
            ->orderByDesc('id')
            ->paginate(20);

        if (isset($request->confirmid)) {
            DB::table('transactions')
                ->where('transactionId', $request->confirmid)
                ->update([
                    'status' => 'CONFIRM',
                    'Admin' => Auth::guard('admin')->user()->username,
                ]);
            return back();
        } elseif (isset($request->unconfirmid)) {
            DB::table('transactions')
                ->where('transactionId', $request->unconfirmid)
                ->update([
                    'status' => 'PENDING',
                    'Admin' => Auth::guard('admin')->user()->username,
                ]);
            return back();
        } elseif (isset($request->deleteid)) {
            
            $transaction = DB::table('transactions')
                ->where('transactionId', $request->deleteid)
                ->first();
            
            if ($transaction && $transaction->transactionType != 'Deposit') {
                // Update User Balance
                $user = User::where('userId', $transaction->userId)->first();
            
                if ($user) {
                    $user->update([
                        'beforeBalance' => $user->currentBalance,
                        'currentBalance' => $user->currentBalance + $transaction->amount,
                    ]);
                }
            }else{
                 if ($user) {
                    $user->update([
                        'beforeBalance' => $user->currentBalance,
                        'currentBalance' => $user->currentBalance - $transaction->amount,
                    ]);
                }
            }
            
            
           
            DB::table('transactions')
                ->where('transactionId', $request->deleteid)
                ->delete();
            return back();
        } else {
            return view('admin.transactions')->with('datadeposits', $datadeposits);
        }
    }

    public function search(Request $request)
    {
        $datatransactions = DB::table('transactions')
            ->orderByDesc('id')
            ->paginate(20);
        $datadeposits = DB::table('transactions')
            ->orderByDesc('id')
            ->paginate(20);

        $query = $request->input('query');
        if ($query != null) {
            $datas = DB::table('transactions')
                ->where('username', 'LIKE', "%$query%")
                ->orWhere('transactionType', 'LIKE', "%$query%")
                ->orWhere('status', 'LIKE', "%$query%")
                ->orWhere('transactionId', 'LIKE', "%$query%")
                ->orWhere('email', 'LIKE', "%$query%")
                ->orWhere('phoneNumber', 'LIKE', "%$query%")
                ->orWhere('transactionType', 'LIKE', "%$query%")
                ->orderByDesc('id')
                ->get();
            return view('admin.transactions')
                ->with('query', $query)
                ->with('datas', $datas)
                ->with('datatransactions', $datatransactions);
        } else {
            if (isset($request->confirmid)) {
                DB::table('transactions')
                    ->where('transactionId', $request->confirmid)
                    ->update(['status' => 'CONFIRM']);
                return back();
            } elseif (isset($request->unconfirmid)) {
                DB::table('transactions')
                    ->where('transactionId', $request->unconfirmid)
                    ->update(['status' => 'PENDING']);
                return back();
            } elseif (isset($request->deleteid)) {
                DB::table('transactions')
                    ->where('transactionId', $request->deleteid)
                    ->delete();
                return back();
            } else {
                return view('admin.transactions')->with('datadeposits', $datadeposits);
            }
        }
    }

    public function exportToCSV(Request $request)
    {
        if ($request->package != "None") {
            $datas = DB::table('transactions')
                ->where('transactionType', $request->package)
                ->orderByDesc('id')
                ->get();

            $csvExporter = Writer::createFromFileObject(new \SplTempFileObject());

            // Set the CSV header
            $csvExporter->insertOne([
                'TransactionId',
                'Username',
                'Email',
                'Phone Number',
                'Amount',
                'Tansaction Type',
                'Transaction Service',
                'Payment Method',
                'Date',
            ]);

            // Add the data rows
            foreach ($datas as $data) {
                $csvExporter->insertOne([
                    $data->transactionId,
                    $data->username,
                    $data->email,
                    $data->phoneNumber,
                    $data->amount,
                    $data->transactionType,
                    $data->transactionService,
                    $data->paymentMethod,
                    $data->created_at,
                ]);
            }
            // Set the file name and headers for the download
            $fileName = 'transactions.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ];

            // Return the CSV file as a download
            return response()->streamDownload(
                function () use ($csvExporter) {
                    echo $csvExporter->getContent();
                },
                $fileName,
                $headers
            );
        } else {
            $datas = DB::table('transactions')
                ->orderByDesc('id')
                ->get();

            $csvExporter = Writer::createFromFileObject(new \SplTempFileObject());

            // Set the CSV header
            $csvExporter->insertOne([
                'TransactionId',
                'Username',
                'Email',
                'Phone Number',
                'Amount',
                'Tansaction Type',
                'Transaction Service',
                'Payment Method',
                'Date',
            ]);

            // Add the data rows
            foreach ($datas as $data) {
                $csvExporter->insertOne([
                    $data->transactionId,
                    $data->username,
                    $data->email,
                    $data->phoneNumber,
                    $data->amount,
                    $data->transactionType,
                    $data->transactionService,
                    $data->paymentMethod,
                    $data->created_at,
                ]);
            }
            // Set the file name and headers for the download
            $fileName = 'transactions.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ];

            // Return the CSV file as a download
            return response()->streamDownload(
                function () use ($csvExporter) {
                    echo $csvExporter->getContent();
                },
                $fileName,
                $headers
            );
        }
    }
}
