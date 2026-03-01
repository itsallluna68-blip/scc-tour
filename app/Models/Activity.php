<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'tblactivities';
    protected $primaryKey = 'aid';
    public $timestamps = false;

    protected $fillable = ['a_name', 'a_info', 'img0', 'a_status',];

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'tblcategoryactivity',
            'activityid',
            'categoryid'
        );
    }

    public function places()
    {
        return $this->belongsToMany(Exploreplaces::class, 'place_activity', 'aid', 'place_id');
}
}