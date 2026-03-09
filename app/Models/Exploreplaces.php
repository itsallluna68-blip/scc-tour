<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PlaceCategory;

class Exploreplaces extends Model
{
    use HasFactory;

    protected $table = 'tblplaces';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $casts = [
        'images' => 'array',
    ];

    protected $fillable = [
        'name',
        'contact',
        'description',
        'history',
        'link1',
        'link2',
        'address',
        'email',
        'status',
        'transport',
        'map_link',
        'opening_hours',
        'is_popular',
        'images',
        'main_image'
    ];

    public function categories(){
        return $this ->belongsToMany(
            // PlaceCategory::class, 
            // 'tblplacecategory',
            // 'placeid',
            // 'categoryid',
            // 'id', 
            // 'cid' 
            // cocogrove w 4 devices 2-21
            Category::class,
            'tblplacecategory',
            'placeid',
            'categoryid',
            'id',
            'cid'

        );
    }
    
    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class, 'place_id', 'id')
                    ->orderBy('date', 'desc');
    }
    
    public function activities()
{
    return $this->belongsToMany(Activity::class, 'place_activity', 'place_id', 'activity_id');
}
}
