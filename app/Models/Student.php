<?php

namespace App\Models;
use App\Models\Sibling;
use App\Models\Teacher;
use App\Models\Classroom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'image',
        'classroom_id',
        'teacher_id',
        'sibling_id',
    ];

    public function classroom() {
        return $this->belongsTo(Classroom::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function sibling() {
        return $this->belongsTo(Sibling::class);
    }
}
