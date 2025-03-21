<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewPostNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $post;

    /**
     * Create a new notification instance.
     */
    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Post Published: ' . $this->post->title)
                    ->greeting('Hello!')
                    ->line('A new post has just been published on our site.')
                    ->line('Title: ' . $this->post->title)
                    ->action('Read Post', env('BASE_URL') . '/posts/' . $this->post->id)
                    ->line('Thank you for following our updates!');
    }
}
