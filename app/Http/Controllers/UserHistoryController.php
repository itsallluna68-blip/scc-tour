<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserHistory;

class UserHistoryController extends Controller
{
    public function index()
    {
        $userLogs = UserHistory::orderBy('date_time', 'desc')->get();

        return view('admin.list.userlog', compact('userLogs'));
    }
}