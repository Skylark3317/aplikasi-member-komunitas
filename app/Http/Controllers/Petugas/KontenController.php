<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class KontenController extends Controller
{
    public function index()
    {
        $contents = Content::latest()->get();
        return Inertia::render('Petugas/Konten/Index', [
            'contents' => $contents
        ]);
    }

    public function create()
    {
        return Inertia::render('Petugas/Konten/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,ebook',
            'file_url' => 'nullable|string',
            'file' => 'nullable|file',
            'thumbnail' => 'nullable|image|max:1024'
        ]);

        $fileUrl = $request->file_url;
        if ($request->hasFile('file')) {
            $fileUrl = $request->file('file')->store('uploads', 'public');
        }

        $thumbnailUrl = '';
        if ($request->hasFile('thumbnail')) {
            $thumbnailUrl = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        Content::create([
            'uploader_id' => auth()->id(),
            'title' => $request->title,
            'type' => $request->type,
            'file_url' => $fileUrl,
            'thumbnail_url' => $thumbnailUrl,
        ]);

        return redirect()->route('petugas.konten.index')->with('success', 'Konten berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $content = Content::findOrFail($id);
        return Inertia::render('Petugas/Konten/Edit', [
            'content' => $content
        ]);
    }

    public function update(Request $request, $id)
    {
        $content = Content::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,ebook',
            'file_url' => 'required|string',
            'thumbnail' => 'nullable|image|max:1024'
        ]);

        $thumbnailUrl = $content->thumbnail_url;

        if ($request->boolean('remove_thumbnail')) {
            if ($thumbnailUrl && Storage::disk('public')->exists($thumbnailUrl)) {
                Storage::disk('public')->delete($thumbnailUrl);
            }
            $thumbnailUrl = '';
        } elseif ($request->hasFile('thumbnail')) {
            if ($thumbnailUrl && Storage::disk('public')->exists($thumbnailUrl)) {
                Storage::disk('public')->delete($thumbnailUrl);
            }
            $thumbnailUrl = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $content->update([
            'title' => $request->title,
            'type' => $request->type,
            'file_url' => $request->file_url,
            'thumbnail_url' => $thumbnailUrl,
        ]);

        return redirect()->route('petugas.konten.index')->with('success', 'Konten berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $content = Content::findOrFail($id);
        
        if ($content->thumbnail_url && Storage::disk('public')->exists($content->thumbnail_url)) {
            Storage::disk('public')->delete($content->thumbnail_url);
        }
        
        $content->delete();

        return redirect()->route('petugas.konten.index')->with('success', 'Konten berhasil dihapus.');
    }
}
