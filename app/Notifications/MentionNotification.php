<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class MentionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $mentioner;
    public $mentionable;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $mentioner, $mentionable)
    {
        $this->mentioner = $mentioner;
        $this->mentionable = $mentionable;
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
        $mentionableType = class_basename($this->mentionable);
        
        return [
            'type' => 'mention',
            'message' => $this->mentioner->username . ' mentioned you in a ' . strtolower($mentionableType),
            'mentioner' => [
                'id' => $this->mentioner->id,
                'username' => $this->mentioner->username,
            ],
            'mentionable_type' => get_class($this->mentionable),
            'mentionable_id' => $this->mentionable->id,
            'thread_id' => $this->mentionable->id,
            'thread_url' => route('threads.show', $this->mentionable->id),
        ];
    }
}
