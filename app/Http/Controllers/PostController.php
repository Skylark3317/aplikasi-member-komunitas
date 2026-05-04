<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request): Response
    {
        $search   = $request->input('q');
        $catSlug  = $request->input('kategori', 'semua');

        $posts = Post::with(['category', 'author'])
            ->published()
            ->search($search)
            ->byCategory($catSlug)
            ->latest('published_at')
            ->paginate(5)
            ->withQueryString()
            ->through(fn($p) => [
                'id'           => $p->id,
                'title'        => $p->title,
                'slug'         => $p->slug,
                'excerpt'      => $p->excerpt,
                'published_at' => $p->published_at?->format('d/m/Y'),
                'category'     => $p->category?->name,
                'category_slug'=> $p->category?->slug,
            ]);

        $categories = Category::all(['id', 'name', 'slug']);

        return Inertia::render('Blog/Index', [
            'posts'           => $posts,
            'categories'      => $categories,
            'filters'         => ['q' => $search, 'kategori' => $catSlug],
            'isSearchResult'  => (bool) $search,
        ]);
    }

    public function show(string $slug): Response
    {
        $post = Post::with(['category', 'author'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $categories = Category::all(['id', 'name', 'slug']);

        return Inertia::render('Blog/Show', [
            'post'       => [
                'id'           => $post->id,
                'title'        => $post->title,
                'content'      => $post->content,
                'published_at' => $post->published_at?->format('d/m/Y'),
                'category'     => $post->category?->name,
                'category_slug'=> $post->category?->slug,
                'author'       => $post->author?->name,
            ],
            'categories' => $categories,
        ]);
    }
}
