<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class ProfilController extends Controller
{
    public function show(Request $request)
    {
        return Inertia::render('Petugas/Profil/Show', [
            'user' => $request->user(),
        ]);
    }

    public function edit(Request $request)
    {
        return Inertia::render('Petugas/Profil/Edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $rules = [
            'telephone' => ['nullable', 'string', 'max:20'],
        ];

        if ($request->filled('old_password') || $request->filled('password')) {
            $rules['old_password'] = ['required', 'current_password'];
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $validated = $request->validate($rules);

        $user->telephone = $validated['telephone'] ?? $user->telephone;

        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('petugas.profil')->with('success', 'Profil berhasil diperbarui.');
    }
}
