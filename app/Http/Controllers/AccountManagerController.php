<?php

namespace App\Http\Controllers;

use App\Models\AccountManager;
use App\Models\User;
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

        // Get users assigned to this manager via direct `users.manager` column
        $users = User::where('manager', $managerToDelete->id)->get();

        // Get other managers to reassign users
        $otherManagers = AccountManager::where('id', '!=', $id)->get();

        if ($otherManagers->isEmpty()) {
            return redirect()->back()->with('error', 'Cannot delete. No other managers to reassign users.');
        }

        $managerCount = $otherManagers->count();
        $index = 0;

        foreach ($users as $user) {
            // Pick a new manager in round-robin style
            $newManager = $otherManagers[$index % $managerCount];

            // Decrement old manager
            $managerToDelete->decrement('noOfUsers');
            $managerToDelete->decrement('totalUsers');

            // Assign to new manager
            $user->manager = $newManager->id;
            $user->save();

            $newManager->users()->syncWithoutDetaching([$user->id]);
            $newManager->increment('noOfUsers');
            $newManager->increment('totalUsers');

            $index++;
        }

        // Detach users from pivot table (optional cleanup)
        $managerToDelete->users()->detach();

        // Finally, delete the manager
        $managerToDelete->delete();

        return redirect()->back()->with('success', 'Manager deleted and users reassigned.');
    }

    // 3. Assign to users
    public function assignUsers(Request $request)
    {
        $validated = $request->validate([
            'usernames' => 'required|array',
            'usernames.*' => 'exists:users,username',
            'manager_email' => 'required|email',
        ]);

        $manager = AccountManager::where('email', $validated['manager_email'])->firstOrFail();

        // Get user IDs from usernames
        $userIds = User::whereIn('username', $request->usernames)->pluck('id')->toArray();

        // Assign users without detaching existing ones
        $manager->users()->syncWithoutDetaching($userIds);

        return redirect()->back()->with('success', 'Users assigned successfully.');
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
                ->with('toast_error', $validator->messages()->first())
                ->withInput();
        }

        $validated = $validator->validated();
        $user = User::where('username', $validated['username'])->firstOrFail();
        $newManager = AccountManager::where('email', $validated['manager_email'])->firstOrFail();

        // Handle previous manager count decrement
        if ($user->manager && $user->manager != $newManager->id) {
            $previousManager = AccountManager::find($user->manager);

            if ($previousManager) {
                $previousManager->decrement('noOfUsers');
                $previousManager->decrement('totalUsers');
            }
        }

        // Sync pivot relationship (many-to-many)
        $user->accountManagers()->syncWithoutDetaching([$newManager->id]);

        // If the new manager is different, increment their counts
        if ($user->manager !== $newManager->id) {
            $newManager->increment('noOfUsers');
            $newManager->increment('totalUsers');
        }

        // Update the manager reference on the user record
        $user->manager = $newManager->id;
        $user->save();

        return redirect()->back()->with('success', 'User reassigned successfully.');
    }


    public function assignUsersEqually()
    {
        $managers = AccountManager::all(['id']); // Only fetch what's needed
        $users = User::whereNull('manager')->get(); // Only unassigned users

        if ($managers->isEmpty() || $users->isEmpty()) {

            return redirect()->back()->with('error', 'No managers or unassigned users found.');
        }

        $managerCount = $managers->count();
        $index = 0;

        DB::beginTransaction();

        try {
            foreach ($users as $user) {
                $manager = $managers[$index % $managerCount];

                // Update the manager column on the user
                $user->manager = $manager->id;
                $user->save();

                $manager->users()->syncWithoutDetaching([$user->id]);

                $index++;
            }

            foreach ($managers as $manager) {
                $manager->noOfUsers = $manager->users()->count();
                $manager->totalUsers =  $manager->users()->count();
                $manager->save();
            }


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
        return view('account_managers.dashboard', compact('manager'));
    }

    public function assignedUsers()
    {
        $manager = auth('account_manager')->user();
        $users = $manager->users()->get();
        return view('account_managers.users', compact('users'));
    }

    public function userTransactions(Request $request)
    {
        $user = User::where('userId', $request->id)->first();
        $transactions = DB::table('transactions')->where('userId', $user->userId)->latest()->get(); // assumes `transactions()` relation exists
        return view('account_managers.user_transactions', compact('user', 'transactions'));
    }
}
