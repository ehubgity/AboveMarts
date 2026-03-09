<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;
use Illuminate\Http\Request;

class adminbonushistory extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('subadmin');
    }

    public function index(Request $request){
                $datadeposits = DB::table('bonuses')->orderByDesc('id')
                ->paginate(20);

                    if(isset($request->deleteid)){
                        DB::table('bonuses')
                        ->where('bonusId', $request->deleteid)
                        ->delete();
                        return back();
                    }else{
                    return view('admin.bonushistory')->with('datadeposits', $datadeposits);  
                }

        
    }

    public function search(Request $request){
        $datausedcard = DB::table('bonuses')->orderByDesc('id')
               ->paginate(20);
        $datadeposits = DB::table('bonuses')->orderByDesc('id')
               ->paginate(20);

       $query = $request->input('query');

       if ($query != null){
        $datas = DB::table('bonuses')->where('sponsor', 'LIKE', "%$query%")->orWhere('bonusId', 'LIKE', "%$query%")->orWhere('sponsorId', 'LIKE', "%$query%")->orWhere('package', 'LIKE', "%$query%")
        ->orderByDesc('id')->get();
        return view('admin.bonushistory')->with('query', $query)->with('datas', $datas)->with('datausedcard', $datausedcard);
       }

       else{

        if(isset($request->deleteid)){
                DB::table('bonuses')
                ->where('bonusId', $request->deleteid)
                ->delete();
                return back();

            }else{
            return view('admin.bonushistory')->with('datadeposits', $datadeposits);  

        }

       }
       
   }

   public function exportToCSV(Request $request)
   {


        if ($request->package != "None"){
            if($request->sponsor == 'Admin'){
                $datas = DB::table('bonuses')->where('sponsor', $request->user)->orderByDesc('id')->get();

                $csvExporter = Writer::createFromFileObject(new \SplTempFileObject());
    
                // Set the CSV header
                $csvExporter->insertOne(['BonusId', 'Sponsor', 'Downline', 'Amount', 'Package', 'Date']);
    
                // Add the data rows
                foreach ($datas as $data) {
                    $csvExporter->insertOne([$data->bonusId, $data->sponsor, $data->username, $data->amount, $data->package, $data->created_at]);
                }
                // Set the file name and headers for the download
                    $fileName = 'bonus.csv';
                    $headers = [
                        'Content-Type' => 'text/csv',
                        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                    ];
    
                    // Return the CSV file as a download
                    return response()->streamDownload(function () use ($csvExporter) {
                        echo $csvExporter->getContent();
                    }, $fileName, $headers);
    
            }else {
                $datas = DB::table('bonuses')->where('sponsor',  '!=' , 'Admin')->orderByDesc('id')->get();

                $csvExporter = Writer::createFromFileObject(new \SplTempFileObject());
    
                // Set the CSV header
                $csvExporter->insertOne(['BonusId', 'Sponsor', 'Downline', 'Amount', 'Package', 'Date']);
    
                // Add the data rows
                foreach ($datas as $data) {
                    $csvExporter->insertOne([$data->bonusId, $data->sponsor, $data->username, $data->amount, $data->package, $data->created_at]);
                }
                // Set the file name and headers for the download
                    $fileName = 'bonus.csv';
                    $headers = [
                        'Content-Type' => 'text/csv',
                        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                    ];
    
                    // Return the CSV file as a download
                    return response()->streamDownload(function () use ($csvExporter) {
                        echo $csvExporter->getContent();
                    }, $fileName, $headers);
            }
           }else{
            $datas = DB::table('bonuses')->orderByDesc('id')->get();

            $csvExporter = Writer::createFromFileObject(new \SplTempFileObject());

            // Set the CSV header
            $csvExporter->insertOne(['BonusId', 'Sponsor', 'Downline', 'Amount', 'Package', 'Date']);

            // Add the data rows
            foreach ($datas as $data) {
                $csvExporter->insertOne([$data->bonusId, $data->sponsor, $data->username, $data->amount, $data->package, $data->created_at]);
            }
            // Set the file name and headers for the download
                $fileName = 'bonus.csv';
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
