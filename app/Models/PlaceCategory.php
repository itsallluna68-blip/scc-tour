<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exploreplaces;

class PlaceCategory extends Model
{
    use HasFactory;
    protected $table = 'tblcategories';
    protected $primaryKey = 'cid';

    public function places(){
        return $this->belongsToMany(
        // Exploreplaces::class,'tblplacecategory',
        // 'placeid',
        // 'categoryid',
        // 'cid', 
        // 'id'   
        // cocogrove 4 devs 2-21
        Exploreplaces::class,
        'tblplacecategory',
        'categoryid',
        'placeid',
        'cid',
        'id'
    );
    }

    public function activities()
    {
        return $this->belongsToMany(
            \App\Models\Activity::class,
            'tblcategoryactivity',
            'categoryid',
            'activityid',
            'cid',
             'aid'
        );
    }
}