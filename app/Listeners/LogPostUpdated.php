<?php

namespace App\Listeners;

use App\Events\PostUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogPostUpdated implements ShouldQueue
{
    public function handle(PostUpdated $event): void
    {
        Log::info('Post updated', [
            'post_id' => $event->post->id,
            'title' => $event->post->title,
            'user_id' => $event->post->user_id,
        ]);
    }
}
