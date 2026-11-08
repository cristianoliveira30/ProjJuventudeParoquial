<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Retreat extends Model
{
    public const SERVANT_LINK_OPEN = 'open';

    public const SERVANT_LINK_SCHEDULED = 'scheduled';

    public const SERVANT_LINK_CLOSED = 'closed';

    public const REGISTRATION_OPEN = 'open';

    public const REGISTRATION_SCHEDULED = 'scheduled';

    public const REGISTRATION_CLOSED = 'closed';

    protected $fillable = [
        'title', 'slug', 'summary', 'description', 'location', 'starts_at', 'ends_at',
        'registration_opens_at', 'registration_closes_at', 'capacity', 'public_contact',
        'cover_image', 'published_at', 'coordinator_id', 'author_id',
        'servant_registration_token', 'servant_registration_enabled',
        'servant_registration_opens_at', 'servant_registration_closes_at',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'registration_opens_at' => 'datetime',
            'registration_closes_at' => 'datetime',
            'published_at' => 'datetime',
            'capacity' => 'integer',
            'servant_registration_enabled' => 'boolean',
            'servant_registration_opens_at' => 'datetime',
            'servant_registration_closes_at' => 'datetime',
        ];
    }

    public function canBeManagedBy(User $user): bool
    {
        return $user->role?->sigla !== 'COORD' || $this->coordinator_id === $user->id;
    }

    public function isMinorOn(mixed $birthDate): bool
    {
        try {
            return Carbon::createFromFormat('Y-m-d', (string) $birthDate)->diffInYears($this->starts_at) < 18;
        } catch (\Throwable) {
            return false;
        }
    }

    public function coordinator()
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function registrations()
    {
        return $this->hasMany(RetreatRegistration::class);
    }

    public function ministries()
    {
        return $this->hasMany(RetreatMinistry::class)->orderBy('sort_order')->orderBy('name');
    }

    public function servants()
    {
        return $this->hasMany(RetreatServant::class);
    }

    public function attendanceSessions()
    {
        return $this->hasMany(RetreatAttendanceSession::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('starts_at', '>=', now());
    }

    public function isRegistrationOpen(): bool
    {
        return $this->registrationStatus() === self::REGISTRATION_OPEN;
    }

    public function servantRegistrationStatus(): string
    {
        $now = now();

        if (! $this->servant_registration_enabled || $this->servant_registration_closes_at?->isBefore($now)) {
            return self::SERVANT_LINK_CLOSED;
        }

        if ($this->servant_registration_opens_at?->isAfter($now)) {
            return self::SERVANT_LINK_SCHEDULED;
        }

        return self::SERVANT_LINK_OPEN;
    }

    public function registrationStatus(): string
    {
        $now = now();

        if (! $this->published_at || $this->published_at->isAfter($now) || ! $this->starts_at->isAfter($now)) {
            return self::REGISTRATION_CLOSED;
        }

        if ($this->registration_opens_at?->isAfter($now)) {
            return self::REGISTRATION_SCHEDULED;
        }

        if ($this->registration_closes_at?->isBefore($now)) {
            return self::REGISTRATION_CLOSED;
        }

        return self::REGISTRATION_OPEN;
    }

    public function coverImageUrl(): ?string
    {
        return $this->cover_image ? Storage::disk('public')->url($this->cover_image) : null;
    }
}
