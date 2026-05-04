<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Payment;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class StatistikController extends Controller
{
    public function index(Request $request): Response
    {
        $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $currentYear = now()->year;

        // Member stats per month
        $memberData = [];
        $memberAktif = [];
        $memberNonaktif = [];
        for ($m = 1; $m <= 12; $m++) {
            $aktif    = User::where('role', 'member')->where('is_active', true)->whereMonth('created_at', $m)->whereYear('created_at', $currentYear)->count();
            $nonaktif = User::where('role', 'member')->where('is_active', false)->whereMonth('created_at', $m)->whereYear('created_at', $currentYear)->count();
            $memberAktif[]    = $aktif;
            $memberNonaktif[] = $nonaktif;
        }

        // Post stats per month
        $postData = [];
        for ($m = 1; $m <= 12; $m++) {
            $postData[] = Post::whereMonth('published_at', $m)->whereYear('published_at', $currentYear)->count();
        }

        // Payment stats per month
        $payDiterima   = [];
        $payDitolak    = [];
        $payMenunggu   = [];
        for ($m = 1; $m <= 12; $m++) {
            $payDiterima[] = Payment::where('status','diterima')->whereMonth('created_at',$m)->whereYear('created_at',$currentYear)->sum('amount') / 1_000_000;
            $payDitolak[]  = Payment::where('status','ditolak')->whereMonth('created_at',$m)->whereYear('created_at',$currentYear)->sum('amount') / 1_000;
            $payMenunggu[] = Payment::where('status','menunggu')->whereMonth('created_at',$m)->whereYear('created_at',$currentYear)->sum('amount') / 1_000;
        }

        return Inertia::render('Admin/Statistik', [
            'months'         => $months,
            'currentMonth'   => now()->format('F Y'),
            'stats' => [
                'member' => [
                    'total'    => User::where('role','member')->count(),
                    'aktif'    => User::where('role','member')->where('is_active',true)->count(),
                    'nonaktif' => User::where('role','member')->where('is_active',false)->count(),
                    'aktifData'    => $memberAktif,
                    'nonaktifData' => $memberNonaktif,
                ],
                'blog' => [
                    'total' => Post::count(),
                    'data'  => $postData,
                ],
                'payment' => [
                    'diterima'       => Payment::where('status','diterima')->sum('amount'),
                    'ditolak'        => Payment::where('status','ditolak')->sum('amount'),
                    'menunggu'       => Payment::where('status','menunggu')->sum('amount'),
                    'diterimaData'   => $payDiterima,
                    'ditolakData'    => $payDitolak,
                    'menungguData'   => $payMenunggu,
                ],
            ],
        ]);
    }
}
