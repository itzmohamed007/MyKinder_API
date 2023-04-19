<?php

namespace App\Http\Controllers;

use App\Models\Sibling;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;

class SiblingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Sibling::with('children')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $parent = Sibling::with('children')->find($id);

        if(empty($parent)) {
            return response([
                'status' => '404',
                'message' => 'Parent Account Not Found'
            ]);
        }

        return $parent;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $parent = Sibling::find($id);

        if(empty($parent)) {
            return response([
                'status' => '404',
                'message' => 'Parent Account Not Found'
            ]);
        }

        $valid = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email',
            'phone' => 'required|string|min:10|max:14'
        ]);
        
        $parent->update($valid);

        return response([
            'status' => '200',
            'message' => 'Parent Account Updated Successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if(Sibling::destroy($id)) {
            return response([
                'status' => '200',
                'message' => 'Parent Account Deleted Successfully'
            ]);
        }
    }
}
