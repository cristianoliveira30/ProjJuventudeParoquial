<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;

class RetreatServant extends Model
{
    public const STATUSES = ['pending' => 'Pendente', 'approved' => 'Aprovado', 'waitlist' => 'Lista de espera', 'declined' => 'Recusado', 'cancelled' => 'Cancelado'];

    protected $fillable = [
        'preferred_ministry_id', 'assigned_ministry_id', 'name', 'birth_date', 'email', 'phone',
        'community', 'mother_name', 'father_name', 'guardian_name', 'guardian_phone',
        'emergency_contact_name', 'emergency_contact_phone', 'allergies', 'medical_conditions',
        'medications', 'dietary_restrictions', 'previous_retreat_year', 'status', 'internal_notes',
        'consented_at', 'health_data_consented_at', 'approved_at', 'approved_by',
    ];

    protected function casts(): array
    {
        return ['allergies' => 'encrypted', 'medical_conditions' => 'encrypted', 'medications' => 'encrypted', 'dietary_restrictions' => 'encrypted', 'birth_date' => 'date', 'previous_retreat_year' => 'integer', 'consented_at' => 'datetime', 'health_data_consented_at' => 'datetime', 'approved_at' => 'datetime'];
    }

    public function retreat()
    {
        return $this->belongsTo(Retreat::class);
    }

    public function preferredMinistry()
    {
        return $this->belongsTo(RetreatMinistry::class, 'preferred_ministry_id');
    }

    public function assignedMinistry()
    {
        return $this->belongsTo(RetreatMinistry::class, 'assigned_ministry_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function attendanceRecords()
    {
        return $this->morphMany(RetreatAttendanceRecord::class, 'attendee');
    }

    public function ageAt(CarbonInterface $date): int
    {
        return (int) $this->birth_date->diffInYears($date);
    }

    public function hasHealthInformation(): bool
    {
        return collect([$this->allergies, $this->medical_conditions, $this->medications, $this->dietary_restrictions])
            ->contains(fn ($value) => filled($value));
    }
}
