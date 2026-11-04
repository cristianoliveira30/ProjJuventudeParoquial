<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class SendWebPushForUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $userId,
        public int $notificationId,
        public array $payload,
    ) {}

    public function handle(): void
    {
        $user = User::find($this->userId);
        if (!$user) return;

        // vem do HasPushSubscriptions
        $subs = $user->pushSubscriptions()->get();
        if ($subs->isEmpty()) return;

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => config('webpush.vapid.subject'),
                'publicKey' => config('webpush.vapid.public_key'),
                'privateKey' => config('webpush.vapid.private_key'),
            ],
        ]);

        $data = [
            'title' => $this->payload['title'] ?? 'Nova notificação',
            'body'  => $this->payload['message'] ?? '',
            'notification_id' => $this->notificationId,
            // dashboard exige notifications.send; a home abre para qualquer destinatário
            'url' => route('home'),
        ];

        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        foreach ($subs as $s) {
            $subscription = Subscription::create([
                'endpoint' => $s->endpoint,
                'publicKey' => $s->public_key,
                'authToken' => $s->auth_token,
                // o mais comum hoje é aes128gcm; se vier null, cai nele
                'contentEncoding' => $s->content_encoding ?: 'aes128gcm',
            ]);

            try {
                $report = $webPush->sendOneNotification($subscription, $json);

                if (method_exists($report, 'isSubscriptionExpired') && $report->isSubscriptionExpired()) {
                    $s->delete();
                }
            } catch (\Throwable $e) {
                Log::warning('WebPush falhou', [
                    'user_id' => $this->userId,
                    'endpoint' => $s->endpoint,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}