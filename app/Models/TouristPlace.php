<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TouristPlace extends Model
{
    protected $table = 'tblplaces';

    protected $fillable = [
        'name',
        'contact',
        'description',
        'history',
        'link1',
        'link2',
        'address',
        'email',
        'image0',
        'image1',
        'image2',
        'image3',
        'category1',
        'category2',
        'category3',
        'status',
        'transport',
        'map_link', 
        'opening_hours',
        'is_popular',
    ];

    public $timestamps = false;

    protected $attributes = [
    'status' => 1,
    'image0' => '',
    'image1' => '',
    'image2' => '',
    'image3' => '',
];
}
