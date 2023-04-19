<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    public function index()
    {
        return Classroom::with('teacher', 'students')->get();
    }

    /**
     * Store a newly created resource in storage.
    */
    public function store(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required|string|min:10|max:40',
            'teacher_id' => 'required'
        ]);

        Classroom::create($valid);

        return response([
            'status' => '201',
            'message' => 'Classroom Added Successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $classroom = Classroom::with('students')->find($id);

        if(empty($classroom)) {
            return response([
                'status' => '404',
                'message' => 'Classroom Not Found'
            ]);
        }

        return $classroom;
        // return $classroom->with('students')->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $classroom = Classroom::find($id);

        if(empty($classroom)) {
            return response([
                'status' => '404',
                'message' => 'Classroom Not Found'
            ]);
        }

        $valid = $request->validate([
            'name' => 'required|string|min:10|max:40',
            'teacher_id' => 'required'
        ]);

        $classroom->update($valid);

        return $classroom;

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if(Classroom::destroy($id)) {
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
