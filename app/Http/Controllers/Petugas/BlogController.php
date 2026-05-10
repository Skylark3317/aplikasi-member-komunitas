<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::with(['author', 'category'])->latest()->get();
        return Inertia::render('Petugas/Blog/Index', [
            'posts' => $posts
        ]);
    }

    public function create()
    {
        return Inertia::render('Petugas/Blog/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'category' => 'nullable|string',
        ]);

        $categoryId = null;
        if ($request->category) {
            $cat = Category::firstOrCreate(
                ['slug' => \Str::slug($request->category)],
                ['name' => $request->category]
            );
            $categoryId = $cat->id;
        }

        Post::create([
            'author_id' => auth()->id(),
            'title' => $request->title,
            'slug' => \Str::slug($request->title) . '-' . uniqid(),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'category_id' => $categoryId,
            'published_at' => now(),
        ]);

        return redirect()->route('petugas.blog.index')->with('success', 'Blog berhasil dipublikasikan.');
    }

    public function edit($id)
    {
        $post = Post::with('category')->findOrFail($id);
        return Inertia::render('Petugas/Blog/Edit', [
            'post' => $post
        ]);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'category' => 'nullable|string',
        ]);

        $categoryId = $post->category_id;
        if ($request->category) {
            $cat = Category::firstOrCreate(
                ['slug' => \Str::slug($request->category)],
                ['name' => $request->category]
            );
            $categoryId = $cat->id;
        }

        $post->update([
            'title' => $request->title,
            'slug' => \Str::slug($request->title) . '-' . $post->id,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'category_id' => $categoryId,
        ]);

        return redirect()->route('petugas.blog.index')->with('success', 'Blog berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('petugas.blog.index')->with('success', 'Blog berhasil dihapus.');
    }
}
