<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;
use Illuminate\Http\Request;

class adminpackage extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('subadmin');
    }

    public function index(Request $request)
    {
        $datadeposits = DB::table('buypackages')
            ->orderByDesc('id')
            ->paginate(20);

        if (isset($request->deleteid)) {
            DB::table('buypackages')
                ->where('transactionId', $request->deleteid)
                ->delete();
            return back();
        } else {
            return view('admin.package')->with('datadeposits', $datadeposits);
        }
    }

    public function search(Request $request)
    {
        $datapackage = DB::table('buypackages')
            ->orderByDesc('id')
            ->paginate(20);
        $datadeposits = DB::table('buypackages')
            ->orderByDesc('id')
            ->paginate(20);

        $query = $request->input('query');

        if ($query != null) {
            $datas = DB::table('buypackages')
                ->where('transactionId', 'LIKE', "%$query%")
                ->orWhere('username', 'LIKE', "%$query%")
                ->orWhere('package', 'LIKE', "%$query%")
                ->orderByDesc('id')
                ->get();
            return view('admin.package')
                ->with('query', $query)
                ->with('datas', $datas)
                ->with('datapackage', $datapackage);
        } else {
            if (isset($request->deleteid)) {
                DB::table('buypackages')
                    ->where('transactionId', $request->deleteid)
                    ->delete();
                return back();
            } else {
                return view('admin.package')->with('datadeposits', $datadeposits);
            }
        }
    }

    public function exportToCSV(Request $request)
    {
        if ($request->package != "None") {
            $datas = DB::table('buypackages')
                ->where('package', $request->package)
                ->orderByDesc('id')
                ->get();

            $csvExporter = Writer::createFromFileObject(new \SplTempFileObject());

            // Set the CSV header
            $csvExporter->insertOne([
                'TransactionId',
                'Username',
                'Email',
                'Amount',
                'Package',
                'Date',
            ]);

            // Add the data rows
            foreach ($datas as $data) {
                $csvExporter->insertOne([
                    $data->transactionId,
                    $data->username,
                    $data->email,
                    $data->amount,
                    $data->package,
                    $data->created_at,
                ]);
            }
            // Set the file name and headers for the download
            $fileName = 'packages.csv';
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
            $datas = DB::table('buypackages')
                ->orderByDesc('id')
                ->get();

            $csvExporter = Writer::createFromFileObject(new \SplTempFileObject());

            // Set the CSV header
            $csvExporter->insertOne([
                'TransactionId',
                'Username',
                'Email',
                'Amount',
                'Package',
                'Date',
            ]);

            // Add the data rows
            foreach ($datas as $data) {
                $csvExporter->insertOne([
                    $data->transactionId,
                    $data->username,
                    $data->email,
                    $data->amount,
                    $data->package,
                    $data->created_at,
                ]);
            }
            // Set the file name and headers for the download
            $fileName = 'packages.csv';
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
