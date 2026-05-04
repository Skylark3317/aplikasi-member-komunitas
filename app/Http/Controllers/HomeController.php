<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $latestPosts = Post::with(['category', 'author'])
            ->published()
            ->latest('published_at')
            ->take(8)
            ->get()
            ->map(fn($p) => [
                'id'           => $p->id,
                'title'        => $p->title,
                'slug'         => $p->slug,
                'excerpt'      => $p->excerpt,
                'published_at' => $p->published_at?->format('d/m/Y'),
                'category'     => $p->category?->name,
            ]);

        return Inertia::render('Home', [
            'latestPosts' => $latestPosts,
        ]);
    }
}
