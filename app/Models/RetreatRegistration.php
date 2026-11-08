<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;

class RetreatRegistration extends Model
{
    public const STATUSES = [
        'pending' => 'Pendente',
        'confirmed' => 'Confirmada',
        'waitlist' => 'Lista de espera',
        'cancelled' => 'Cancelada',
    ];

    protected $fillable = [
        'name', 'birth_date', 'email', 'phone', 'community', 'mother_name', 'father_name',
        'guardian_name', 'guardian_phone', 'emergency_contact', 'emergency_contact_name',
        'emergency_contact_phone', 'allergies', 'medical_conditions', 'medications',
        'dietary_restrictions', 'status', 'internal_notes', 'consented_at',
        'health_data_consented_at',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'allergies' => 'encrypted',
            'medical_conditions' => 'encrypted',
            'medications' => 'encrypted',
            'dietary_restrictions' => 'encrypted',
            'consented_at' => 'datetime',
            'health_data_consented_at' => 'datetime',
        ];
    }

    public function retreat()
    {
        return $this->belongsTo(Retreat::class);
    }

    public function attendanceRecords()
    {
        return $this->morphMany(RetreatAttendanceRecord::class, 'attendee');
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? 'Pendente';
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
