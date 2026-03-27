<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'tblreviews';
    protected $primaryKey = 'rid';
    protected $fillable = [
        'place_id',
        'name',
        'ratings',
        'feedback',
        'date',
        'rpic0',
        'rpic1',
        'rpic2',
        'status',
        'ip_address'
    ];
    public $timestamps = false;

    public function place()
    {
        return $this->belongsTo(Exploreplaces::class, 'place_id', 'id');
    }
}