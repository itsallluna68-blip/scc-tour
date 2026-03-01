<?php

namespace App\Helpers;

use App\Models\UserHistory;
use Illuminate\Support\Facades\Auth;

class UserLogger
{
    public static function log(string $action)
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        UserHistory::create([
            'username'     => $user->username,
            'user_type'    => $user->usertype,
            'full_name'    => trim($user->fname . ' ' . $user->mname . ' ' . $user->lname),
            'date_time'    => now(),
            'action_taken' => $action,
        ]);
    }
}
