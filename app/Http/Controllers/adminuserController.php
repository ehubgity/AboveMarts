<?php

namespace App\Http\Controllers;

use League\Csv\Writer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class adminuserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['otheradmin']);
    }

    public function index(Request $request)
    {
        $datausers = DB::table('users')->orderByDesc('id')
            ->paginate(20);

        if (isset($request->lockid)) {
            DB::table('users')
                ->where('userId', $request->lockid)
                ->update(['status' => 'BLOCK']);
            return back();

        } elseif (isset($request->unlockid)) {
            DB::table('users')
                ->where('userId', $request->unlockid)
                ->update(['status' => 'ACTIVE']);
            return back();

        } elseif (isset($request->deleteid)) {
            DB::table('users')
                ->where('userId', $request->deleteid)
                ->delete();
            return back();

        } else {
            return view('admin.users')->with('datausers', $datausers);

        }


    }
    public function search(Request $request)
    {
        $datausers = DB::table('users')->orderByDesc('id')
            ->paginate(20);

        $query = $request->input('query');

        $datas = DB::table('users')->where('username', 'LIKE', "%$query%")->orWhere('email', 'LIKE', "%$query%")->orWhere('firstname', 'LIKE', "%$query%")->orWhere('sponsor', 'LIKE', "%$query%")->orWhere('package', 'LIKE', "%$query%")->orWhere('rank', 'LIKE', "%$query%")
            ->orWhere('phoneNumber', 'LIKE', "%$query%")->orWhere('status', 'LIKE', "%$query%")->orWhere('accountNumber', 'LIKE', "%$query%")->orderByDesc('id')->get();
        return view('admin.usersearch')->with('query', $query)->with('datas', $datas)->with('datausers', $datausers);
    }
    public function exportToCSV(Request $request)
    {

        if ($request->package != "None") {
            $datas = DB::table('users')->where('package', $request->package)->orderByDesc('id')->get();

            $csvExporter = Writer::createFromFileObject(new \SplTempFileObject());

            // Set the CSV header
            $csvExporter->insertOne(['userId', 'Name', 'Username', 'Phone Number', 'Email', 'Rank', 'Package', 'Sponsor', 'Date']);

            // Add the data rows
            foreach ($datas as $data) {
                $csvExporter->insertOne([$data->userId, $data->firstName . ' ' . $data->lastName, $data->username, $data->phoneNumber, $data->email, $data->rank, $data->package, $data->sponsor, $data->created_at]);
            }
            // Set the file name and headers for the download
            $fileName = 'users.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ];

            // Return the CSV file as a download
            return response()->streamDownload(function () use ($csvExporter) {
                echo $csvExporter->getContent();
            }, $fileName, $headers);
        } else {
            $datas = DB::table('users')->orderByDesc('id')->get();

            $csvExporter = Writer::createFromFileObject(new \SplTempFileObject());
            // Set the CSV header
            $csvExporter->insertOne(['userId', 'Name', 'Username', 'Phone Number', 'Email', 'Rank', 'Package', 'Sponsor', 'Satus', 'Date']);

            // Add the data rows
            foreach ($datas as $data) {
                $csvExporter->insertOne([$data->userId, $data->firstName . ' ' . $data->lastName, $data->username, $data->phoneNumber, $data->email, $data->rank, $data->package, $data->sponsor, $data->status, $data->created_at]);
            }
            // Set the file name and headers for the download
            $fileName = 'users.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ];

            // Return the CSV file as a download
            return response()->streamDownload(function () use ($csvExporter) {
                echo $csvExporter->getContent();
            }, $fileName, $headers);
        }
    }

}
