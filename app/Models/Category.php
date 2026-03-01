<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'tblcategories';
    protected $primaryKey = 'cid';
    public $timestamps = false;

    protected $fillable = [
        'category',
        'description',
        'status'
    ];


    public function activities()
    {
        return $this->belongsToMany(
            Activity::class,
            'tblcategoryactivity',
            'categoryid',
            'activityid',
            
        );
    }

    public function places()
    {
        return $this->belongsToMany(
            Exploreplaces::class,
            'tblplacecategory',
            'categoryid',
            'placeid'
            // cocogrove w 4 devs 2-21
            ,'cid',
        'id'
            
        );
    }
}