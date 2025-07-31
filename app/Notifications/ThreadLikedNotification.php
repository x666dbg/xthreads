<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Thread;
use App\Models\User;

class ThreadLikedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $thread;
    public $liker;

    /**
     * Create a new notification instance.
     */
    public function __construct(Thread $thread, User $liker)
    {
        $this->thread = $thread;
        $this->liker = $liker;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'thread_liked',
            'message' => $this->liker->username . ' liked your thread',
            'thread_id' => $this->thread->id,
            'liker' => [
                'id' => $this->liker->id,
                'username' => $this->liker->username,
            ],
        ];
    }
}
