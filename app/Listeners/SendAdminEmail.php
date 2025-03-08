<?php

namespace App\Listeners;


use App\Events\PostCreated;
use Illuminate\Support\Facades\Mail;

class SendAdminEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PostCreated $event): void
    {
        // Retrieve all admin users (or just one admin, as needed)
        $admins = \App\Models\User::where('role', 'admin')->get();

        // For each admin, send an notification. You could also use notifications here.
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\NewPostNotification($event->post));
        }
    }
}
