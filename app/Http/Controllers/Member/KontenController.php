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

        $activeBenefits = [];
        if ($profile) {
            if ($profile->plan_snapshot && isset($profile->plan_snapshot['features'])) {
                $activeBenefits = $profile->plan_snapshot['features'];
            } elseif ($profile->plan) {
                $activeBenefits = $profile->plan->features ?? [];
            }
        }

        return Inertia::render('Member/Konten/Index', [
            'contents'        => $contents,
            'canAccessEbook'  => $canAccessEbook,
            'canAccessVideo'  => $canAccessVideo,
            'activeBenefits'  => $activeBenefits,
        ]);
    }
}
