<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:100',
            'role' => 'required|string|in:user,admin',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        User::create([
            'name' => $request->name,
            'role' => $request->role,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]); 

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credencials = $request->only('email', 'password');

        try {
            if(!$token = JWTAuth::attempt($credencials)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            return response()->json(['token' => $token], 200);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Could not create token', 'error' => $e], 500);
        }
    }

    public function getUser() {
        $user = JWTAuth::user();
        return response()->json($user, 200);
    }

    public function logout() {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'User logged out successfully'], 200);
    }
}
