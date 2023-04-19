<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Administrator extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'admin';
    protected $fillable = [
        'email', 
        'password', 
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
