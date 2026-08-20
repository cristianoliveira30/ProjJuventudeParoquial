<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Models\ParishEvent;
use App\Models\PastoralGroup;
use App\Models\Post;
use App\Models\Retreat;

class PortalController extends Controller
{
    public function home()
    {
        $posts = Post::query()
            ->published()
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('portal.home', [
            'featuredPost' => $posts->first(),
            'recentPosts' => $posts->skip(1),
            'upcomingEvents' => ParishEvent::query()
                ->published()
                ->upcoming()
                ->orderBy('starts_at')
                ->take(4)
                ->get(),
        ]);
    }

    public function newsIndex()
    {
        return view('portal.noticias', [
            'posts' => Post::query()
                ->published()
                ->latest('published_at')
                ->paginate(9),
        ]);
    }

    public function newsShow(Post $post)
    {
        abort_unless($post->published_at?->isPast(), 404);

        return view('portal.noticia', compact('post'));
    }

    public function agenda()
    {
        return view('portal.agenda', [
            'events' => ParishEvent::query()
                ->published()
                ->upcoming()
                ->orderBy('starts_at')
                ->paginate(12),
        ]);
    }

    public function agendaShow(ParishEvent $event)
    {
        abort_unless($event->published_at?->isPast(), 404);

        return view('portal.evento', compact('event'));
    }

    public function parish()
    {
        return view('portal.paroquia', [
            'groups' => PastoralGroup::query()
                ->published()
                ->with('coordinator:id,name')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function pastoralShow(PastoralGroup $group)
    {
        abort_unless($group->published_at?->isPast(), 404);

        $group->load('coordinator:id,name');

        return view('portal.pastoral', compact('group'));
    }

    public function youth()
    {
        return view('portal.juventude', [
            'retreats' => Retreat::query()
                ->published()
                ->upcoming()
                ->withCount(['registrations as occupied_count' => fn ($query) => $query->whereIn('status', ['pending', 'confirmed'])])
                ->orderBy('starts_at')
                ->take(3)
                ->get(),
        ]);
    }

    public function retreatShow(Retreat $retreat)
    {
        abort_unless($retreat->published_at?->isPast(), 404);
        $retreat->loadCount(['registrations as occupied_count' => fn ($query) => $query->whereIn('status', ['pending', 'confirmed'])]);

        return view('portal.retiro', compact('retreat'));
    }

    public function gallery()
    {
        return view('portal.galeria', ['albums' => GalleryAlbum::query()->published()->with('firstPhoto')->withCount('photos')->latest('event_date')->latest()->paginate(12)]);
    }

    public function galleryShow(GalleryAlbum $album)
    {
        abort_unless($album->published_at?->isPast(), 404);
        $album->load('photos');

        return view('portal.album', compact('album'));
    }
}
