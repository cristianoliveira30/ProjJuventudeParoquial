<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetreatAttendanceSession extends Model
{
    public const AUDIENCES = ['participants' => 'Participantes', 'servants' => 'Servos'];

    public const DIRECTIONS = ['departure' => 'Partida', 'return' => 'Retorno'];

    public const STATUSES = ['not_started' => 'Não iniciada', 'open' => 'Aberta', 'closed' => 'Encerrada'];

    protected $fillable = ['audience', 'direction', 'status', 'opened_at', 'closed_at', 'opened_by', 'closed_by'];

    protected function casts(): array
    {
        return ['opened_at' => 'datetime', 'closed_at' => 'datetime'];
    }

    public function retreat()
    {
        return $this->belongsTo(Retreat::class);
    }

    public function records()
    {
        return $this->hasMany(RetreatAttendanceRecord::class);
    }

    public function opener()
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function closer()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
}
