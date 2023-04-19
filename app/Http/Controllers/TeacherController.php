<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    public function index()
    {
        return Teacher::all();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Teacher::find($id);
    }

    /**
     * Store a newly created resource in storage.
    */
    public function store(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|unique:teachers,email',
            'phone' => 'required|string|min:10|max:14',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'role' => 'required|string|max:10'
        ]);

        $image = $request->file('image');
        $tempPath = $image->store('temp', 'public');
        
        $newPath = 'storage/app/images/' . $image->getClientOriginalName();
        Storage::disk('local')->move($tempPath, $newPath);  
        
        Teacher::create([
            'name' => $valid['name'],
            'email' => $valid['email'],
            'phone' => $valid['phone'],
            'role' => $valid['role'],
            'password' => bcrypt($valid['name']),
            'image' => $newPath,
        ]);

        return response([
            'status' => 201,
            'message' => 'Teacher Account Created Successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $teacher = Teacher::find($id);

        if(empty($teacher)) {
            return response([
                'status' => '401',
                'message' => 'Teacher Account Not Found'
            ]);
        }

        $valid = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email',
            'phone' => 'required|string|min:10|max:14',
            'image' => 'required'
        ]);

        $currentImagePath = $teacher->image;
        $image = $request->file('image');
        $tempPath = $image->store('temp', 'public');
        
        $newPath = 'storage/app/images/' . $image->getClientOriginalName();
        Storage::disk('local')->move($tempPath, $newPath);

        $teacher->update([
            'name' => $valid['name'],
            'email' => $valid['email'],
            'phone' => $valid['phone'],
            'image' => $newPath,
        ]);

        if(Storage::disk()->exists($currentImagePath)) {
            Storage::disk()->delete($currentImagePath);
        }

        return $teacher;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Teacher::destroy($id);

        return ([
            'status' => '200',
            'Message' => 'Teacher Account Deleted Successfully'
        ]);
    }
}
