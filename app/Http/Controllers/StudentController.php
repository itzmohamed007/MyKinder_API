<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Sibling;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('classroom.teacher', 'sibling')->get()->map(function ($student) {
            $student->image_url = asset('storage/'.$student->image);
            return $student;
        });

        return $students;
    }

    public function show($id)
    {
        $student = Student::with('sibling', 'classroom')->find($id);
        $siblings = Sibling::all();
        $classrooms = Classroom::all();

        if(empty($student)) {
            return response([
                'status' => '404',
                'message' => 'Student Not Found'
            ]);
        }

        $student->image_url = asset('storage/'.$student->image);

        return response([
            'student' => $student,
            'classrooms' => $classrooms,
            'siblings' => $siblings
        ]);
    }

    public function teacherStudents() {
        $students = Student::where('teacher_id', request()->user()->id)->get();
        return $students;
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required|string|max:30',
            'age' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'classroom_id' => 'required|integer',
            'sibling_id' => 'required|integer',
        ]);

        $image_path = $request->file('image')->store('image', 'public');

        Student::create([
            'name' => $valid['name'],
            'age' => $valid['age'],
            'sibling_id' => $valid['sibling_id'],
            'classroom_id' => $valid['classroom_id'],
            'image' => $image_path
        ]);

        return response([
            'status' => 201,
            'message' => 'Student Added Successfully'
        ]);
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);
    
        if (!$student) {
            return response([
                'status' => 401,
                'message' => 'Student Not Found'
            ]);
        }
    
        $valid = $request->validate([
            'name' => 'required|string|max:30',
            'age' => 'required|integer',
            'sibling_id' => 'required|integer',
            'classroom_id' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);
    
        $image_path = $student->image;
    
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('image', 'public');
    
            if ($image_path) {
                Storage::delete($student->image);
            }
        }

        $student->update([
            'name' => $valid['name'],
            'age' => $valid['age'],
            'sibling_id' => $valid['sibling_id'],
            'classroom_id' => $valid['classroom_id'],
            'image' => $image_path,
        ]);
    
        return $student;
    }

    public function delete($id)
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

    public function creationData() 
    {
        $siblings = Sibling::all();
        $classrooms = Classroom::all();

        return response([
            'status' => 200,
            'parents' => $siblings,
            'classrooms' => $classrooms
        ]);
    }

    public function storeStatus(Request $request, $id) {
        $student = Student::find($id);

        if(!$student) {
            return response([
                'status' => 404,
                'message' => 'Student Not Found'
            ]);
        }

        $validator = $request->validate([
            'status' => 'Required|string'
        ]);

        $student->update([
            'status' => $validator['status']
        ]);

        return response([
            'status' => 201,
            'message' => 'Status Added Successfully'
        ]);
    }
}
