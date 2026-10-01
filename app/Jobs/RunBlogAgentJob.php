<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\AiAgent\BlogContentAgent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Asynchronous Background Job to execute the AI Agent.
 *
 * Prevents HTTP request timeouts and 503 errors from locking the admin panel UI.
 */
class RunBlogAgentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 2;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 5;

    public function __construct(
        public readonly string $sessionId,
        public readonly string $breedKey,
        public readonly string $breedLabel,
        public readonly bool $forceRefresh = false
    ) {}

    public function handle(BlogContentAgent $agent): void
    {
        Log::info('RunBlogAgentJob started', [
            'session_id' => $this->sessionId,
            'breed_key'  => $this->breedKey,
        ]);

        try {
            $agent->runTopicDiscovery(
                $this->sessionId,
                $this->breedKey,
                $this->breedLabel,
                $this->forceRefresh
            );
        } catch (\Throwable $e) {
            Log::error('RunBlogAgentJob failed', [
                'session_id' => $this->sessionId,
                'error'      => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
