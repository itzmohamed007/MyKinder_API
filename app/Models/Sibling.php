<?php

namespace App\Models;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Sibling extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'password'
    ];

    protected $hidden = ['password'];

    public function children() {
        return $this->hasMany(Student::class);
    }
}
