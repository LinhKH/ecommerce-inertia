<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Users as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Users extends Model
{

    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        // 'city',
        // 'state',
        // 'country',
        // 'user_img',
        // 'wishlist',
        // 'status',
    ];
}
