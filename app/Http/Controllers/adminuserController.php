<?php

namespace App\Http\Controllers;

use App\Models\AccountManager;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
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
        if (isset($request->lockid)) {
            User::where('userId', $request->lockid)->update(['status' => 'BLOCK']);
            return back();
        } elseif (isset($request->unlockid)) {
            User::where('userId', $request->unlockid)->update(['status' => 'ACTIVE']);
            return back();
        } elseif (isset($request->deleteid)) {
            User::where('userId', $request->deleteid)->delete();
            return back();
        } else {
            $query = trim((string) $request->input('query'));
            $startsWith = strtoupper(trim((string) $request->input('starts_with')));
            $startsWith = preg_match('/^[A-Z]$/', $startsWith) ? $startsWith : null;
            $usersQuery = $this->buildUsersQuery($query, $startsWith);

            $datausers = $usersQuery->paginate(20);
            $datausers->appends($request->query());
            $managers = AccountManager::orderBy('name')->get();
            $assignmentSummary = [
                'totalUsers' => User::count(),
                'assignedUsers' => User::whereNotNull('manager')->count(),
                'unassignedUsers' => User::whereNull('manager')->count(),
                'filteredUsers' => (clone $usersQuery)->count(),
            ];

            return view('admin.users', compact('datausers', 'managers', 'query', 'startsWith', 'assignmentSummary'));
        }
    }

    public function search(Request $request)
    {
        return $this->index($request);
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

    private function buildUsersQuery(?string $query, ?string $startsWith = null): Builder
    {
        $usersQuery = User::with('accountManager')->orderByDesc('id');

        if ($startsWith !== null) {
            $usersQuery->where('firstName', 'LIKE', "{$startsWith}%");
        }

        if ($query !== null && $query !== '') {
            $usersQuery->where(function (Builder $builder) use ($query) {
                $builder->where('username', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%")
                    ->orWhere('firstName', 'LIKE', "%{$query}%")
                    ->orWhere('lastName', 'LIKE', "%{$query}%")
                    ->orWhere('sponsor', 'LIKE', "%{$query}%")
                    ->orWhere('package', 'LIKE', "%{$query}%")
                    ->orWhere('rank', 'LIKE', "%{$query}%")
                    ->orWhere('phoneNumber', 'LIKE', "%{$query}%")
                    ->orWhere('status', 'LIKE', "%{$query}%")
                    ->orWhere('accountNumber', 'LIKE', "%{$query}%");
            });
        }

        return $usersQuery;
    }
}
