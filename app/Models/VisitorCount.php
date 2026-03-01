<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorCount extends Model
{
    use HasFactory;

    protected $table = 'tblvisitorcount';

    public $timestamps = false;

    // 'loc' column stores the location (e.g. pier, port to sipaway) which is
    // required by the database.  It wasn't previously included in the
    // migration so new inserts were failing with "Field 'loc' doesn't have a
    // default value".  Allow mass assignment here so that we can pass this
    // value from the controller.
    protected $fillable = ['vmonth', 'vyear', 'total_visitors', 'loc', 'date_add'];
}
