<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\hash;
use App\Models\Administrator;
use App\Models\Sibling;
use App\Models\Teacher;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|unique:admin,email',
            'password' => 'required|string|confirmed',
        ]);

        $admin = Administrator::create([
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'role' => 'admin'
        ]);

        $token = $admin->createToken('myapptoken')->plainTextToken;

        $response = [
            'admin' => $admin,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|string'
        ]);

        $role = $fields['role'];
        $user = null;
    
        switch ($role) {
            case 'admin':
                $user = Administrator::where('email', $fields['email'])->first();
                break;
            case 'teacher':
                $user = Teacher::where('email', $fields['email'])->first();
                break;
            case 'sibling':
                $user = Sibling::where('email', $fields['email'])->first();
                break;
            default:
                return response([
                    'message' => 'Invalid Role'
                ], 400);
        }

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Invalid Creds'
            ], 401);
        }
    
        $token = $user->createToken('myapptoken')->plainTextToken;
    
        $response = [
            'user' => $user,
            'token' => $token
        ];
    
        return response($response, 201);
    }

    public function logout(request $request)
    {
        auth()->user()->tokens()->delete();

        return response([
            'status' => 200,
            'message' => 'Logout Successfully'
        ]);
    }
}