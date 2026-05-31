<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Home', [
            'posts' => Post::latest()
                ->limit(8)
                ->get()
                ->map(fn ($post) => [
                    ...$post->toArray(),
                    'date' => $post->created_at->timezone(config('app.timezone'))->format('d/m/Y'),
                ]),
        ]);
    }
}
