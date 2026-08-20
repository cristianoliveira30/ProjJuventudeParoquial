<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationDeliveries extends Model
{
    protected $table = 'notification_deliveries';

    protected $fillable = [
        'notification_id',
        'user_id',
        'read_at',
        'delivered_at',
    ];

    protected $casts = [
        'read_at' => 'datetime:d/m/Y H:i',
        'delivered_at' => 'datetime:d/m/Y H:i',
    ];

    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notification_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
