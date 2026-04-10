<?php

namespace App\Http\Controllers;

use App\Models\AccountManager;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountManagerController extends Controller
{
    // Show all account managers
    public function index()
    {
        $managers = AccountManager::latest()->paginate(10);
        return view('account_managers.index', compact('managers'));
    }

    public function viewUsersOfManager($id)
    {
        $manager = AccountManager::with('users')->where('accountManagerId', $id)->firstOrFail();
        // return $users->users;
        $users = $manager->users()->latest()->paginate(20);

        return view('account_managers.users')->with('users', $users);
    }

    // Show create form
    public function create()
    {
        return view('account_managers.create');
    }

    public function reassign()
    {
        return view('account_managers.reassign');
    }

    // 1. Create Account managers
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:account_managers,email',
            'password' => 'required|string|min:6',
        ]);

        do {
            $validated['accountManagerId'] = 'ACM' . rand(100000, 999999);
        } while (AccountManager::where('accountManagerId', $validated['accountManagerId'])->exists());

        $validated['password'] = Hash::make($validated['password']);

        // Create the AccountManager
        AccountManager::create($validated);
        return redirect()->back()->with('success', 'Account Manager created successfully.');
    }
    // 2. Edit and delete account managers
    public function editindex(Request $request)
    {
        $id = $request->id;
        if (isset($id)) {
            $manager = AccountManager::where('accountManagerId', $id)->first();
            return view(view: 'account_managers.edit')->with('manager', $manager);
        }
        return redirect()->back()->with('error', 'No managers or users found.');
    }

    public function update(Request $request)
    {
        $manager = AccountManager::where('accountManagerId', $request->id)->firstOrFail();

        $data = ['name' => $request->name];

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $manager->update($data);
        return redirect()->back()->with('success', 'Manager updated');
    }
    public function destroy($id)
    {
        $managerToDelete = AccountManager::findOrFail($id);

        // Get users assigned to this manager
        $users = User::where('manager', $managerToDelete->id)->get();

        // Get other managers to reassign users
        $otherManagers = AccountManager::where('id', '!=', $id)->get();

        if ($otherManagers->isEmpty()) {
            return redirect()->back()->with('error', 'Cannot delete. No other managers to reassign users.');
        }

        $managerCount = $otherManagers->count();
        $index = 0;

        DB::beginTransaction();
        try {
            foreach ($users as $user) {
                // Pick a new manager in round-robin style
                $newManager = $otherManagers[$index % $managerCount];

                // Assign to new manager
                $user->manager = $newManager->id;
                $user->save();

                // Optional: Sync pivot if still used anywhere
                $newManager->assignedUsers()->syncWithoutDetaching([$user->id]);
                
                $index++;
            }

            // Update counts for all managers involved
            foreach ($otherManagers as $manager) {
                $count = User::where('manager', $manager->id)->count();
                $manager->update([
                    'noOfUsers' => $count,
                    'totalUsers' => $count
                ]);
            }

            // Detach users from pivot table for the deleted manager
            $managerToDelete->assignedUsers()->detach();
            $managerToDelete->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Manager deleted and users reassigned.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Deletion failed: ' . $e->getMessage());
        }
    }

    // 3. Assign to users
    public function assignUsers(Request $request)
    {
        $validated = $request->validate([
            'manager_email' => 'required|email|exists:account_managers,email',
            'assignment_mode' => 'required|in:selected,alphabet,search',
            'usernames' => 'nullable|array',
            'usernames.*' => 'exists:users,username',
            'starts_with' => 'nullable|string|size:1|alpha',
            'query' => 'nullable|string',
            'only_unassigned' => 'nullable|boolean',
        ]);

        $manager = AccountManager::where('email', $validated['manager_email'])->firstOrFail();
        $usersQuery = User::query();

        if ($validated['assignment_mode'] === 'selected') {
            if (empty($validated['usernames'])) {
                return redirect()->back()->with('error', 'Select at least one user before assigning a manager.');
            }

            $usersQuery->whereIn('username', $validated['usernames']);
        }

        if ($validated['assignment_mode'] === 'alphabet') {
            if (empty($validated['starts_with'])) {
                return redirect()->back()->with('error', 'Choose an alphabet before assigning users.');
            }

            $letter = strtoupper($validated['starts_with']);
            $usersQuery->where('firstName', 'LIKE', "{$letter}%");
        }

        if ($validated['assignment_mode'] === 'search') {
            $searchTerm = trim((string) ($validated['query'] ?? ''));
            $startsWith = strtoupper(trim((string) ($validated['starts_with'] ?? '')));
            $hasAlphabetFilter = (bool) preg_match('/^[A-Z]$/', $startsWith);

            if ($searchTerm === '' && !$hasAlphabetFilter) {
                return redirect()->back()->with('error', 'Apply a search or alphabet filter before assigning the current filtered results.');
            }

            if ($searchTerm !== '') {
                $this->applyUserSearchFilter($usersQuery, $searchTerm);
            }

            if ($hasAlphabetFilter) {
                $usersQuery->where('firstName', 'LIKE', "{$startsWith}%");
            }
        }

        if (($validated['only_unassigned'] ?? false)) {
            $usersQuery->whereNull('manager');
        }

        $userIds = (clone $usersQuery)->pluck('id');

        if ($userIds->isEmpty()) {
            return redirect()->back()->with('error', 'No users matched the selected assignment criteria.');
        }

        $previousManagerIds = (clone $usersQuery)
            ->whereNotNull('manager')
            ->pluck('manager')
            ->filter()
            ->unique()
            ->values()
            ->all();

        DB::transaction(function () use ($userIds, $manager, $previousManagerIds) {
            User::whereIn('id', $userIds)->update(['manager' => $manager->id]);

            $this->refreshManagerCounts(array_unique(array_merge($previousManagerIds, [$manager->id])));
        });

        $userCount = $userIds->count();
        $label = $userCount === 1 ? 'user' : 'users';

        return redirect()->back()->with('success', "{$userCount} {$label} assigned to {$manager->name} successfully.");
    }

    // 4. Reassign to users
    public function reassignUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|exists:users,username',
            'manager_email' => 'required|email|exists:account_managers,email',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('toast_error', $validator->errors()->first())
                ->withInput();
        }

        $validated = $validator->validated();
        $user = User::where('username', $validated['username'])->firstOrFail();
        $newManager = AccountManager::where('email', $validated['manager_email'])->firstOrFail();
        $oldManagerId = $user->manager;

        $user->manager = $newManager->id;
        $user->save();
        $managerIds = array_filter([$oldManagerId, $newManager->id]);
        $this->refreshManagerCounts($managerIds);

        return redirect()->back()->with('success', 'User reassigned successfully.');
    }

    public function assignUsersEqually()
    {
        $managers = AccountManager::all();
        $users = User::whereNull('manager')->get();

        if ($managers->isEmpty() || $users->isEmpty()) {
            return redirect()->back()->with('error', 'No managers or unassigned users found.');
        }

        $managerCount = $managers->count();
        $index = 0;

        DB::beginTransaction();
        try {
            foreach ($users as $user) {
                $manager = $managers[$index % $managerCount];
                $user->manager = $manager->id;
                $user->save();
                $index++;
            }

            $this->refreshManagerCounts($managers->pluck('id')->all());

            DB::commit();
            return redirect()->back()->with('success', 'Users assigned equally to account managers.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Assignment failed: ' . $e->getMessage());
        }
    }

    public function dashboard()
    {
        $manager = auth('account_manager')->user();

        if (!$manager instanceof AccountManager) {
            abort(403);
        }

        $status = [
            'total_users' => $manager->users()->count(),
            'total_deposit' => User::where('manager', $manager->id)->sum('currentBalance'), // Placeholder for actual deposit logic
            // Add more stats as needed
        ];
        return view('account_managers.dashboard', compact('manager', 'status'));
    }

    public function assignedUsers()
    {
        $manager = auth('account_manager')->user();

        if (!$manager instanceof AccountManager) {
            abort(403);
        }

        $users = $manager->users()->paginate(20);
        return view('account_managers.users', compact('users'));
    }

    public function userTransactions($id)
    {
        $user = User::where('userId', $id)->firstOrFail();
        
        // Ensure the user belongs to the logged-in manager
        if ($user->manager !== auth('account_manager')->id()) {
            abort(403, 'Unauthorized action.');
        }

        $transactions = DB::table('transactions')->where('userId', $user->userId)->latest()->paginate(20);
        return view('account_managers.user_transactions', compact('user', 'transactions'));
    }

    private function applyUserSearchFilter(Builder $usersQuery, string $searchTerm): void
    {
        $usersQuery->where(function (Builder $builder) use ($searchTerm) {
            $builder->where('username', 'LIKE', "%{$searchTerm}%")
                ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                ->orWhere('firstName', 'LIKE', "%{$searchTerm}%")
                ->orWhere('lastName', 'LIKE', "%{$searchTerm}%")
                ->orWhere('sponsor', 'LIKE', "%{$searchTerm}%")
                ->orWhere('package', 'LIKE', "%{$searchTerm}%")
                ->orWhere('rank', 'LIKE', "%{$searchTerm}%")
                ->orWhere('phoneNumber', 'LIKE', "%{$searchTerm}%")
                ->orWhere('status', 'LIKE', "%{$searchTerm}%")
                ->orWhere('accountNumber', 'LIKE', "%{$searchTerm}%");
        });
    }

    private function refreshManagerCounts(array $managerIds): void
    {
        $uniqueManagerIds = collect($managerIds)->filter()->unique();

        if ($uniqueManagerIds->isEmpty()) {
            return;
        }

        AccountManager::whereIn('id', $uniqueManagerIds)->get()->each(function (AccountManager $manager) {
            $count = User::where('manager', $manager->id)->count();
            $manager->update([
                'noOfUsers' => $count,
                'totalUsers' => $count,
            ]);
        });
    }
}
