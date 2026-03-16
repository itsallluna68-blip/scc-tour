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
        if (Auth::check() && Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized access. Only Admin can manage users.');
        }

        $search = $request->input('search');

        $users = User::where('status', 'active')
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
        if (Auth::check() && Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized access. Only Admin can create users.');
        }

        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'mname' => 'nullable|string|max:255',
            'lname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:tblusers,username',
            'password' => 'required|string|min:8',
            'usertype' => 'required|string|in:admin,staff',
        ]);

        User::create([
            'fname' => $validated['fname'],
            'mname' => $validated['mname'] ?? '',
            'lname' => $validated['lname'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'usertype' => $validated['usertype'],
            'status' => 'active',
        ]);

        return redirect()->route('users.index')->with('success', 'User added successfully!');
    }

    public function update(Request $request, $id)
    {
        if (Auth::check() && Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized access. Only Admin can update users.');
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'mname' => 'nullable|string|max:255',
            'lname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:tblusers,username,' . $id . ',id',
            'password' => 'nullable|string|min:8',
            'usertype' => 'required|string|in:admin,staff',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['mname'] = $validated['mname'] ?? '';
        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        if (Auth::check() && Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized access. Only Admin can delete users.');
        }

        $user = User::findOrFail($id);
        $user->update(['status' => 'block']);

        return redirect()->route('users.index')->with('success', 'User deactivated successfully!');
    }

    public function trash(Request $request)
    {
        if (Auth::check() && Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized access. Only Admin can view user trash.');
        }

        $search = $request->input('search');

        $users = User::where('status', 'block')
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
        if (Auth::check() && Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized access. Only Admin can restore users.');
        }

        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);

        return response()->json(['success' => true, 'message' => 'User restored successfully!']);
    }

    public function search(Request $request)
    {
        if (Auth::check() && Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized access. Only Admin can search users.');
        }

        $query = $request->get('query', '');
        $users = User::where('fname', 'LIKE', "%{$query}%")->get();

        return view('admin.list.partials.users_table', compact('users'));
    }
}