<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryPhotoController extends Controller
{
    public function index(Request $request, GalleryAlbum $album)
    {
        $this->ensureCanManage($request, $album);
        $album->load('photos');

        return view('gallery.photos', compact('album'));
    }

    public function store(Request $request, GalleryAlbum $album)
    {
        $this->ensureCanManage($request, $album);
        $request->validate(['photos' => ['required', 'array', 'min:1', 'max:20'], 'photos.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192']]);
        $next = ((int) $album->photos()->max('sort_order')) + 1;
        foreach ($request->file('photos') as $file) {
            $album->photos()->create(['image_path' => $file->store('gallery/'.$album->id, 'public'), 'sort_order' => $next++, 'uploaded_by' => $request->user()->id]);
        }

        return back()->with('success', count($request->file('photos')).' foto(s) adicionada(s).');
    }

    public function update(Request $request, GalleryAlbum $album, GalleryPhoto $photo)
    {
        $this->ensureCanManage($request, $album);
        $photo->update($request->validate(['caption' => ['nullable', 'string', 'max:255'], 'alt_text' => ['nullable', 'string', 'max:255'], 'sort_order' => ['required', 'integer', 'min:0', 'max:100000']]));

        return back()->with('success', 'Informações da foto atualizadas.');
    }

    public function destroy(Request $request, GalleryAlbum $album, GalleryPhoto $photo)
    {
        $this->ensureCanManage($request, $album);
        Storage::disk('public')->delete($photo->image_path);
        $photo->delete();

        return back()->with('success', 'Foto removida.');
    }

    private function ensureCanManage(Request $request, GalleryAlbum $album): void
    {
        abort_if($request->user()->role?->sigla === 'COORD' && $album->author_id !== $request->user()->id, 403, 'Você só pode gerenciar os álbuns que criou.');
    }
}
