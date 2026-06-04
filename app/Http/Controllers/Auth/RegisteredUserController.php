<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'phone'          => 'nullable|string|max:20',
            'gender'         => 'nullable|string|max:255',
            'blood_type'     => 'nullable|string|max:255',
            'last_education' => 'nullable|string|max:255',
            'institution'    => 'nullable|string|max:255',
            'department'     => 'nullable|string|max:255',
            'address'        => 'nullable|string',
            'password'       => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'telephone'   => $request->phone ?? '-',
            'role'        => 'member',
            'password'    => Hash::make($request->password),
        ]);

        \App\Models\MemberProfile::create([
            'member_id'      => $user->id,
            'member_number'  => \App\Models\MemberProfile::generateMemberNumber(),
            'expire_date'    => now(),
            'gender'         => $request->gender,
            'blood_type'     => $request->blood_type,
            'last_education' => $request->last_education,
            'institution'    => $request->institution,
            'department'     => $request->department,
            'address'        => $request->address ?? '-',
            'status'         => 'active',
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('verification.notice'));
    }
}
