<?php

namespace App\Http\Controllers;

use App\Models\Retreat;
use App\Models\RetreatMinistry;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RetreatMinistryController extends Controller
{
    public function index(Request $request, Retreat $retreat)
    {
        $this->ensureCanManage($request, $retreat);
        $retreat->load(['ministries' => fn ($query) => $query->withCount(['preferredServants', 'assignedServants'])]);

        return view('retreats.ministries', compact('retreat'));
    }

    public function store(Request $request, Retreat $retreat)
    {
        $this->ensureCanManage($request, $retreat);
        $data = $this->validatedData($request, $retreat);
        $data['slug'] = $this->uniqueSlug($retreat, $data['name']);
        $data['sort_order'] = ((int) $retreat->ministries()->max('sort_order')) + 1;
        $retreat->ministries()->create($data);

        return back()->with('success', 'Ministério adicionado ao retiro.');
    }

    public function update(Request $request, Retreat $retreat, RetreatMinistry $ministry)
    {
        $this->ensureCanManage($request, $retreat);
        $this->ensureBelongsToRetreat($retreat, $ministry);
        $data = $this->validatedData($request, $retreat, $ministry);
        $data['slug'] = $this->uniqueSlug($retreat, $data['name'], $ministry->id);
        $ministry->update($data);

        return back()->with('success', 'Ministério atualizado.');
    }

    public function toggle(Request $request, Retreat $retreat, RetreatMinistry $ministry)
    {
        $this->ensureCanManage($request, $retreat);
        $this->ensureBelongsToRetreat($retreat, $ministry);
        $request->validate(['active' => ['required', 'boolean']]);
        $active = $request->boolean('active');

        if (! $active && $retreat->ministries()->where('is_active', true)->count() <= 3) {
            return back()->withErrors(['ministry' => 'O retiro precisa manter pelo menos três ministérios ativos.']);
        }

        $ministry->update(['is_active' => $active]);

        return back()->with('success', $active ? 'Ministério reativado.' : 'Ministério desativado.');
    }

    public function destroy(Request $request, Retreat $retreat, RetreatMinistry $ministry)
    {
        $this->ensureCanManage($request, $retreat);
        $this->ensureBelongsToRetreat($retreat, $ministry);

        if ($ministry->preferredServants()->exists() || $ministry->assignedServants()->exists()) {
            return back()->withErrors(['ministry' => 'Este ministério possui servos vinculados e não pode ser excluído.']);
        }

        if ($ministry->is_active && $retreat->ministries()->where('is_active', true)->count() <= 3) {
            return back()->withErrors(['ministry' => 'O retiro precisa manter pelo menos três ministérios ativos.']);
        }

        $ministry->delete();

        return back()->with('success', 'Ministério excluído.');
    }

    private function validatedData(Request $request, Retreat $retreat, ?RetreatMinistry $ministry = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120', Rule::unique('retreat_ministries')->where('retreat_id', $retreat->id)->ignore($ministry)],
            'description' => ['nullable', 'string', 'max:2000'],
            'sort_order' => ['sometimes', 'integer', 'min:0', 'max:10000'],
        ]);
    }

    private function uniqueSlug(Retreat $retreat, string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'ministerio';
        $slug = $base;
        $suffix = 2;
        while ($retreat->ministries()->where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    private function ensureCanManage(Request $request, Retreat $retreat): void
    {
        abort_if($request->user()->role?->sigla === 'COORD' && $retreat->coordinator_id !== $request->user()->id, 403);
    }

    private function ensureBelongsToRetreat(Retreat $retreat, RetreatMinistry $ministry): void
    {
        abort_unless($ministry->retreat_id === $retreat->id, 404);
    }
}
