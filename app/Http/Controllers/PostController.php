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
        $posts = Post::byCategory($request->query('category'))
            ->latest()
            ->paginate(10)
            ->appends($request->query())
            ->through(fn ($post) => [
                ...$post->toArray(),
                'date' => $post->created_at->timezone(config('app.timezone'))->format('d/m/Y'),
            ]);

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
            'category' => $request->query('category', ''),
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

    public function search(Request $request)
    {
        $posts = Post::search($request->query('q'))
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        return Inertia::render('Blog/Search', [
            'posts' => $posts,
            'q' => $request->query('q'),
        ]);
    }
}
