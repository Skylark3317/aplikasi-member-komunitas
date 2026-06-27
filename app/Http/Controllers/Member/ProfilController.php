<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use App\Models\MemberProfile;

class ProfilController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load('memberProfile');
        $memberProfile = $user->memberProfile;

        $profileData = [
            'id'                   => $user->id,
            'name'                 => $user->name,
            'email'                => $user->email,
            'telephone'            => $user->telephone,
            'role'                 => $user->role,
            'is_active'            => $user->is_active,
            'created_at'           => $user->created_at?->translatedFormat('j F Y'),
            'is_premium'           => $user->isPremium(),
            'status'               => $user->membershipStatus(),
            'avatar_url'           => $user->avatar_url,
            'delete_requested_at'  => $user->delete_requested_at?->toISOString(),
        ];

        if ($memberProfile) {
            $expireDate = $memberProfile->expire_date
                ? \Carbon\Carbon::parse($memberProfile->expire_date)
                : null;

            $profileData['member_profile'] = [
                'member_number'   => $memberProfile->member_number,
                'gender'          => $memberProfile->gender,
                'blood_type'      => $memberProfile->blood_type,
                'last_education'  => $memberProfile->last_education,
                'institution'     => $memberProfile->institution,
                'department'      => $memberProfile->department,
                'address'         => $memberProfile->address,
                'status'          => $memberProfile->status,
                'expire_date'     => $expireDate?->translatedFormat('j F Y'),
                'days_remaining'  => $expireDate ? max(0, now()->diffInDays($expireDate, false)) : 0,
                'expertise'       => $memberProfile->expertise ?? [],
                'expertise_proof' => is_array($memberProfile->expertise_proof) 
                    ? array_map(fn($p) => Storage::url($p), $memberProfile->expertise_proof) 
                    : ($memberProfile->expertise_proof ? [Storage::url($memberProfile->expertise_proof)] : []),
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
                'gender'          => $user->memberProfile->gender,
                'blood_type'      => $user->memberProfile->blood_type,
                'last_education'  => $user->memberProfile->last_education,
                'institution'     => $user->memberProfile->institution,
                'department'      => $user->memberProfile->department,
                'address'         => $user->memberProfile->address,
                'expertise'       => is_array($user->memberProfile->expertise) ? $user->memberProfile->expertise : ($user->memberProfile->expertise ? [$user->memberProfile->expertise] : []),
                'expertise_proof' => is_array($user->memberProfile->expertise_proof) 
                    ? array_map(fn($p) => Storage::url($p), $user->memberProfile->expertise_proof) 
                    : ($user->memberProfile->expertise_proof ? [Storage::url($user->memberProfile->expertise_proof)] : []),
            ] : null,
        ];

        $defaultExpertises = [
    // --- Teknologi & IT ---
    'Web Development', 'Mobile Development', 'UI/UX Design', 
    'Data Science', 'Machine Learning', 'Digital Marketing', 
    'Project Management', 'Cyber Security', 'Cloud Computing', 
    'DevOps', 'Graphic Design', 'SEO Optimization', 'Software Engineering',
    'IT Support', 'Network Administration', 'Database Administration',

    // --- Bisnis, Keuangan & Manajemen ---
    'Business Strategy', 'Financial Analysis', 'Human Resource Management', 
    'Accounting', 'Supply Chain Management', 'Risk Management', 
    'Sales & Business Development', 'Customer Relationship Management',

    // --- Industri Kreatif, Media & Seni ---
    'Content Writing', 'Copywriting', 'Video Editing', 
    'Photography', 'Animation & 3D Modeling', 'Interior Design', 
    'Fashion Design', 'Music Production', 'Journalism',

    // --- Kesehatan & Medis ---
    'General Medicine', 'Nursing', 'Nutrition & Dietetics', 
    'Physiotherapy', 'Psychology & Counseling', 'Pharmacology',

    // --- Hukum & Edukasi ---
    'Corporate Law', 'Intellectual Property Law', 'Public Speaking', 
    'Academic Research', 'Curriculum Development', 'Translation & Interpretation',

    // --- Teknik & Sains (Non-IT) ---
    'Mechanical Engineering', 'Civil Engineering', 'Electrical Engineering', 
    'Biotechnology', 'Environmental Science', 'Architecture',

    // --- Kuliner & Hospitality ---
    'Culinary Arts', 'Pastry & Baking', 'Hospitality Management', 'Event Planning'
];
        // Pluck all expertises, decode/flatten them, merge with defaults, and get unique values
        $expertises = MemberProfile::whereNotNull('expertise')
            ->pluck('expertise')
            ->flatten()
            ->merge($defaultExpertises)
            ->unique()
            ->filter()
            ->values()
            ->toArray();

        return Inertia::render('Member/Profil/Edit', [
            'user'       => $profileData,
            'expertises' => $expertises,
        ]);
    }

    public function update(Request $request)
    {
        // Load memberProfile relation upfront so the if-check below is reliable
        $user = $request->user()->load('memberProfile');

        $rules = [
            'name'             => ['required', 'string', 'max:255'],
            'telephone'        => ['nullable', 'string', 'max:20'],
            'gender'           => ['nullable', 'string', 'max:255'],
            'blood_type'       => ['nullable', 'string', 'max:255'],
            'last_education'   => ['nullable', 'string', 'max:255'],
            'institution'      => ['nullable', 'string', 'max:255'],
            'department'       => ['nullable', 'string', 'max:255'],
            'address'          => ['nullable', 'string'],
            'avatar'           => ['nullable', 'image', 'max:1024'],
            'expertise'        => ['nullable', 'array', 'max:3'],
            'expertise.*'      => ['nullable', 'string', 'max:255'],
            'expertise_proofs' => ['nullable', 'array', 'max:10'],
            'expertise_proofs.*'=> ['file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'existing_proofs'  => ['nullable', 'array'],
            'existing_proofs.*'=> ['nullable', 'string'],
        ];

        if ($request->filled('old_password') || $request->filled('password')) {
            $rules['old_password'] = ['required', 'current_password'];
            $rules['password']     = ['required', 'confirmed', Password::defaults()];
        }

        $validated = $request->validate($rules);

        // Save user fields — allow telephone to be cleared (defaults to '-' in DB to satisfy NOT NULL)
        $user->name      = $validated['name'];
        $user->telephone = $validated['telephone'] ?? '-';

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Update or create member profile
        $profileFields = [
            'gender'         => $validated['gender'] ?? null,
            'blood_type'     => $validated['blood_type'] ?? null,
            'last_education' => $validated['last_education'] ?? null,
            'institution'    => $validated['institution'] ?? null,
            'department'     => $validated['department'] ?? null,
            'address'        => $validated['address'] ?? '-',
            'expertise'      => array_values(array_filter($validated['expertise'] ?? [])),
        ];

        // Handle expertise_proofs
        $newProofs = [];
        if ($request->hasFile('expertise_proofs')) {
            foreach ($request->file('expertise_proofs') as $proof) {
                $ext   = $proof->getClientOriginalExtension();
                $path  = $proof->storeAs('kepakaran', 'proof_user_' . $user->id . '_' . uniqid() . '.' . $ext, 'public');
                $newProofs[] = $path;
            }
        }

        $existingProofsInput = $validated['existing_proofs'] ?? [];
        $existingProofsKeep = [];
        $oldProofs = $user->memberProfile && is_array($user->memberProfile->expertise_proof) 
            ? $user->memberProfile->expertise_proof 
            : [];

        foreach ($oldProofs as $old) {
            $oldUrl = Storage::url($old);
            if (in_array($oldUrl, $existingProofsInput)) {
                $existingProofsKeep[] = $old;
            } else {
                Storage::disk('public')->delete($old);
            }
        }

        $finalProofs = array_merge($existingProofsKeep, $newProofs);
        $profileFields['expertise_proof'] = $finalProofs;

        if ($user->memberProfile) {
            $user->memberProfile->update($profileFields);
        } else {
            $user->memberProfile()->create(array_merge($profileFields, [
                'status'        => 'nonactive',
                'expire_date'   => now(),
                'member_number' => MemberProfile::generateMemberNumber(),
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