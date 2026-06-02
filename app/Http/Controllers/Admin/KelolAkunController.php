<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MemberProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class KelolAkunController extends Controller
{
    public function index(Request $request): Response
    {
        $query = User::query()->where('role', '!=', 'super_admin');

        // Search by name or email
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by role
        if ($request->filled('role') && $request->role !== 'semua') {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('is_active', $request->status === 'aktif');
        }

        $users = $query->orderBy('name')->get()->map(function ($user) {
            return [
                'id'        => $user->id,
                'name'      => $user->name,
                'email'     => $user->email,
                'role'      => $user->role,
                'is_active' => $user->is_active,
            ];
        });

        return Inertia::render('Admin/KelolAkun', [
            'users'   => $users,
            'filters' => $request->only(['search', 'role', 'status']),
        ]);
    }

    public function show(User $user): Response
    {
        if ($user->role === 'super_admin') {
            abort(404);
        }

        $memberProfile = null;
        if ($user->role === 'member') {
            $memberProfile = $user->memberProfile;
        }

        $data = [
            'id'           => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'telephone'    => $user->telephone,
            'role'         => $user->role,
            'is_active'    => $user->is_active,
            'created_at'   => $user->created_at?->translatedFormat('j F Y'),
        ];

        if ($memberProfile) {
            $expireDate = $memberProfile->expire_date
                ? \Carbon\Carbon::parse($memberProfile->expire_date)
                : null;

            $data['member_profile'] = [
                'member_number'  => 'M' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'institution'    => $memberProfile->institution,
                'department'     => $memberProfile->department,
                'address'        => $memberProfile->address,
                'status'         => $memberProfile->status,
                'expire_date'    => $expireDate?->translatedFormat('j F Y'),
                'days_remaining' => $expireDate ? max(0, now()->diffInDays($expireDate, false)) : 0,
            ];
        }

        return Inertia::render('Admin/DetailAkun', [
            'user' => $data,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/BuatAkunBaru');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'telephone'             => 'required|string|max:20',
            'role'                  => 'required|in:staff,finance,leader',
            'password'              => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'telephone' => $request->telephone,
            'role'      => $request->role,
            'password'  => Hash::make($request->password),
            'is_active' => true,
        ]);

        $user->markEmailAsVerified();

        return redirect()->route('superadmin.kelol-akun.index')
            ->with('success', 'Akun berhasil dibuat.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->role === 'super_admin') {
            abort(403);
        }

        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', $user->is_active
            ? 'Akun berhasil diaktifkan.'
            : 'Akun berhasil dinonaktifkan.'
        );
    }

    public function destroy(User $user)
    {
        if ($user->role === 'super_admin') {
            abort(403, 'Tidak dapat menghapus Super Admin.');
        }

        $user->delete();

        return redirect()->route('superadmin.kelol-akun.index')
            ->with('success', 'Akun berhasil dihapus.');
    }
}
