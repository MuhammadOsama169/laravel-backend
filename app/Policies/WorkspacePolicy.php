<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Auth\Access\Response;

class WorkspacePolicy
{
       // allowing control based on auth
       public function modify(User $user, Workspace $workspace): Response
       {
           return $user->id === $workspace->user_id ? Response::allow() : Response::deny('You do not own this post');
       }
}
