<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'You were logged out'
        ];
    }
    public function login(Request $request)
    {
        $input = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string'

        ]);

        $user = User::where('login', $input['login'])->first();
        if (!$user || !Hash::check($input['password'], $user->password)) {
            return response([
                'message' => 'Incorrect login or password'
            ], 401);
        }

        $token = $user->createToken('userToken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
}
