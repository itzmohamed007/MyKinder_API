<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Sibling;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AdministratorController extends Controller
{
    public function global()
    {
        $classrooms = Classroom::with('students', 'teacher')->get();
        $teachers = Teacher::all();
        $siblings = Sibling::all();
        $students = Student::with('classroom.teacher', 'sibling')->get()->map(function ($student) {
            $student->image_url = asset('storage/' . $student->image);
            return $student;
        });

        return response()->json([
            'classrooms' => $classrooms,
            'students' => $students,
            'teachers' => $teachers,
            'siblings' => $siblings
        ]);
    }
}
