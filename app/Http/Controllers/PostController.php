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

    public function show(string $slug)
    {
        $foundPost = Post::with(['category', 'author'])->where('slug', $slug)->first();

        $post = $foundPost
            ? [
                ...$foundPost->toArray(),
                'date' => $foundPost->created_at->timezone(config('app.timezone'))->format('d/m/Y H:i'),
            ]
            : null;

        return Inertia::render('Blog/Show', [
            'post' => $post,
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
