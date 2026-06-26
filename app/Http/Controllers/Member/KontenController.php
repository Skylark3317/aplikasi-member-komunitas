<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KontenController extends Controller
{
    public function index(Request $request)
    {
        $user    = $request->user();
        $profile = $user->memberProfile()->with('plan')->first();

        // Determine which content types the member can access
        $canAccessEbook = false;
        $canAccessVideo = false;

        if ($profile && $profile->status === 'active' && now()->lt($profile->expire_date)) {
            $canAccessEbook = $profile->hasBenefit('Akses Ebook Berkualitas');
            $canAccessVideo = $profile->hasBenefit('Akses Video Premium');
        }

        // Always load all content — frontend will gate per tab
        $contents = Content::latest()->get();

        return Inertia::render('Member/Konten/Index', [
            'contents'        => $contents,
            'canAccessEbook'  => $canAccessEbook,
            'canAccessVideo'  => $canAccessVideo,
            'activeBenefits'  => $profile?->plan?->features ?? [],
        ]);
    }
}
