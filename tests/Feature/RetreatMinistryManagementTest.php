<?php

use App\Models\Retreat;
use App\Models\RetreatMinistry;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates the three required ministries idempotently for a retreat', function () {
    $retreat = Retreat::query()->create([
        'title' => 'Retiro de teste',
        'slug' => 'retiro-de-teste',
        'description' => 'Retiro usado para validar os ministérios.',
        'starts_at' => now()->addMonth(),
    ]);

    RetreatMinistry::ensureDefaultsFor($retreat);
    RetreatMinistry::ensureDefaultsFor($retreat);

    expect($retreat->ministries()->count())->toBe(3)
        ->and($retreat->ministries()->pluck('name')->all())->toBe(['Artes', 'Música', 'Cozinha'])
        ->and($retreat->ministries()->where('is_active', true)->count())->toBe(3);
});
