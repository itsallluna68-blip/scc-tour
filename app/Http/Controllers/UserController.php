<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index(Request $request)
{
    // Only Super Admin can access this
    if (Auth::check() && Auth::user()->usertype !== 'Super Admin') {
        abort(403, 'Unauthorized access. Only Super Admin can manage users.');
    }

    $search = $request->input('search'); // get the search value from query string

    $users = User::where('status', 1)
        ->when($search, function ($query, $search) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('fname', 'LIKE', "%{$search}%")
                         ->orWhere('mname', 'LIKE', "%{$search}%")
                         ->orWhere('lname', 'LIKE', "%{$search}%")
                         ->orWhere('username', 'LIKE', "%{$search}%");
            });
        })
        ->get();

    return view('admin.list.usershome', compact('users'));
}


    public function store(Request $request)
    {
        // Only Super Admin can create users
        if (Auth::check() && Auth::user()->usertype !== 'Super Admin') {
            abort(403, 'Unauthorized access. Only Super Admin can create users.');
        }

        // Validate fields
        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'mname' => 'nullable|string|max:255',
            'lname' => 'required|string|max:255',
            // 'bdate' => 'required|date',
            'username' => 'required|string|max:255|unique:tblusers,username',
            'password' => 'required|string|min:8',
            'usertype' => 'required|string|in:Super Admin,Admin,Staff',
        ]);

        // Save user
        User::create([
            'fname' => $validated['fname'],
            'mname' => $validated['mname'] ?? '',
            'lname' => $validated['lname'],
            // 'bdate' => $validated['bdate'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'usertype' => $validated['usertype'],
            'status' => '1',
        ]);
        return redirect()->route('users.index')->with('success', 'User added successfully!');
    }

    public function update(Request $request, $id)
    {
        // Only Super Admin can update users
        if (Auth::check() && Auth::user()->usertype !== 'Super Admin') {
            abort(403, 'Unauthorized access. Only Super Admin can update users.');
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'mname' => 'nullable|string|max:255',
            'lname' => 'required|string|max:255',
            // 'bdate' => 'required|date',
            'username' => 'required|string|max:255|unique:tblusers,username,' . $id . ',id',
            'password' => 'nullable|string|min:8',
            'usertype' => 'required|string',
        ]);


        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']); // keep old password
        }


        // Update user
        $user->update([
            'fname' => $validated['fname'],
            'mname' => $validated['mname'] ?? '',
            'lname' => $validated['lname'],
            // 'bdate' => $validated['bdate'],
            'username' => $validated['username'],
            'usertype' => $validated['usertype'],
        ]);
        $validated['mname'] = $validated['mname'] ?? '';
        $user->update($validated);
        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

        // Delete User
            public function destroy($id)
    {
        // Only Super Admin can delete users
        if (Auth::check() && Auth::user()->usertype !== 'Super Admin') {
            abort(403, 'Unauthorized access. Only Super Admin can delete users.');
        }

        $user = User::findOrFail($id);

        $user->update(['status' => 0]);

        return redirect()->route('users.index')->with('success', 'User deactivated successfully!');
    }

        // Users Bin
        public function trash(Request $request)
    {
        // Only Super Admin can view trash
        if (Auth::check() && Auth::user()->usertype !== 'Super Admin') {
            abort(403, 'Unauthorized access. Only Super Admin can view user trash.');
        }

        $search = $request->input('search');

        $users = User::where('status', 0)
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('fname', 'LIKE', "%{$search}%")
                            ->orWhere('mname', 'LIKE', "%{$search}%")
                            ->orWhere('lname', 'LIKE', "%{$search}%")
                            ->orWhere('username', 'LIKE', "%{$search}%");
                });
            })
            ->get();

        return view('admin.list.bin.userstrash', compact('users'));
    }

        public function restore($id)
    {
        // Only Super Admin can restore users
        if (Auth::check() && Auth::user()->usertype !== 'Super Admin') {
            abort(403, 'Unauthorized access. Only Super Admin can restore users.');
        }

        $user = User::findOrFail($id);
        $user->update(['status' => 1]);
        return response()->json(['success' => true, 'message' => 'User restored successfully!']);
    }

        // User Search
        public function search(Request $request)
    {
        // Only Super Admin can search users
        if (Auth::check() && Auth::user()->usertype !== 'Super Admin') {
            abort(403, 'Unauthorized access. Only Super Admin can search users.');
        }

        $query = $request->get('query', '');
        $users = User::where('name', 'LIKE', "%{$query}%")->get();

        return view('admin.list.partials.users_table', compact('users'));
    }

        // Trash Search


}
