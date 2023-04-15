<?php

namespace App\Models;
use App\Models\Teacher;
use App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'teacher_id'
    ];

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function students() {
        return $this->hasMany(Student::class);
    }
}
