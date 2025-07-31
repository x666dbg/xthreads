<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Thread;
use App\Models\User;

class ThreadReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $thread;
    public $reply;
    public $replier;

    /**
     * Create a new notification instance.
     */
    public function __construct(Thread $thread, Thread $reply, User $replier)
    {
        $this->thread = $thread;
        $this->reply = $reply;
        $this->replier = $replier;
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
            'type' => 'thread_reply',
            'message' => $this->replier->username . ' replied to your thread',
            'thread_id' => $this->thread->id,
            'reply_id' => $this->reply->id,
            'replier' => [
                'id' => $this->replier->id,
                'username' => $this->replier->username,
            ],
        ];
    }
}
