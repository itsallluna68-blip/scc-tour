<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserHistory extends Model
{
    protected $table = 'tbluserhistory';

    public $timestamps = false;

    protected $fillable = [
        'username',
        'user_type',
        'full_name',
        'date_time',
        'action_taken',
    ];
}
