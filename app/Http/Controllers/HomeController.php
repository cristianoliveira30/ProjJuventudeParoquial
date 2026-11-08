<?php

namespace App\Http\Controllers;

use App\Models\ParishEvent;
use App\Models\Retreat;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $nextRetreat = $user->can('retreats.view')
            ? Retreat::query()
                ->when($user->role?->sigla === 'COORD', fn ($query) => $query->where('coordinator_id', $user->id))
                ->upcoming()
                ->withCount('registrations')
                ->orderBy('starts_at')
                ->first()
            : null;

        return view('home', [
            'page' => 'home',
            'encounters' => $user->can('events.view')
                ? ParishEvent::query()->upcoming()->orderBy('starts_at')->take(3)->get()
                : collect(),
            'nextRetreat' => $nextRetreat,
        ]);
    }
}
