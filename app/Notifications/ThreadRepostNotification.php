<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Thread;
use App\Models\User;

class ThreadRepostNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $thread;
    public $reposter;

    /**
     * Create a new notification instance.
     */
    public function __construct(Thread $thread, User $reposter)
    {
        $this->thread = $thread;
        $this->reposter = $reposter;
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
            'type' => 'thread_repost',
            'message' => $this->reposter->username . ' reposted your thread',
            'thread_id' => $this->thread->id,
            'reposter' => [
                'id' => $this->reposter->id,
                'username' => $this->reposter->username,
            ],
        ];
    }
}
