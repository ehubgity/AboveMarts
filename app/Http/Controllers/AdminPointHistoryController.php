<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPointHistoryController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('otheradmin');
    }

    public function index(Request $request)
    {
        $datadeposits = DB::table('points')->orderByDesc('id')
            ->paginate(20);

        if (isset($request->deleteid)) {
            $data = DB::table('points')
                ->where('transactionId', $request->deleteid)->first();
            $user = DB::table('users')
                ->where('username', $data->username)->first();
            $oldpoint = $user->point;
            $newpoint = $oldpoint - $data->point;
            return dd($newpoint, $data->point, $oldpoint);


            $user = DB::table('users')
                ->where('username', $data->username)->update([
                        'point' => $newpoint,
                    ]);
            DB::table('points')
                ->where('transactionId', $request->deleteid)
                ->delete();
            return back();
        } else {
            return view('admin.pointhistory')->with('datadeposits', $datadeposits);
        }


    }

    public function search(Request $request)
    {
        $datapoint = DB::table('points')->orderByDesc('id')
            ->paginate(20);
        $datadeposits = DB::table('points')->orderByDesc('id')
            ->paginate(20);

        $query = $request->input('query');

        if ($query != null) {
            $datas = DB::table('points')->where('sponsor', 'LIKE', "%$query%")->orWhere('transactionId', 'LIKE', "%$query%")->orWhere('username', 'LIKE', "%$query%")->orWhere('package', 'LIKE', "%$query%")
                ->orderByDesc('id')->get();
            return view('admin.pointhistory')->with('query', $query)->with('datas', $datas)->with('datapoint', $datapoint);
        } else {

            if (isset($request->deleteid)) {
                $data = DB::table('points')
                    ->where('transactionId', $request->deleteid)->first();
                $user = DB::table('users')
                    ->where('username', $data->username)->first();
                $oldpoint = $user->point;
                $newpoint = $oldpoint - $data->point;


                $user = DB::table('users')
                    ->where('username', $data->username)->update([
                            'point' => $newpoint,
                        ]);

                DB::table('points')
                    ->where('transactionId', $request->deleteid)
                    ->delete();
                return back();

            } else {
                return view('admin.pointhistory')->with('datadeposits', $datadeposits);

            }

        }

    }

    // public function exportToCSV(Request $request)
    // {


    //     if ($request->package != "None") {
    //         if ($request->sponsor == 'Admin') {
    //             $datas = DB::table('points')->where('sponsor', $request->user)->orderByDesc('id')->get();

    //             $csvExporter = Writer::createFromFileObject(new \SplTempFileObject());

    //             // Set the CSV header
    //             $csvExporter->insertOne(['BonusId', 'Sponsor', 'Downline', 'Amount', 'Package', 'Date']);

    //             // Add the data rows
    //             foreach ($datas as $data) {
    //                 $csvExporter->insertOne([$data->bonusId, $data->sponsor, $data->username, $data->amount, $data->package, $data->created_at]);
    //             }
    //             // Set the file name and headers for the download
    //             $fileName = 'bonus.csv';
    //             $headers = [
    //                 'Content-Type' => 'text/csv',
    //                 'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    //             ];

    //             // Return the CSV file as a download
    //             return response()->streamDownload(function () use ($csvExporter) {
    //                 echo $csvExporter->getContent();
    //             }, $fileName, $headers);

    //         } else {
    //             $datas = DB::table('points')->where('sponsor', '!=', 'Admin')->orderByDesc('id')->get();

    //             $csvExporter = Writer::createFromFileObject(new \SplTempFileObject());

    //             // Set the CSV header
    //             $csvExporter->insertOne(['BonusId', 'Sponsor', 'Downline', 'Amount', 'Package', 'Date']);

    //             // Add the data rows
    //             foreach ($datas as $data) {
    //                 $csvExporter->insertOne([$data->bonusId, $data->sponsor, $data->username, $data->amount, $data->package, $data->created_at]);
    //             }
    //             // Set the file name and headers for the download
    //             $fileName = 'bonus.csv';
    //             $headers = [
    //                 'Content-Type' => 'text/csv',
    //                 'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    //             ];

    //             // Return the CSV file as a download
    //             return response()->streamDownload(function () use ($csvExporter) {
    //                 echo $csvExporter->getContent();
    //             }, $fileName, $headers);
    //         }
    //     } else {
    //         $datas = DB::table('points')->orderByDesc('id')->get();

    //         $csvExporter = Writer::createFromFileObject(new \SplTempFileObject());

    //         // Set the CSV header
    //         $csvExporter->insertOne(['BonusId', 'Sponsor', 'Downline', 'Amount', 'Package', 'Date']);

    //         // Add the data rows
    //         foreach ($datas as $data) {
    //             $csvExporter->insertOne([$data->bonusId, $data->sponsor, $data->username, $data->amount, $data->package, $data->created_at]);
    //         }
    //         // Set the file name and headers for the download
    //         $fileName = 'bonus.csv';
    //         $headers = [
    //             'Content-Type' => 'text/csv',
    //             'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    //         ];

    //         // Return the CSV file as a download
    //         return response()->streamDownload(function () use ($csvExporter) {
    //             echo $csvExporter->getContent();
    //         }, $fileName, $headers);
    //     }
    // }
}
