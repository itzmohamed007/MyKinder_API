<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Sibling;
use App\Models\Teacher;
use App\Models\Student;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AdministratorController extends Controller
{
    // Global
    public function global()
    {
        $classrooms = Classroom::with('students', 'teacher')->get();
        $teachers = Teacher::all();
        $siblings = Sibling::all();
        $students = Student::with('classroom', 'teacher', 'sibling')->get()->map(function ($student) {
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

    // Teachers Crud
    // Teacher Create
    public function TeacherCreate(Request $request)
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

    // Teacher Update
    public function TeacherUpdate(Request $request, $id)
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
            'email' => 'required|string|email',
            'phone' => 'required|string|min:10|max:14',
        ]);

        $teacher->update([
            'name' => $valid['name'],
            'email' => $valid['email'],
            'phone' => $valid['phone'],
        ]);

        return $teacher;
    }

    // Teacher Delete
    public function TeacherDelete($id)
    {
        Teacher::destroy($id);

        return ([
            'status' => '200',
            'Message' => 'Teacher Account Deleted Successfully'
        ]);
    }


    // Classroom Crud

    // Classroom Create
    public function ClassroomCreate(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required|string|min:10|max:40',
            'teacher_id' => 'required',
        ]);

        Classroom::create([
            'name' => $valid['name'],
            'teacher_id' => $valid['teacher_id'],
            'activity' => 'null',
        ]);

        return response([
            'status' => '201',
            'message' => 'Classroom Added Successfully'
        ]);
    }

    // Classroom Update
    public function ClassroomUpdate(Request $request, $id)
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
            'activity' => 'string|required'
        ]);

        $classroom->update([
            'name' => $valid['name'],
            'teacher_id' => $valid['teacher_id'],
            'activity' => $valid['activity']
        ]);

        return $classroom;
    }

    // Classroom Delete
    public function ClassroomDelete($id)
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

    // Siblings Crud
    // Sibling Create
    public function SiblingCreate(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email',
            'phone' => 'required|string|min:10|max:14'
        ]);

        Sibling::create([
            'name' => $valid['name'],
            'email' => $valid['email'],
            'phone' => $valid['phone'],
            'role' => 'sibling',
            'password' => bcrypt('default')
        ]);

        return response([
            'status' => '201',
            'message' => 'Parent Account Created Successfully'
        ]);
    }

    // Sibling Update
    public function SiblingUpdate(Request $request)
    {
        $valid = $request->validate([

            'name' => 'required|string|max:50',
            'email' => 'required|string|email',
            'phone' => 'required|string|min:10|max:14'
        ]);

        Sibling::create($valid);

        return response([
            'status' => '201',
            'message' => 'Parent Account Created Successfully'
        ]);
    }
    // Sibling Delete
    public function SiblingDelete($id)
    {
        if (Sibling::destroy($id)) {
            return response([
                'status' => '200',
                'message' => 'Parent Account Deleted Successfully'
            ]);
        }
    }

    // Students Crud
    // Student Create
    public function StudentCreate(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required|string|max:30',
            'age' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'classroom_id' => 'required',
            'teacher_id' => 'required',
            'sibling_id' => 'required',
        ]);

        // $image = $request->file('image');
        // $tempPath = $image->store('temp', 'public');

        // $newPath = 'storage/app/images/' . $image->getClientOriginalName();
        // Storage::disk('local')->move($tempPath, $newPath);  


        $image_path = $request->file('image')->store('image', 'public');

        // dd($image_path);

        Student::create([
            'name' => $valid['name'],
            'age' => $valid['age'],
            'sibling_id' => $valid['sibling_id'],
            'teacher_id' => $valid['teacher_id'],
            'classroom_id' => $valid['classroom_id'],
            'image' => $image_path,
            // 'image' => $newPath,
        ]);

        return response([
            'status' => 201,
            'message' => 'Student Added Successfully'
        ]);
    }
    // Student Update
    public function StudentUpdate(Request $request, $id)
    {
        $student = Student::find($id);

        if (empty($student)) {
            return response([
                'status' => '401',
                'message' => 'Student Not Found'
            ]);
        }

        $valid = $request->validate([
            'name' => 'required|string|max:30',
            'age' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'teacher_id' => 'required',
            'sibling_id' => 'required',
            'classroom_id' => 'required',
        ]);

        $currentImagePath = $student->image;
        $image = $request->file('image');
        $tempPath = $image->store('temp', 'public');

        $newPath = 'storage/app/images/' . $image->getClientOriginalName();
        Storage::disk('local')->move($tempPath, $newPath);

        $student->update([
            'name' => $valid['name'],
            'age' => $valid['age'],
            'teacher_id' => $valid['teacher_id'],
            'sibling_id' => $valid['sibling_id'],
            'classroom_id' => $valid['classroom_id'],
            'image' => $newPath,
        ]);

        if (Storage::disk()->exists($currentImagePath)) {
            Storage::disk()->delete($currentImagePath);
        }

        return $student;
    }
    // Student Delete
    public function StudentDelete($id)
    {
        if (Student::destroy($id)) {
            return response([
                'status' => '200',
                'message' => 'Student Deleted Successfully'
            ]);
        } else {
            return response([
                'status' => '404',
                'message' => 'Student Not Found'
            ]);
        }
    }
}
