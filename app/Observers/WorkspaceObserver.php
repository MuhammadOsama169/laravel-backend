<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class WorkspaceObserver
{
    /**
     * Handle the Workspace "created" event.
     */    
    public function creating(Workspace $workspace): void
    {
        $user = $workspace->user;
        $workspaceCount = $user->workspaces()->count();

        // Log the count for debugging console log
        // Log::debug('User workspace count: ' . $workspaceCount);
    
        if ($user->role === 'basic_user' && $workspaceCount >= 3) {
            throw new HttpException(
                422,
                "Basic plan users can only have a maximum of 3 workspaces. Please upgrade your plan for more workspaces."
            );
        }
    }

    public function created(Workspace $workspace): void
    {
        $workspace->setting = [
            'security_settings' => [
                'whitelisted_countries' => false,
                'whitelisted_emails' => false,
            ]
        ];
        // Take the string 'WORKSPACE-' and append the value of $workspace->id to it.
        $workspace->ref_id = 'WORKSPACE-' . $workspace->id;
        $workspace->save();
    }


    /**
     * Handle the Workspace "updated" event.
     */
    public function updated(Workspace $workspace)
    {
        if ($workspace->isDirty()) {
            // isDirty checks if any fields are edited
            AuditLog::create([
                'user_id' => Auth::id(),
                'model' => 'Workspace',
                'model_id' => $workspace->id,
                'changes' => json_encode($workspace->getChanges()),
                // getChanges() retrieves only the modified attributes
                // json_encode()   Converts PHP arrays to a JSON string for database storage
            ]);
        }
    }

    public function deleting(Workspace $workspace)
    {
        if ($workspace->user->workspaces()->count() === 1) {
            throw new \Exception("You cannot delete your last workspace.");
        }
    }
    /**
     * Handle the Workspace "deleted" event.
     */

    //delete related tasks when workspace is deleted
    public function deleted(Workspace $workspace): void
    {
        // $workspace->tasks()->delete();
    }

    /**
     * Handle the Workspace "restored" event.
     */
    public function restored(Workspace $workspace): void
    {
        //
    }

    /**
     * Handle the Workspace "force deleted" event.
     */
    public function forceDeleted(Workspace $workspace): void
    {
        //
    }
}
