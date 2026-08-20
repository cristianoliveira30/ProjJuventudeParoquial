<?php

namespace App\Http\Controllers;

use App\Models\Retreat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RetreatServantLinkController extends Controller
{
    public function show(Request $request, Retreat $retreat)
    {
        $this->ensureCanManage($request, $retreat);
        $this->ensureToken($retreat);

        return view('retreats.servant-link', compact('retreat'));
    }

    public function update(Request $request, Retreat $retreat)
    {
        $this->ensureCanManage($request, $retreat);
        $data = $request->validate([
            'servant_registration_enabled' => ['required', 'boolean'],
            'servant_registration_opens_at' => ['nullable', 'date', 'before:servant_registration_closes_at'],
            'servant_registration_closes_at' => ['nullable', 'date', 'after:servant_registration_opens_at', 'before_or_equal:'.$retreat->starts_at->format('Y-m-d H:i:s')],
        ]);
        $this->ensureToken($retreat);
        $retreat->update($data);

        return back()->with('success', 'Configuração do link de servos atualizada.');
    }

    public function regenerate(Request $request, Retreat $retreat)
    {
        $this->ensureCanManage($request, $retreat);
        $retreat->update(['servant_registration_token' => Str::random(64)]);

        return back()->with('success', 'Novo link gerado. O endereço anterior foi invalidado imediatamente.');
    }

    public function publicShow(string $token)
    {
        $retreat = Retreat::query()
            ->with(['ministries' => fn ($query) => $query->where('is_active', true)])
            ->where('servant_registration_token', $token)
            ->firstOrFail();

        return view('portal.servir', compact('retreat'));
    }

    private function ensureToken(Retreat $retreat): void
    {
        if (! $retreat->servant_registration_token) {
            $retreat->update(['servant_registration_token' => Str::random(64)]);
        }
    }

    private function ensureCanManage(Request $request, Retreat $retreat): void
    {
        abort_if($request->user()->role?->sigla === 'COORD' && $retreat->coordinator_id !== $request->user()->id, 403);
    }
}
