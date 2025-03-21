<?php

namespace App\Policies;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MoviePolicy
{

    public function modify(User $user, Movie $movie): Response
    {
        return $user->id === $movie->user_id ? Response::allow() : Response::deny('You do not own this post');
    }

}
