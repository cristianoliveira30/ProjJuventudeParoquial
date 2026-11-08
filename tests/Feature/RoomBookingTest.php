<?php

use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;

uses(RefreshDatabase::class);

function roomUser(string $name): User
{
    $role = Role::firstOrCreate(['sigla' => 'SEC'], ['name' => 'SEC']);

    return User::query()->create(['name' => $name, 'username' => $name, 'email' => "$name@example.com", 'password' => 'Senha@123', 'password_verify' => true, 'is_active' => true, 'role_id' => $role->id]);
}

it('requests a room and blocks overlapping approved hours', function () {
    Gate::before(fn () => true);
    $user = roomUser('ana');
    $room = Room::create(['name' => 'Salão']);
    $start = now()->addDay()->setTime(19, 0);

    $this->actingAs($user)->post(route('rooms.store'), ['room_id' => $room->id, 'title' => 'Ensaio', 'starts_at' => $start, 'ends_at' => $start->copy()->addHours(2)])
        ->assertSessionHasNoErrors();
    $first = RoomBooking::firstOrFail();
    expect($first->status)->toBe('pending');

    $this->actingAs($user)->patch(route('rooms.decide', $first), ['decision' => 'approved'])->assertSessionHasNoErrors();
    expect($first->fresh()->status)->toBe('approved');

    // pedido que invade o horário aprovado é recusado na hora
    $this->actingAs($user)->post(route('rooms.store'), ['room_id' => $room->id, 'title' => 'Reunião', 'starts_at' => $start->copy()->addHour(), 'ends_at' => $start->copy()->addHours(3)])
        ->assertSessionHasErrors('starts_at');

    // horário colado (termina quando o outro começa) é permitido
    $this->actingAs($user)->post(route('rooms.store'), ['room_id' => $room->id, 'title' => 'Depois', 'starts_at' => $start->copy()->addHours(2), 'ends_at' => $start->copy()->addHours(3)])
        ->assertSessionHasNoErrors();
});

it('does not approve two pending requests for the same hours', function () {
    Gate::before(fn () => true);
    $user = roomUser('ana');
    $room = Room::create(['name' => 'Salão']);
    $start = now()->addDay()->setTime(19, 0);
    $a = RoomBooking::create(['room_id' => $room->id, 'requested_by' => $user->id, 'title' => 'A', 'starts_at' => $start, 'ends_at' => $start->copy()->addHours(2)]);
    $b = RoomBooking::create(['room_id' => $room->id, 'requested_by' => $user->id, 'title' => 'B', 'starts_at' => $start->copy()->addHour(), 'ends_at' => $start->copy()->addHours(3)]);

    $this->actingAs($user)->patch(route('rooms.decide', $a), ['decision' => 'approved']);
    $this->actingAs($user)->patch(route('rooms.decide', $b), ['decision' => 'approved'])->assertSessionHasErrors('decision');

    expect($b->fresh()->status)->toBe('pending');
});

it('renders the rooms page', function () {
    Gate::before(fn () => true);
    $this->actingAs(roomUser('ana'))->get(route('rooms.index'))->assertOk()->assertSee('Agendamento de salas');
});
