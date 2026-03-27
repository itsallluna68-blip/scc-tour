<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'tblsetting';
    protected $primaryKey = 'sid';

    public $timestamps = false;

    protected $fillable = [
        'term',
        'details',
    ];
}