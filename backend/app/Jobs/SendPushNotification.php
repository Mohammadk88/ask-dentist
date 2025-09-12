<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\FCMService;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SendPushNotification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $timeout = 60;

    protected $userIds;
    protected $title;
    protected $body;
    protected $data;
    protected $channel;

    /**
     * Create a new job instance.
     */
    public function __construct(
        array $userIds,
        string $title,
        string $body,
        array $data = [],
        ?string $channel = null
    ) {
        $this->userIds = $userIds;
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
        $this->channel = $channel;
    }

    /**
     * Execute the job.
     */
    public function handle(FCMService $fcmService): void
    {
        try {
            $users = User::whereIn('id', $this->userIds)
                        ->whereNotNull('fcm_tokens')
                        ->get();

            $successCount = 0;
            $failureCount = 0;

            foreach ($users as $user) {
                $result = $fcmService->sendToUser(
                    $user,
                    $this->title,
                    $this->body,
                    $this->data,
                    $this->channel
                );

                if ($result) {
                    $successCount++;
                } else {
                    $failureCount++;
                }
            }

            Log::info('Push notification job completed', [
                'title' => $this->title,
                'users_targeted' => count($this->userIds),
                'users_with_tokens' => $users->count(),
                'success_count' => $successCount,
                'failure_count' => $failureCount
            ]);

        } catch (\Exception $e) {
            Log::error('Push notification job failed', [
                'title' => $this->title,
                'users_count' => count($this->userIds),
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Push notification job permanently failed', [
            'title' => $this->title,
            'users_count' => count($this->userIds),
            'error' => $exception->getMessage()
        ]);
    }
}
