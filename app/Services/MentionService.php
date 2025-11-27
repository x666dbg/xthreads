<?php

namespace App\Services;

use App\Models\User;
use App\Models\Mention;
use App\Notifications\MentionNotification;
use Illuminate\Support\Facades\Notification;

class MentionService
{
    public function extractMentions(string $content): array
    {
        preg_match_all('/@([a-zA-Z0-9_]+)/', $content, $matches);
        return array_unique($matches[1]);
    }

    public function processMentions(string $content, $mentionable, User $mentioner): void
    {
        $usernames = $this->extractMentions($content);
        
        if (empty($usernames)) {
            return;
        }

        $users = User::whereIn('username', $usernames)->get();

        foreach ($users as $user) {
            if ($user->id === $mentioner->id) {
                continue;
            }

            $mention = Mention::create([
                'user_id' => $mentioner->id,
                'mentioned_user_id' => $user->id,
                'mentionable_type' => get_class($mentionable),
                'mentionable_id' => $mentionable->id,
            ]);

            $user->notify(new MentionNotification($mentioner, $mentionable));
        }
    }

    public function formatMentions(string $content): string
    {
        return preg_replace_callback('/@([a-zA-Z0-9_]+)/', function ($matches) {
            $username = $matches[1];
            $user = User::where('username', $username)->first();
            
            if ($user) {
                return '<a href="/' . $username . '" class="text-blue-400 hover:text-blue-300 hover:underline" style="display: inline; color: #60a5fa;">@' . $username . '</a>';
            }
            
            return '@' . $username;
        }, $content);
    }
}