<?php

namespace App\Http\Controllers;

use App\Models\Retreat;
use App\Models\RetreatMinistry;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RetreatController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->input('q'));
        $status = $request->input('status', 'upcoming');

        $retreats = $this->visibleRetreats($request)
            ->with('coordinator:id,name')
            ->withCount('registrations')
            ->when($query !== '', fn (Builder $builder) => $builder->where(function ($nested) use ($query) {
                $nested->where('title', 'like', "%{$query}%")
                    ->orWhere('location', 'like', "%{$query}%");
            }))
            ->when($status === 'upcoming', fn ($builder) => $builder->upcoming())
            ->when($status === 'past', fn ($builder) => $builder->where('starts_at', '<', now()))
            ->when($status === 'draft', fn ($builder) => $builder->whereNull('published_at'))
            ->when($status === 'published', fn ($builder) => $builder->published())
            ->orderBy('starts_at')
            ->paginate(12)
            ->withQueryString();

        return view('retreats.index', compact('retreats', 'query', 'status'));
    }

    public function create()
    {
        return view('retreats.form', [
            'retreat' => new Retreat,
            'users' => $this->activeUsers(),
        ]);
    }

    public function store(Request $request)
    {
        $retreat = new Retreat;
        $retreat->fill($this->validatedData($request));
        $retreat->author_id = $request->user()->id;
        $retreat->servant_registration_token = Str::random(64);
        $this->storeCoverImage($request, $retreat);
        $retreat->save();
        RetreatMinistry::ensureDefaultsFor($retreat);

        return redirect()->route('retreats.index')->with('success', $retreat->published_at
            ? 'Retiro criado e publicado no portal.'
            : 'Retiro salvo como rascunho.');
    }

    public function edit(Request $request, Retreat $retreat)
    {
        $this->ensureCanManage($request, $retreat);

        return view('retreats.form', [
            'retreat' => $retreat,
            'users' => $this->activeUsers(),
        ]);
    }

    public function update(Request $request, Retreat $retreat)
    {
        $this->ensureCanManage($request, $retreat);
        $retreat->fill($this->validatedData($request, $retreat));
        $this->storeCoverImage($request, $retreat);
        $retreat->save();

        return redirect()->route('retreats.index')->with('success', 'Retiro atualizado com sucesso.');
    }

    public function togglePublication(Request $request, Retreat $retreat)
    {
        $this->ensureCanManage($request, $retreat);
        $request->validate(['published' => ['required', 'boolean']]);
        $retreat->update(['published_at' => $request->boolean('published') ? now() : null]);

        return back()->with('success', $retreat->published_at
            ? 'Retiro publicado no portal.'
            : 'Retiro retirado do portal e mantido como rascunho.');
    }

    public function destroy(Request $request, Retreat $retreat)
    {
        $this->ensureCanManage($request, $retreat);

        if ($retreat->cover_image) {
            Storage::disk('public')->delete($retreat->cover_image);
        }

        $retreat->delete();

        return redirect()->route('retreats.index')->with('success', 'Retiro excluído.');
    }

    private function validatedData(Request $request, ?Retreat $retreat = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique('retreats', 'slug')->ignore($retreat)],
            'summary' => ['nullable', 'string', 'max:500'],
            'description' => ['required', 'string', 'max:30000'],
            'location' => ['nullable', 'string', 'max:180'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'registration_opens_at' => ['nullable', 'date', 'before:starts_at'],
            'registration_closes_at' => [
                'nullable',
                'date',
                Rule::when($request->filled('registration_opens_at'), ['after_or_equal:registration_opens_at']),
                'before_or_equal:starts_at',
            ],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:10000'],
            'public_contact' => ['nullable', 'string', 'max:180'],
            'coordinator_id' => ['nullable', Rule::exists('users', 'id')->where('is_active', true)],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'published_at' => ['nullable', 'date'],
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?: $validated['title'], $retreat?->id);
        $validated['summary'] = $validated['summary'] ?: Str::limit(strip_tags($validated['description']), 180);
        $validated['ends_at'] = $validated['ends_at'] ?: null;
        $validated['registration_opens_at'] = $validated['registration_opens_at'] ?: null;
        $validated['registration_closes_at'] = $validated['registration_closes_at'] ?: null;
        $validated['capacity'] = $validated['capacity'] ?: null;
        $validated['published_at'] = $request->user()->can('retreats.publish')
            ? ($validated['published_at'] ?: null)
            : $retreat?->published_at;

        if ($request->user()->role?->sigla === 'COORD') {
            $validated['coordinator_id'] = $retreat?->coordinator_id ?? $request->user()->id;
        }

        unset($validated['cover_image']);

        return $validated;
    }

    private function visibleRetreats(Request $request): Builder
    {
        return Retreat::query()->when(
            $request->user()->role?->sigla === 'COORD',
            fn ($query) => $query->where('coordinator_id', $request->user()->id)
        );
    }

    private function ensureCanManage(Request $request, Retreat $retreat): void
    {
        abort_if(
            $request->user()->role?->sigla === 'COORD' && $retreat->coordinator_id !== $request->user()->id,
            403,
            'Você só pode gerenciar retiros sob sua responsabilidade.'
        );
    }

    private function activeUsers()
    {
        return User::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']);
    }

    private function storeCoverImage(Request $request, Retreat $retreat): void
    {
        if (! $request->hasFile('cover_image')) {
            return;
        }

        if ($retreat->cover_image) {
            Storage::disk('public')->delete($retreat->cover_image);
        }

        $retreat->cover_image = $request->file('cover_image')->store('retreats', 'public');
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'retiro';
        $slug = $base;
        $suffix = 2;

        while (Retreat::query()->where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
