<?php

namespace App\Http\Controllers;

use App\Models\profile;
use App\Http\Requests\StoreprofileRequest;
use App\Http\Requests\UpdateprofileRequest;
use App\Models\User;

class ProfileController extends Controller
{


    public function show($userId)
    {
        // eager load relashions from user instead of lazy loading solving n1 problem
        // we use with methd for related data that isn't stored in the same table but has relshions with it so get those relashions data
        $user = User::with(['posts', 'movies','workspaces'])->findOrFail($userId);

        $data = [
            'username' => $user->name,
            'email'    => $user->email,
            'posts'    => $user->posts,  
            'movies'   => $user->movies,  
            'workspace'   => $user->workspaces,  
        ];

        return $data;
    }


}
