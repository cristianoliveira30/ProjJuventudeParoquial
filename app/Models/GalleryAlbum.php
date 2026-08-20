<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryAlbum extends Model
{
    protected $fillable = ['title', 'slug', 'description', 'event_date', 'cover_image', 'published_at', 'author_id'];

    protected function casts(): array
    {
        return ['event_date' => 'date', 'published_at' => 'datetime'];
    }

    public function photos()
    {
        return $this->hasMany(GalleryPhoto::class)->orderBy('sort_order')->orderBy('id');
    }

    public function firstPhoto()
    {
        return $this->hasOne(GalleryPhoto::class)->ofMany(['sort_order' => 'min', 'id' => 'min']);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function coverImageUrl(): ?string
    {
        $path = $this->cover_image ?: $this->firstPhoto?->image_path;

        return $path ? Storage::disk('public')->url($path) : null;
    }
}
