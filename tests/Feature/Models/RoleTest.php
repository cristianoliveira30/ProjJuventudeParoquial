<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

it('can be mass assigned with the columns defined by its migration', function () {
    $role = new Role([
        'name' => 'Administrador',
        'sigla' => 'ADMIN',
        'description' => 'Administrador do sistema',
    ]);

    expect($role->name)->toBe('Administrador')
        ->and($role->sigla)->toBe('ADMIN')
        ->and($role->description)->toBe('Administrador do sistema');
});

it('has many users', function () {
    $relation = (new Role())->users();

    expect($relation)->toBeInstanceOf(HasMany::class)
        ->and($relation->getRelated())->toBeInstanceOf(User::class)
        ->and($relation->getForeignKeyName())->toBe('role_id')
        ->and($relation->getLocalKeyName())->toBe('id');
});
