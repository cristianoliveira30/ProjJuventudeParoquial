<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetreatAttendanceRecord extends Model
{
    protected $fillable = ['attendee_type', 'attendee_id', 'present_at', 'marked_by', 'notes'];

    protected function casts(): array
    {
        return ['present_at' => 'datetime'];
    }

    public function session()
    {
        return $this->belongsTo(RetreatAttendanceSession::class, 'retreat_attendance_session_id');
    }

    public function attendee()
    {
        return $this->morphTo();
    }

    public function marker()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }
}
