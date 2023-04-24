<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class TeacherController extends Controller
{
    public function index()
    {
        return Teacher::all();
    }

    public function show($id)
    {
        return Teacher::find($id);
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|unique:teachers,email',
            'phone' => 'required|min:10|max:14',
        ]);

        Teacher::create([
            'name' => $valid['name'],
            'email' => $valid['email'],
            'password' => bcrypt('default'),
            'phone' => $valid['phone'],
            'role' => "teacher",
        ]);

        return response([
            'status' => 201,
            'message' => 'Teacher Account Created Successfully'
        ]);
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::find($id);

        if (empty($teacher)) {
            return response([
                'status' => '401',
                'message' => 'Teacher Account Not Found'
            ]);
        }

        $valid = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|unique:siblings,email',
            'phone' => 'required|string|min:10|max:14',
        ]);

        $teacher->update([
            'name' => $valid['name'],
            'email' => $valid['email'],
            'phone' => $valid['phone'],
        ]);

        return $teacher;
    }

    public function delete($id)
    {
        Teacher::destroy($id);

        return ([
            'status' => '200',
            'Message' => 'Teacher Account Deleted Successfully'
        ]);
    }

    public function availableTeachers() {
        $availableTteachers = Teacher::whereNotIn('id', function($query) {
            $query->select('teacher_id')->from('classrooms');
        })->get();

        return $availableTteachers;
    }
}
