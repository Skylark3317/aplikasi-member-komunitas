<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class ProfilController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load('memberProfile');
        $memberProfile = $user->memberProfile;

        $profileData = [
            'id'           => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'telephone'    => $user->telephone,
            'role'         => $user->role,
            'is_active'    => $user->is_active,
            'created_at'   => $user->created_at?->translatedFormat('j F Y'),
            'is_premium'   => $user->isPremium(),
            'status'       => $user->membershipStatus(),
            'avatar_url'   => $user->avatar_url,
        ];

        if ($memberProfile) {
            $expireDate = $memberProfile->expire_date
                ? \Carbon\Carbon::parse($memberProfile->expire_date)
                : null;

            $profileData['member_profile'] = [
                'member_number'  => 'M' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'institution'    => $memberProfile->institution,
                'department'     => $memberProfile->department,
                'address'        => $memberProfile->address,
                'status'         => $memberProfile->status,
                'expire_date'    => $expireDate?->translatedFormat('j F Y'),
                'days_remaining' => $expireDate ? max(0, now()->diffInDays($expireDate, false)) : 0,
            ];
        } else {
            $profileData['member_profile'] = null;
        }

        return Inertia::render('Member/Profil/Show', [
            'user' => $profileData,
        ]);
    }

    public function edit(Request $request)
    {
        $user = $request->user()->load('memberProfile');

        $profileData = [
            'id'             => $user->id,
            'name'           => $user->name,
            'email'          => $user->email,
            'telephone'      => $user->telephone,
            'avatar_url'     => $user->avatar_url,
            'member_profile' => $user->memberProfile ? [
                'institution' => $user->memberProfile->institution,
                'department'  => $user->memberProfile->department,
                'address'     => $user->memberProfile->address,
            ] : null,
        ];

        return Inertia::render('Member/Profil/Edit', [
            'user' => $profileData,
        ]);
    }

    public function update(Request $request)
    {
        // Load memberProfile relation upfront so the if-check below is reliable
        $user = $request->user()->load('memberProfile');

        $rules = [
            'name'        => ['required', 'string', 'max:255'],
            'telephone'   => ['nullable', 'string', 'max:20'],
            'institution' => ['nullable', 'string', 'max:255'],
            'department'  => ['nullable', 'string', 'max:255'],
            'address'     => ['nullable', 'string'],
            'avatar'      => ['nullable', 'image', 'max:1024'],
        ];

        if ($request->filled('old_password') || $request->filled('password')) {
            $rules['old_password'] = ['required', 'current_password'];
            $rules['password']     = ['required', 'confirmed', Password::defaults()];
        }

        $validated = $request->validate($rules);

        // Save user fields — allow telephone to be cleared (defaults to '-' in DB to satisfy NOT NULL)
        $user->name      = $validated['name'];
        $user->telephone = $validated['telephone'] ?: '-';

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Update or create member profile
        $profileFields = [
            'institution' => $validated['institution'] ?? null,
            'department'  => $validated['department'] ?? null,
            'address'     => $validated['address'] ?: '-',
        ];

        if ($user->memberProfile) {
            $user->memberProfile->update($profileFields);
        } else {
            $user->memberProfile()->create(array_merge($profileFields, [
                'status'      => 'nonactive',
                'expire_date' => now(),
            ]));
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $ext    = $avatar->getClientOriginalExtension();

            // Delete all old avatar variants first
            foreach (['png', 'jpg', 'jpeg', 'gif', 'webp'] as $e) {
                Storage::disk('public')->delete('avatars/user_' . $user->id . '.' . $e);
            }

            $avatar->storeAs('avatars', 'user_' . $user->id . '.' . $ext, 'public');
        }

        // Handle avatar delete
        if ($request->boolean('delete_avatar')) {
            foreach (['png', 'jpg', 'jpeg', 'gif', 'webp'] as $e) {
                Storage::disk('public')->delete('avatars/user_' . $user->id . '.' . $e);
            }
        }

        return redirect()->route('member.profil.show')->with('success', 'Profil berhasil diperbarui.');
    }
}
