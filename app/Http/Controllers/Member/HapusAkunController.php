<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HapusAkunController extends Controller
{
    /**
     * Member requests account deletion.
     * Sets delete_requested_at timestamp; deletion happens after 7 days if member doesn't log in.
     */
    public function request(Request $request)
    {
        $user = Auth::user();

        if ($user->delete_requested_at) {
            return back()->with('info', 'Permintaan hapus akun sudah aktif. Akun akan dihapus permanen setelah 7 hari tidak login.');
        }

        $user->update(['delete_requested_at' => now()]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Permintaan hapus akun berhasil dikirim. Akun Anda akan dihapus permanen setelah 7 hari jika tidak login kembali. Jika Anda login sebelum 7 hari, permintaan hapus akun akan dibatalkan otomatis.');
    }

    /**
     * Member cancels account deletion request.
     */
    public function cancel(Request $request)
    {
        $user = Auth::user();
        $user->update(['delete_requested_at' => null]);

        return back()->with('success', 'Permintaan hapus akun berhasil dibatalkan. Akun Anda aman!');
    }
}
