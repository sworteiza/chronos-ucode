<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function regist(Request $request)
    {
        $input = $request->validate([
            'login' => 'required|string|unique:users,login',
            'email' => 'required|string|unique:users,email',
            'full_name' => 'required|string',
            'password' => 'required|string|confirmed'

        ]);

        $user = User::create([
            'login' => $input['login'],
            'email' => $input['email'],
            'full_name' => $input['full_name'],
            'password' => Hash::make($input['password'])
        ]);

        $token = $user->createToken('userToken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
}
