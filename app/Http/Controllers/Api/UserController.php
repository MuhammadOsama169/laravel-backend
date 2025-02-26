<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming request data
        // dd($request->all());
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);
        $data= $data+ ['password'=>Hash::make(Str::random(6,9))];
        // Create the user (if you have password handling, adjust accordingly)
        

        // Return a JSON response
        return response()->json([
            'message' => 'User created successfully',

        ], 201);
    }
}
