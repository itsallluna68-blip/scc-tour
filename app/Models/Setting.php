<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'tblsetting';   // custom table name
    protected $primaryKey = 'sid';     // custom primary key

    public $timestamps = false;        // disable if you don't have created_at & updated_at

    protected $fillable = [
        'term',
        'details',
    ];
}