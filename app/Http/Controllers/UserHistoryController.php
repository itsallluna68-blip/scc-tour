<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserHistory;
use Illuminate\Support\Facades\Auth;

class UserHistoryController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized access. Only Admin can view user logs.');
        }

        $userLogs = UserHistory::orderBy('date_time', 'desc')->get();

        return view('admin.list.userlog', compact('userLogs'));
    }
}