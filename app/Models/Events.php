<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    protected $table = 'tblevents';

    protected $fillable = [
        'events',
        'e_info',
        'e_datetime',
        'e_location',
        'e_maplink',
        'e_link',
        'pics',    
        'status',
    ];

    protected $casts = [
        'pics' => 'array',  
    ];

    public $timestamps = false;
}