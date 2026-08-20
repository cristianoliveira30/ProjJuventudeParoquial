<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GalleryAlbumController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->input('q'));
        $status = $request->input('status', 'all');
        $albums = $this->visibleAlbums($request)->with('firstPhoto')->withCount('photos')
            ->when($query !== '', fn (Builder $builder) => $builder->where('title', 'like', "%{$query}%"))
            ->when($status === 'published', fn ($builder) => $builder->published())
            ->when($status === 'draft', fn ($builder) => $builder->whereNull('published_at'))
            ->latest('event_date')->latest()->paginate(12)->withQueryString();

        return view('gallery.index', compact('albums', 'query', 'status'));
    }

    public function create()
    {
        return view('gallery.form', ['album' => new GalleryAlbum]);
    }

    public function store(Request $request)
    {
        $album = new GalleryAlbum($this->validatedData($request));
        $album->author_id = $request->user()->id;
        $this->storeCover($request, $album);
        $album->save();

        return redirect()->route('gallery.photos.index', $album)->with('success', 'Álbum criado. Agora adicione as fotos.');
    }

    public function edit(Request $request, GalleryAlbum $album)
    {
        $this->ensureCanManage($request, $album);

        return view('gallery.form', compact('album'));
    }

    public function update(Request $request, GalleryAlbum $album)
    {
        $this->ensureCanManage($request, $album);
        $album->fill($this->validatedData($request, $album));
        $this->storeCover($request, $album);
        $album->save();

        return redirect()->route('gallery.index')->with('success', 'Álbum atualizado.');
    }

    public function togglePublication(Request $request, GalleryAlbum $album)
    {
        $this->ensureCanManage($request, $album);
        $request->validate(['published' => ['required', 'boolean']]);
        $album->update(['published_at' => $request->boolean('published') ? now() : null]);

        return back()->with('success', $album->published_at ? 'Álbum publicado no portal.' : 'Álbum mantido como rascunho.');
    }

    public function destroy(Request $request, GalleryAlbum $album)
    {
        $this->ensureCanManage($request, $album);
        $album->load('photos');
        Storage::disk('public')->delete(array_filter([$album->cover_image, ...$album->photos->pluck('image_path')->all()]));
        $album->delete();

        return redirect()->route('gallery.index')->with('success', 'Álbum e suas fotos foram excluídos.');
    }

    private function validatedData(Request $request, ?GalleryAlbum $album = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique('gallery_albums')->ignore($album)],
            'description' => ['nullable', 'string', 'max:5000'],
            'event_date' => ['nullable', 'date'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'published_at' => ['nullable', 'date'],
        ]);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['title'], $album?->id);
        $data['event_date'] = $data['event_date'] ?: null;
        $data['published_at'] = $request->user()->can('gallery.publish') ? ($data['published_at'] ?: null) : $album?->published_at;
        unset($data['cover_image']);

        return $data;
    }

    private function visibleAlbums(Request $request): Builder
    {
        return GalleryAlbum::query()->when($request->user()->role?->sigla === 'COORD', fn ($q) => $q->where('author_id', $request->user()->id));
    }

    private function ensureCanManage(Request $request, GalleryAlbum $album): void
    {
        abort_if($request->user()->role?->sigla === 'COORD' && $album->author_id !== $request->user()->id, 403, 'Você só pode gerenciar os álbuns que criou.');
    }

    private function storeCover(Request $request, GalleryAlbum $album): void
    {
        if (! $request->hasFile('cover_image')) {
            return;
        }
        if ($album->cover_image) {
            Storage::disk('public')->delete($album->cover_image);
        }
        $album->cover_image = $request->file('cover_image')->store('gallery/covers', 'public');
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'album';
        $slug = $base;
        $suffix = 2;
        while (GalleryAlbum::query()->where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
