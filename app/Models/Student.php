<?php

namespace App\Models;
use App\Models\Sibling;
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
        'status',
        'classroom_id',
        'sibling_id'
    ];

    public function classroom() {
        return $this->belongsTo(Classroom::class);
    }

    public function sibling() {
        return $this->belongsTo(Sibling::class);
    }
}
