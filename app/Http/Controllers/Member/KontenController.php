<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KontenController extends Controller
{
    public function index()
    {
        $contents = \App\Models\Content::latest()->get();
        return Inertia::render('Member/Konten/Index', [
            'contents' => $contents
        ]);
    }
}
