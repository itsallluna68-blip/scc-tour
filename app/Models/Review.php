<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{   protected $table = 'tblreviews';
    protected $primaryKey = 'rid';
    protected $fillable = [
        'place_id',
        'email',
        'name',
        'ratings',
        'feedback',
        'date',
        'rpic0',
        'rpic1',
        'rpic2'
    ]; 
    public $timestamps = false;

    public function place()
    {
        return $this->belongsTo(Exploreplaces::class, 'place_id', 'id');
    }

    // public function place()
    // {
    //     return $this->belongsTo(\App\Models\Exploreplaces::class, 'place_id');
    // }
}
