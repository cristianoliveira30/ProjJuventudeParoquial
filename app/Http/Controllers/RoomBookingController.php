<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RoomBookingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $canManage = $user->can('rooms.manage');

        return view('rooms.index', [
            'rooms' => Room::query()->orderBy('name')->get(),
            'pending' => $canManage
                ? RoomBooking::query()->with(['room', 'requester'])->where('status', 'pending')->orderBy('starts_at')->get()
                : collect(),
            'mine' => RoomBooking::query()->with('room')->where('requested_by', $user->id)->where('ends_at', '>=', now())->orderBy('starts_at')->get(),
            'agenda' => RoomBooking::query()->with(['room', 'requester'])->where('status', 'approved')->where('ends_at', '>=', now())->orderBy('starts_at')->limit(50)->get(),
            'canManage' => $canManage,
            'canRequest' => $user->can('rooms.request'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', Rule::exists('rooms', 'id')->where('is_active', true)],
            'title' => ['required', 'string', 'max:180'],
            'purpose' => ['nullable', 'string', 'max:1000'],
            'starts_at' => ['required', 'date', 'after:now'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
        ]);

        if (RoomBooking::conflicting($validated['room_id'], $validated['starts_at'], $validated['ends_at'])->exists()) {
            throw ValidationException::withMessages(['starts_at' => 'A sala já está reservada neste horário.']);
        }

        RoomBooking::create($validated + ['requested_by' => $request->user()->id, 'status' => 'pending']);

        return back()->with('success', 'Solicitação enviada. A secretaria responderá em breve.');
    }

    public function decide(Request $request, RoomBooking $booking)
    {
        $validated = $request->validate(['decision' => ['required', Rule::in(['approved', 'rejected'])]]);
        abort_unless($booking->status === 'pending', 422, 'Esta solicitação já foi respondida.');

        DB::transaction(function () use ($booking, $validated, $request) {
            // Trava a sala: duas aprovações ao mesmo tempo não podem ocupar o mesmo horário.
            Room::query()->lockForUpdate()->findOrFail($booking->room_id);

            if ($validated['decision'] === 'approved'
                && RoomBooking::conflicting($booking->room_id, $booking->starts_at, $booking->ends_at, $booking->id)->exists()) {
                throw ValidationException::withMessages(['decision' => 'Já existe uma reserva aprovada neste horário.']);
            }

            $booking->update(['status' => $validated['decision'], 'decided_by' => $request->user()->id, 'decided_at' => now()]);
        });

        return back()->with('success', $validated['decision'] === 'approved' ? 'Reserva aprovada.' : 'Solicitação recusada.');
    }

    public function cancel(Request $request, RoomBooking $booking)
    {
        abort_unless($booking->requested_by === $request->user()->id || $request->user()->can('rooms.manage'), 403);
        abort_unless(in_array($booking->status, ['pending', 'approved'], true), 422);

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Reserva cancelada.');
    }

    public function storeRoom(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:rooms,name'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:10000'],
        ]);

        Room::create($validated);

        return back()->with('success', 'Sala cadastrada.');
    }

    public function toggleRoom(Room $room)
    {
        $room->update(['is_active' => ! $room->is_active]);

        return back()->with('success', $room->is_active ? 'Sala ativada.' : 'Sala desativada.');
    }
}
