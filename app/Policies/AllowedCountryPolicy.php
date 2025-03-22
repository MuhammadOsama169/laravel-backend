<?php

namespace App\Policies;

use App\Models\AllowedCountry;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AllowedCountryPolicy
{
public function modify(User $user, AllowedCountry $allowedCountry): Response
{
    // Make sure the workspace relationship is loaded, then check its user_id.
    return $user->id === $allowedCountry->workspace->user_id 
        ? Response::allow() 
        : Response::deny('You do not own this Workspace');
}

}
