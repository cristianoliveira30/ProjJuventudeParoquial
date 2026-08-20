<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PastoralGroup extends Model
{
    public const TYPES = [
        'pastoral' => 'Pastoral',
        'movimento' => 'Movimento',
        'ministerio' => 'Ministério',
        'grupo' => 'Grupo',
    ];

    protected $fillable = [
        'name',
        'slug',
        'type',
        'summary',
        'description',
        'meeting_schedule',
        'meeting_location',
        'public_contact',
        'cover_image',
        'published_at',
        'coordinator_id',
        'author_id',
    ];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function coordinator()
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function coverImageUrl(): ?string
    {
        return $this->cover_image ? Storage::disk('public')->url($this->cover_image) : null;
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? 'Grupo';
    }
}
