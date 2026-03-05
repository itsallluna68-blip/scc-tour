<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Add this import
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable; // Ensure HasFactory is included here

    protected $table = 'tblusers'; // Your custom table name

    protected $fillable = [
        'fname', 'mname', 'lname', 'username', 'password', 'usertype', 'status',
    ];
}
