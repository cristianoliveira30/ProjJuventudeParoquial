<?php

namespace App\Http\Controllers;

use App\Models\PastoralGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PastoralGroupController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->input('q'));
        $type = $request->input('type', 'all');

        $groups = $this->visibleGroups($request)
            ->with(['coordinator:id,name', 'author:id,name'])
            ->withCount('members')
            ->when($query !== '', fn (Builder $builder) => $builder->where(function ($nested) use ($query) {
                $nested->where('name', 'like', "%{$query}%")
                    ->orWhere('summary', 'like', "%{$query}%")
                    ->orWhere('meeting_location', 'like', "%{$query}%");
            }))
            ->when(array_key_exists($type, PastoralGroup::TYPES), fn ($builder) => $builder->where('type', $type))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('groups.index', compact('groups', 'query', 'type'));
    }

    public function create()
    {
        return view('groups.form', [
            'group' => new PastoralGroup,
            'users' => $this->activeUsers(),
            'selectedMembers' => [],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);
        $members = $this->memberIds($validated);

        $group = DB::transaction(function () use ($request, $validated, $members) {
            $group = new PastoralGroup;
            $group->fill($this->groupAttributes($validated));
            $group->author_id = $request->user()->id;
            $this->storeCoverImage($request, $group);
            $group->save();
            $group->members()->sync($members);

            return $group;
        });

        return redirect()->route('groups.index')->with('success', $group->published_at
            ? 'Pastoral criada e publicada no portal.'
            : 'Pastoral salva como rascunho.');
    }

    public function edit(Request $request, PastoralGroup $group)
    {
        $this->ensureCanManage($request, $group);

        return view('groups.form', [
            'group' => $group,
            'users' => $this->activeUsers(),
            'selectedMembers' => $group->members()->pluck('users.id')->all(),
        ]);
    }

    public function update(Request $request, PastoralGroup $group)
    {
        $this->ensureCanManage($request, $group);
        $validated = $this->validatedData($request, $group);
        $members = $this->memberIds($validated);

        DB::transaction(function () use ($request, $validated, $members, $group) {
            $group->fill($this->groupAttributes($validated));
            $this->storeCoverImage($request, $group);
            $group->save();
            $group->members()->sync($members);
        });

        return redirect()->route('groups.index')->with('success', 'Pastoral atualizada com sucesso.');
    }

    public function togglePublication(Request $request, PastoralGroup $group)
    {
        $this->ensureCanManage($request, $group);
        $request->validate(['published' => ['required', 'boolean']]);
        $group->update(['published_at' => $request->boolean('published') ? now() : null]);

        return back()->with('success', $group->published_at
            ? 'Pastoral publicada no portal.'
            : 'Pastoral retirada do portal e mantida como rascunho.');
    }

    public function destroy(Request $request, PastoralGroup $group)
    {
        $this->ensureCanManage($request, $group);

        if ($group->cover_image) {
            Storage::disk('public')->delete($group->cover_image);
        }

        $group->delete();

        return redirect()->route('groups.index')->with('success', 'Pastoral excluída.');
    }

    private function visibleGroups(Request $request): Builder
    {
        $query = PastoralGroup::query();

        if ($request->user()->role?->sigla === 'COORD') {
            $query->where(function ($nested) use ($request) {
                $nested->where('coordinator_id', $request->user()->id)
                    ->orWhereHas('members', fn ($members) => $members->whereKey($request->user()->id));
            });
        }

        return $query;
    }

    private function ensureCanManage(Request $request, PastoralGroup $group): void
    {
        if ($request->user()->role?->sigla !== 'COORD') {
            return;
        }

        abort_unless(
            $group->coordinator_id === $request->user()->id || $group->members()->whereKey($request->user()->id)->exists(),
            403,
            'Você só pode editar as equipes das quais participa.'
        );
    }

    private function validatedData(Request $request, ?PastoralGroup $group = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique('pastoral_groups', 'slug')->ignore($group)],
            'type' => ['required', Rule::in(array_keys(PastoralGroup::TYPES))],
            'summary' => ['nullable', 'string', 'max:500'],
            'description' => ['required', 'string', 'max:30000'],
            'meeting_schedule' => ['nullable', 'string', 'max:180'],
            'meeting_location' => ['nullable', 'string', 'max:180'],
            'public_contact' => ['nullable', 'string', 'max:180'],
            'coordinator_id' => ['nullable', Rule::exists('users', 'id')->where('is_active', true)],
            'member_ids' => ['nullable', 'array'],
            'member_ids.*' => [Rule::exists('users', 'id')->where('is_active', true)],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'published_at' => ['nullable', 'date'],
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?: $validated['name'], $group?->id);
        $validated['summary'] = $validated['summary'] ?: Str::limit(strip_tags($validated['description']), 180);
        $validated['published_at'] = $request->user()->can('groups.publish')
            ? ($validated['published_at'] ?: null)
            : $group?->published_at;

        if ($request->user()->role?->sigla === 'COORD') {
            $validated['coordinator_id'] = $group?->coordinator_id ?? $request->user()->id;
            $validated['member_ids'] = $group
                ? $group->members()->pluck('users.id')->all()
                : [$request->user()->id];
        }

        return $validated;
    }

    private function groupAttributes(array $validated): array
    {
        return collect($validated)->except(['member_ids', 'cover_image'])->all();
    }

    private function memberIds(array $validated): array
    {
        return collect($validated['member_ids'] ?? [])
            ->push($validated['coordinator_id'] ?? null)
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function activeUsers()
    {
        return User::query()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'email']);
    }

    private function storeCoverImage(Request $request, PastoralGroup $group): void
    {
        if (! $request->hasFile('cover_image')) {
            return;
        }

        if ($group->cover_image) {
            Storage::disk('public')->delete($group->cover_image);
        }

        $group->cover_image = $request->file('cover_image')->store('pastoral-groups', 'public');
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'pastoral';
        $slug = $base;
        $suffix = 2;

        while (PastoralGroup::query()->where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
