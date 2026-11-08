<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RoomBooking extends Model
{
    public const STATUSES = ['pending' => 'Pendente', 'approved' => 'Aprovada', 'rejected' => 'Recusada', 'cancelled' => 'Cancelada'];

    protected $fillable = ['room_id', 'requested_by', 'title', 'purpose', 'starts_at', 'ends_at', 'status', 'decided_by', 'decided_at'];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'decided_at' => 'datetime'];
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /** Reservas aprovadas que ocupam a sala em algum momento de [$start, $end). */
    public function scopeConflicting(Builder $query, int $roomId, $start, $end, ?int $ignoreId = null): Builder
    {
        return $query->where('room_id', $roomId)
            ->where('status', 'approved')
            ->where('starts_at', '<', $end)
            ->where('ends_at', '>', $start)
            ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId));
    }
}
