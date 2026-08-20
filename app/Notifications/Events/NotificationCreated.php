<?php

namespace App\Notifications\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly int $notificationId,
        public readonly int $senderId,
        public readonly array $targets // ['users'=>[], 'roles'=>[]]
    ) {}
}
