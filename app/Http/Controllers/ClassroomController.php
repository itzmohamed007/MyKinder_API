<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        return Classroom::with('teacher', 'students')->get();
    }

    public function show($id)
    {
        $classroom = Classroom::with('teacher', 'students')->find($id);

        $availableTeachers = Teacher::whereNotIn('id', function ($query) {
            $query->select('teacher_id')->from('classrooms');
        })->get();

        if (empty($classroom)) {
            return response([
                'status' => '404',
                'message' => 'Classroom Not Found'
            ]);
        }
        
        $availableTeachers->prepend($classroom->teacher);

        return response([
            'status' => '200',
            'classroom' => $classroom,
            'availableTeachers' => $availableTeachers
        ]);
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required|string|min:10|max:40',
            'teacher_id' => 'required',
        ]);

        Classroom::create([
            'name' => $valid['name'],
            'teacher_id' => $valid['teacher_id'],
        ]);

        return response([
            'status' => '201',
            'message' => 'Classroom Added Successfully'
        ]);
    }

    public function update(Request $request, $id)
    {
        $classroom = Classroom::find($id);

        if (empty($classroom)) {
            return response([
                'status' => '404',
                'message' => 'Classroom Not Found'
            ]);
        }

        $valid = $request->validate([
            'name' => 'required|string|min:10|max:40',
            'teacher_id' => 'required',
        ]);

        $classroom->update([
            'name' => $valid['name'],
            'teacher_id' => $valid['teacher_id']
        ]);

        return $classroom;
    }

    public function delete($id)
    {
        if (Classroom::destroy($id)) {
            return ([
                'status' => '200',
                'Message' => 'Classroom Deleted Successfully'
            ]);
        } else {
            return ([
                'status' => '404',
                'Message' => 'Classroom Not Found'
            ]);
        }
    }
}
