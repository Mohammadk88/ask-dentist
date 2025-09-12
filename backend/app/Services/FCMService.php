<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Exception\MessagingException;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class FCMService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory);

        // Use service account file or JSON
        if (config('fcm.credentials.service_account_array')) {
            $factory = $factory->withServiceAccount(config('fcm.credentials.service_account_array'));
        } elseif (file_exists(config('fcm.credentials.service_account'))) {
            $factory = $factory->withServiceAccount(config('fcm.credentials.service_account'));
        }

        if (config('fcm.project_id')) {
            $factory = $factory->withProjectId(config('fcm.project_id'));
        }

        $this->messaging = $factory->createMessaging();
    }

    /**
     * Send push notification to specific user
     */
    public function sendToUser(User $user, string $title, string $body, array $data = [], ?string $channel = null): bool
    {
        if (!$user->fcm_tokens || empty($user->fcm_tokens)) {
            Log::warning('User has no FCM tokens', ['user_id' => $user->id]);
            return false;
        }

        $tokens = is_array($user->fcm_tokens) ? $user->fcm_tokens : json_decode($user->fcm_tokens, true);
        
        return $this->sendToTokens($tokens, $title, $body, $data, $channel);
    }

    /**
     * Send push notification to multiple tokens
     */
    public function sendToTokens(array $tokens, string $title, string $body, array $data = [], ?string $channel = null): bool
    {
        if (empty($tokens)) {
            return false;
        }

        try {
            $notification = Notification::create($title, $body);
            
            $message = CloudMessage::new()
                ->withNotification($notification)
                ->withData($data);

            // Add Android specific config
            $androidConfig = [
                'notification' => [
                    'sound' => config('fcm.default_sound'),
                    'icon' => config('fcm.default_icon'),
                    'color' => config('fcm.default_color'),
                ]
            ];

            if ($channel) {
                $androidConfig['notification']['channel_id'] = $channel;
            }

            $message = $message->withAndroidConfig(AndroidConfig::fromArray($androidConfig));

            // Add iOS specific config
            $apnsConfig = [
                'payload' => [
                    'aps' => [
                        'sound' => 'default',
                        'badge' => 1,
                    ]
                ]
            ];

            $message = $message->withApnsConfig(ApnsConfig::fromArray($apnsConfig));

            // Send to multiple tokens
            $result = $this->messaging->sendMulticast($message, $tokens);

            $successCount = $result->successes()->count();
            $failureCount = $result->failures()->count();

            Log::info('FCM notification sent', [
                'title' => $title,
                'tokens_count' => count($tokens),
                'success_count' => $successCount,
                'failure_count' => $failureCount
            ]);

            // Remove invalid tokens
            if ($failureCount > 0) {
                $invalidTokens = [];
                foreach ($result->failures() as $failure) {
                    $invalidTokens[] = $failure->target()->value();
                }
                $this->removeInvalidTokens($invalidTokens);
            }

            return $successCount > 0;

        } catch (MessagingException $e) {
            Log::error('FCM messaging error', [
                'title' => $title,
                'error' => $e->getMessage()
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('FCM general error', [
                'title' => $title,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send notification to topic
     */
    public function sendToTopic(string $topic, string $title, string $body, array $data = [], ?string $channel = null): bool
    {
        try {
            $notification = Notification::create($title, $body);
            
            $message = CloudMessage::withTarget('topic', $topic)
                ->withNotification($notification)
                ->withData($data);

            // Add platform specific configs
            $androidConfig = [
                'notification' => [
                    'sound' => config('fcm.default_sound'),
                    'icon' => config('fcm.default_icon'),
                    'color' => config('fcm.default_color'),
                ]
            ];

            if ($channel) {
                $androidConfig['notification']['channel_id'] = $channel;
            }

            $message = $message->withAndroidConfig(AndroidConfig::fromArray($androidConfig));

            $result = $this->messaging->send($message);

            Log::info('FCM topic notification sent', [
                'topic' => $topic,
                'title' => $title,
                'message_id' => $result
            ]);

            return true;

        } catch (MessagingException $e) {
            Log::error('FCM topic messaging error', [
                'topic' => $topic,
                'title' => $title,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Subscribe user to topic
     */
    public function subscribeToTopic(User $user, string $topic): bool
    {
        if (!$user->fcm_tokens || empty($user->fcm_tokens)) {
            return false;
        }

        $tokens = is_array($user->fcm_tokens) ? $user->fcm_tokens : json_decode($user->fcm_tokens, true);

        try {
            $this->messaging->subscribeToTopic($topic, $tokens);
            
            Log::info('User subscribed to topic', [
                'user_id' => $user->id,
                'topic' => $topic,
                'tokens_count' => count($tokens)
            ]);

            return true;
        } catch (MessagingException $e) {
            Log::error('Failed to subscribe to topic', [
                'user_id' => $user->id,
                'topic' => $topic,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Unsubscribe user from topic
     */
    public function unsubscribeFromTopic(User $user, string $topic): bool
    {
        if (!$user->fcm_tokens || empty($user->fcm_tokens)) {
            return false;
        }

        $tokens = is_array($user->fcm_tokens) ? $user->fcm_tokens : json_decode($user->fcm_tokens, true);

        try {
            $this->messaging->unsubscribeFromTopic($topic, $tokens);
            
            Log::info('User unsubscribed from topic', [
                'user_id' => $user->id,
                'topic' => $topic,
                'tokens_count' => count($tokens)
            ]);

            return true;
        } catch (MessagingException $e) {
            Log::error('Failed to unsubscribe from topic', [
                'user_id' => $user->id,
                'topic' => $topic,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Add FCM token to user
     */
    public function addTokenToUser(User $user, string $token): bool
    {
        $existingTokens = $user->fcm_tokens ? 
            (is_array($user->fcm_tokens) ? $user->fcm_tokens : json_decode($user->fcm_tokens, true)) : 
            [];

        if (!in_array($token, $existingTokens)) {
            $existingTokens[] = $token;
            $user->update(['fcm_tokens' => $existingTokens]);
            
            Log::info('FCM token added to user', [
                'user_id' => $user->id,
                'token_count' => count($existingTokens)
            ]);

            return true;
        }

        return false;
    }

    /**
     * Remove FCM token from user
     */
    public function removeTokenFromUser(User $user, string $token): bool
    {
        if (!$user->fcm_tokens) {
            return false;
        }

        $existingTokens = is_array($user->fcm_tokens) ? $user->fcm_tokens : json_decode($user->fcm_tokens, true);
        $filteredTokens = array_filter($existingTokens, fn($t) => $t !== $token);

        if (count($filteredTokens) !== count($existingTokens)) {
            $user->update(['fcm_tokens' => array_values($filteredTokens)]);
            
            Log::info('FCM token removed from user', [
                'user_id' => $user->id,
                'remaining_tokens' => count($filteredTokens)
            ]);

            return true;
        }

        return false;
    }

    /**
     * Remove invalid tokens from all users
     */
    protected function removeInvalidTokens(array $invalidTokens): void
    {
        if (empty($invalidTokens)) {
            return;
        }

        $users = User::whereNotNull('fcm_tokens')->get();

        foreach ($users as $user) {
            $userTokens = is_array($user->fcm_tokens) ? $user->fcm_tokens : json_decode($user->fcm_tokens, true);
            $validTokens = array_filter($userTokens, fn($token) => !in_array($token, $invalidTokens));

            if (count($validTokens) !== count($userTokens)) {
                $user->update(['fcm_tokens' => array_values($validTokens)]);
            }
        }

        Log::info('Invalid FCM tokens cleaned up', [
            'invalid_tokens_count' => count($invalidTokens)
        ]);
    }
}