<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use Notifiable;
    
    protected $fillable = [
    'name',
    'email',
    'address',
    'password',
    ];
    
    protected $hidden = [
    'password',
    'remember_token',
    ];
    
    protected $casts = [
    'email_verified_at' => 'datetime',
    ];

}
