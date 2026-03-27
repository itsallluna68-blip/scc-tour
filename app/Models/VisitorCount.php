<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorCount extends Model
{
    use HasFactory;

    protected $table = 'tblvisitorcount';

    public $timestamps = false;

    protected $fillable = [
        'vmonth',
        'vyear',
        'total_visitors',
        'loc',
        'date_add',
        'visitor_type'
    ];
}