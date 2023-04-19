<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with('classroom', 'teacher', 'sibling')->get()->map(function ($student) {
            $student->image_url = asset('storage/' . $student->image);
            return $student;
        });
        return $students;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required|string|max:30',
            'age' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'classroom_id' => 'required',
            'teacher_id' => 'required',
            'sibling_id' => 'required',
        ]);

        $image = $request->file('image');
        $tempPath = $image->store('temp', 'public');
        
        $newPath = 'storage/app/images/' . $image->getClientOriginalName();
        Storage::disk('local')->move($tempPath, $newPath);  

        Student::create([
            'name' => $valid['name'],
            'age' => $valid['age'],
            'sibling_id' => $valid['sibling_id'],
            'teacher_id' => $valid['teacher_id'],
            'classroom_id' => $valid['classroom_id'],
            'image' => $newPath,
        ]);

        return response([
            'status' => 201,
            'message' => 'Student Added Successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $student = Student::find($id);

        if(empty($student)) {
            return response([
                'status' => '404',
                'message' => 'Student Not Found'
            ]);
        }

        return $student;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if(empty($student)) {
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

        if(Storage::disk()->exists($currentImagePath)) {
            Storage::disk()->delete($currentImagePath);
        }

        return $student;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if(Student::destroy($id)) {
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
