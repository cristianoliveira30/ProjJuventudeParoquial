<?php

namespace App\Http\Controllers;

use App\Models\ParishEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ParishEventController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->input('q'));
        $status = $request->input('status', 'upcoming');

        $events = ParishEvent::query()
            ->with('author:id,name')
            ->when($query !== '', fn ($builder) => $builder->where(function ($nested) use ($query) {
                $nested->where('title', 'like', "%{$query}%")
                    ->orWhere('category', 'like', "%{$query}%")
                    ->orWhere('location', 'like', "%{$query}%");
            }))
            ->when($status === 'upcoming', fn ($builder) => $builder->upcoming())
            ->when($status === 'past', fn ($builder) => $builder->where('starts_at', '<', now()))
            ->when($status === 'draft', fn ($builder) => $builder->whereNull('published_at'))
            ->when($status === 'published', fn ($builder) => $builder->published())
            ->orderBy('starts_at')
            ->paginate(12)
            ->withQueryString();

        return view('events.index', compact('events', 'query', 'status'));
    }

    public function create()
    {
        return view('events.form', ['event' => new ParishEvent]);
    }

    public function store(Request $request)
    {
        $event = new ParishEvent;
        $event->fill($this->validatedData($request));
        $event->author_id = $request->user()->id;
        $this->storeCoverImage($request, $event);
        $event->save();

        return redirect()->route('events.index')->with('success', $event->published_at
            ? 'Evento criado e publicado na agenda.'
            : 'Evento salvo como rascunho.');
    }

    public function edit(ParishEvent $event)
    {
        return view('events.form', compact('event'));
    }

    public function update(Request $request, ParishEvent $event)
    {
        $event->fill($this->validatedData($request, $event));
        $this->storeCoverImage($request, $event);
        $event->save();

        return redirect()->route('events.index')->with('success', 'Evento atualizado com sucesso.');
    }

    public function togglePublication(Request $request, ParishEvent $event)
    {
        $request->validate(['published' => ['required', 'boolean']]);
        $event->update(['published_at' => $request->boolean('published') ? now() : null]);

        return back()->with('success', $event->published_at
            ? 'Evento publicado na agenda.'
            : 'Evento retirado da agenda e mantido como rascunho.');
    }

    public function destroy(ParishEvent $event)
    {
        if ($event->cover_image) {
            Storage::disk('public')->delete($event->cover_image);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Evento excluído.');
    }

    private function validatedData(Request $request, ?ParishEvent $event = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique('events', 'slug')->ignore($event)],
            'category' => ['nullable', 'string', 'max:80'],
            'description' => ['required', 'string', 'max:30000'],
            'location' => ['nullable', 'string', 'max:180'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'published_at' => ['nullable', 'date'],
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?: $validated['title'], $event?->id);
        $validated['category'] = $validated['category'] ?: null;
        $validated['location'] = $validated['location'] ?: null;
        $validated['ends_at'] = $validated['ends_at'] ?: null;
        $validated['published_at'] = $request->user()->can('events.publish')
            ? ($validated['published_at'] ?: null)
            : $event?->published_at;
        unset($validated['cover_image']);

        return $validated;
    }

    private function storeCoverImage(Request $request, ParishEvent $event): void
    {
        if (! $request->hasFile('cover_image')) {
            return;
        }

        if ($event->cover_image) {
            Storage::disk('public')->delete($event->cover_image);
        }

        $event->cover_image = $request->file('cover_image')->store('events', 'public');
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'evento';
        $slug = $base;
        $suffix = 2;

        while (ParishEvent::query()->where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
