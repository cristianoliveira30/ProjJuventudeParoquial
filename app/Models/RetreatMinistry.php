<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetreatMinistry extends Model
{
    public const DEFAULTS = ['Artes', 'Música', 'Cozinha'];

    protected $fillable = ['name', 'slug', 'description', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer', 'is_active' => 'boolean'];
    }

    public function retreat()
    {
        return $this->belongsTo(Retreat::class);
    }

    public function preferredServants()
    {
        return $this->hasMany(RetreatServant::class, 'preferred_ministry_id');
    }

    public function assignedServants()
    {
        return $this->hasMany(RetreatServant::class, 'assigned_ministry_id');
    }

    public static function ensureDefaultsFor(Retreat $retreat): void
    {
        foreach (self::DEFAULTS as $position => $name) {
            $retreat->ministries()->firstOrCreate(
                ['slug' => str($name)->slug()->toString()],
                ['name' => $name, 'sort_order' => $position + 1, 'is_active' => true]
            );
        }
    }
}
