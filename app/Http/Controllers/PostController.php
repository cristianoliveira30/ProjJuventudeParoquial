<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->input('q'));
        $status = $request->input('status', 'all');

        $posts = Post::query()
            ->with('author:id,name')
            ->when($query !== '', fn ($builder) => $builder->where(function ($nested) use ($query) {
                $nested->where('title', 'like', "%{$query}%")
                    ->orWhere('category', 'like', "%{$query}%");
            }))
            ->when($status === 'published', fn ($builder) => $builder->published())
            ->when($status === 'draft', fn ($builder) => $builder->whereNull('published_at'))
            ->when($status === 'scheduled', fn ($builder) => $builder->where('published_at', '>', now()))
            ->latest('published_at')
            ->latest('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('posts.index', compact('posts', 'query', 'status'));
    }

    public function create()
    {
        return view('posts.form', ['post' => new Post, 'page' => 'posts.create']);
    }

    public function store(Request $request)
    {
        $post = new Post;
        $post->fill($this->validatedData($request));
        $post->author_id = $request->user()->id;
        $this->storeCoverImage($request, $post);
        $post->save();

        return redirect()->route('posts.index')->with('success', $post->published_at
            ? 'Publicação criada e enviada ao portal.'
            : 'Rascunho salvo com sucesso.');
    }

    public function edit(Post $post)
    {
        return view('posts.form', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $post->fill($this->validatedData($request, $post));
        $this->storeCoverImage($request, $post);
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Publicação atualizada com sucesso.');
    }

    public function togglePublication(Request $request, Post $post)
    {
        $request->validate(['published' => ['required', 'boolean']]);
        $post->update(['published_at' => $request->boolean('published') ? now() : null]);

        return back()->with('success', $post->published_at
            ? 'Publicação exibida no portal.'
            : 'Publicação retirada do portal e mantida como rascunho.');
    }

    public function destroy(Post $post)
    {
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Publicação excluída.');
    }

    private function validatedData(Request $request, ?Post $post = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique('posts', 'slug')->ignore($post)],
            'category' => ['nullable', 'string', 'max:80'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string', 'max:30000'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'published_at' => ['nullable', 'date'],
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?: $validated['title'], $post?->id);
        $validated['category'] = $validated['category'] ?: null;
        $validated['excerpt'] = $validated['excerpt'] ?: Str::limit(strip_tags($validated['content']), 180);
        $validated['published_at'] = $request->user()->can('posts.publish')
            ? ($validated['published_at'] ?: null)
            : $post?->published_at;

        unset($validated['cover_image']);

        return $validated;
    }

    private function storeCoverImage(Request $request, Post $post): void
    {
        if (! $request->hasFile('cover_image')) {
            return;
        }

        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }

        $post->cover_image = $request->file('cover_image')->store('posts', 'public');
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'publicacao';
        $slug = $base;
        $suffix = 2;

        while (Post::query()->where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
