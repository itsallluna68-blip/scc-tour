<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'images'
    ];
    public function getImagesAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function setImagesAttribute($value)
    {
        $this->attributes['images'] = $value ? json_encode($value) : null;
    }

    public function categories()
    {
        return $this->belongsToMany(
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