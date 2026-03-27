<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserHistory;
use Illuminate\Support\Facades\Auth;

class UserHistoryController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check() && Auth::user()->usertype !== 'admin') {
            abort(403);
        }

        $userLogs = UserHistory::orderBy('date_time', 'desc')->paginate(10);

        return view('admin.list.userlog', compact('userLogs'));
    }

    public function destroy($id)
    {
        if (Auth::check() && Auth::user()->usertype !== 'admin') {
            abort(403);
        }

        $log = UserHistory::findOrFail($id);
        $log->delete();

        return redirect()->back()->with('success', 'Log entry deleted successfully!');
    }
}