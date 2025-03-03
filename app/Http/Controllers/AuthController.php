<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterAuthRequest;
use App\Http\Requests\Auth\LoginAuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterAuthRequest $request)
    {
        //validate

        //register
        $user = User::create($request->validated());
        //issue token and return values
        $token = $user->createToken($request->name);
        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function login(LoginAuthRequest $request)
    {
        //validate
        // $request->validate([
        //     'email' => 'required|email|exists:users',
        //     'password' => 'required'
        // ]);

        // $user = User::where('email', $request->email)->first();
        $fields = $request->validated();
//search user table email field and find and return first match
        $user = User::where('email', $fields['email'])->first();


        // if user does not exist or email/pass wrong
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => "The provided credentials are incorrect..."
            ], 422);
        }

        //issue token and return values when condtion true and validation valid
        $token = $user->createToken($user->name);
        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return [
            'message' => "You are logged out"
        ];
    }
}
