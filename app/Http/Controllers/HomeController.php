<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $posts = Post::latest()
            ->limit(8)
            ->get()
            ->map(fn ($post) => [
                ...$post->toArray(),
                'date' => $post->created_at->timezone(config('app.timezone'))->format('d/m/Y'),
            ]);

        $memberStats = [
            'aktif'     => (int) Setting::get('stat_member_aktif', 0),
            'pasif'     => (int) Setting::get('stat_member_pasif', 0),
            'company'   => (int) Setting::get('stat_member_company', 0),
            'personal'  => (int) Setting::get('stat_member_personal', 0),
        ];

        return Inertia::render('Home', [
            'posts' => $posts,
            'memberStats' => $memberStats,
        ]);
    }
}
