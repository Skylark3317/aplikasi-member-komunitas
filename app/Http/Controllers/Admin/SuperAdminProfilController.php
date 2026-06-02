<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class SuperAdminProfilController extends Controller
{
    public function show(): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return Inertia::render('Admin/Profil', [
            'user' => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'telephone'  => $user->telephone,
                'role'       => $user->role,
                'is_active'  => $user->is_active,
                'created_at' => $user->created_at?->translatedFormat('j F Y'),
            ],
        ]);
    }

    public function edit(): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return Inertia::render('Admin/EditProfil', [
            'user' => [
                'id'        => $user->id,
                'name'      => $user->name,
                'email'     => $user->email,
                'telephone' => $user->telephone,
                'role'      => $user->role,
            ],
        ]);
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'telephone'    => 'required|string|max:20',
            'old_password' => 'nullable|string',
            'password'     => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->telephone = $request->telephone;

        if ($request->filled('password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Password lama tidak sesuai.']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('superadmin.profil')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
