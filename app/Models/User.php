<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'tblusers'; // ✅ use your custom table

    public $timestamps = false;    // ✅ disable created_at & updated_at

    protected $fillable = [
        'fname',
        'mname',
        'lname',
        'bdate',
        'username',
        'password',
        'usertype',
        'status'
    ];
    protected $attributes = [
    'status' => 1,
];

}
