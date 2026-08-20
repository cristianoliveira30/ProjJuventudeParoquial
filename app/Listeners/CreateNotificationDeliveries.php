<?php

namespace App\Listeners;

use App\Events\NotificationSent;
use App\Notifications\Events\NotificationCreated;
use App\Notifications\Services\NotificationRecipientResolver;
use Illuminate\Support\Facades\DB;

class CreateNotificationDeliveries
{
    public function __construct(
        protected NotificationRecipientResolver $resolver
    ) {}

    public function handle(NotificationCreated $event): void
    {
        // 1) Resolve destinatários finais (IDs únicos)
        $userIds = $this->resolver->resolve($event->targets);

        if ($userIds->isEmpty()) {
            return;
        }

        $now = now();

        // 2) Busca a notificação "mãe" uma vez só
        $notification = DB::table('notifications')
            ->where('id', $event->notificationId)
            ->first(['id', 'title', 'message', 'created_by', 'created_at']);

        if (!$notification) {
            return;
        }

        $payload = [
            'title'      => $notification->title,
            'message'    => $notification->message,
            'created_by' => (int) $notification->created_by,
            'created_at' => (string) $notification->created_at,
        ];

        // 3) Faz insert das deliveries e dispara broadcast por usuário (sem fila)
        //    Importante: se quiser evitar duplicação caso o usuário esteja em várias regras,
        //    o resolver já garante unique. Ainda assim, banco pode ter unique index (notification_id, user_id).
        $rows = $userIds->map(fn(int $userId) => [
            'notification_id' => $event->notificationId,
            'user_id'         => $userId,
            'read_at'         => null,
            'delivered_at'    => $now,
            'created_at'      => $now,
            'updated_at'      => $now,
        ])->all();

        DB::table('notification_deliveries')->insert($rows);

        foreach ($userIds as $userId) {
            NotificationSent::dispatch(
                (int) $userId,
                (int) $event->notificationId,
                $payload
            );
        }
    }
}
