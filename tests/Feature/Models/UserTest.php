<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

it('belongs to a role', function () {
    $relation = (new User())->role();

    expect($relation)->toBeInstanceOf(BelongsTo::class)
        ->and($relation->getRelated())->toBeInstanceOf(Role::class)
        ->and($relation->getForeignKeyName())->toBe('role_id')
        ->and($relation->getOwnerKeyName())->toBe('id');
});

it('hashes plain text passwords through the model cast', function () {
    $user = new User();
    $user->password = 'Senha@123';

    expect($user->password)->not->toBe('Senha@123')
        ->and(Hash::check('Senha@123', $user->password))->toBeTrue();
});
