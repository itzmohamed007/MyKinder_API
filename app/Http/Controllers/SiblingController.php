<?php

namespace App\Http\Controllers;

use App\Models\Sibling;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;

class SiblingController extends Controller
{
    public function index()
    {
        return Sibling::with('children')->get();
    }

    public function show($id)
    {
        $sibling = Sibling::with('children')->find($id);

        if (!$sibling) {
            return response([
                'status' => 404,
                'message' => 'Parent Account Not Found'
            ]);
        }

        $sibling->children->map(function ($child) {
            $child->image_url = asset('storage/' . $child->image);
            return $child;
        });

        return $sibling;
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|unique:siblings,email',
            'phone' => 'required|min:10|max:14'
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

    public function update(Request $request, $id)
    {
        $sibling = sibling::find($id);

        if (empty($sibling)) {
            return response([
                'status' => '404',
                'message' => 'sibling Not Found'
            ]);
        }

        $valid = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|unique:siblings,email',
            'phone' => 'required|min:10|max:14'
        ]);

        $sibling->update([
            'name' => $valid['name'],
            'email' => $valid['email'],
            'phone' => $valid['phone']
        ]);

        return response([
            'status' => '201',
            'message' => 'Sibling Account Created Successfully'
        ]);
    }

    public function delete($id)
    {
        if (Sibling::destroy($id)) {
            return response([
                'status' => '200',
                'message' => 'Parent Account Deleted Successfully'
            ]);
        }
    }
}
