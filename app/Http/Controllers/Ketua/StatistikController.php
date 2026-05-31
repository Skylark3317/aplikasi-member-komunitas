<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Content;
use App\Models\Conversation;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StatistikController extends Controller
{
    public function index(Request $request): Response
    {
        $now          = Carbon::now();
        $currentYear  = (int) $request->input('year', $now->year);
        $currentMonth = (int) $request->input('month', $now->month);
        $daysInMonth  = Carbon::create($currentYear, $currentMonth, 1)->daysInMonth;
        $monthNames   = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $maxYearStart = $now->year - 9;
        $maxYears     = range($maxYearStart, $now->year);

        // Premium member IDs (has at least one accepted invoice)
        $premiumIds = Invoice::where('is_accepted', true)
            ->pluck('user_id')->unique()->values()->all();

        // ── MEMBER ───────────────────────────────────────────────────────────
        $memberTotal   = User::where('role', 'member')->count();
        $memberPremium = User::where('role', 'member')->whereIn('id', $premiumIds)->count();
        $memberRegular = User::where('role', 'member')->whereNotIn('id', $premiumIds)->count();

        $premYearly = $regYearly = $premMonthly = $regMonthly = $premMax = $regMax = [];
        for ($m = 1; $m <= 12; $m++) {
            $base = User::where('role','member')->whereYear('created_at',$currentYear)->whereMonth('created_at',$m);
            $premYearly[] = (clone $base)->whereIn('id',$premiumIds)->count();
            $regYearly[]  = (clone $base)->whereNotIn('id',$premiumIds)->count();
        }
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $base = User::where('role','member')->whereYear('created_at',$currentYear)->whereMonth('created_at',$currentMonth)->whereDay('created_at',$d);
            $premMonthly[] = (clone $base)->whereIn('id',$premiumIds)->count();
            $regMonthly[]  = (clone $base)->whereNotIn('id',$premiumIds)->count();
        }
        foreach ($maxYears as $y) {
            $base = User::where('role','member')->whereYear('created_at',$y);
            $premMax[] = (clone $base)->whereIn('id',$premiumIds)->count();
            $regMax[]  = (clone $base)->whereNotIn('id',$premiumIds)->count();
        }

        // ── KONTEN ───────────────────────────────────────────────────────────
        $kontenTotal = Content::count();
        $kontenVideo = Content::where('type','video')->count();
        $kontenEbook = Content::where('type','ebook')->count();

        $vidYearly = $ebYearly = $vidMonthly = $ebMonthly = $vidMax = $ebMax = [];
        for ($m = 1; $m <= 12; $m++) {
            $vidYearly[] = Content::where('type','video')->whereYear('created_at',$currentYear)->whereMonth('created_at',$m)->count();
            $ebYearly[]  = Content::where('type','ebook')->whereYear('created_at',$currentYear)->whereMonth('created_at',$m)->count();
        }
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $vidMonthly[] = Content::where('type','video')->whereYear('created_at',$currentYear)->whereMonth('created_at',$currentMonth)->whereDay('created_at',$d)->count();
            $ebMonthly[]  = Content::where('type','ebook')->whereYear('created_at',$currentYear)->whereMonth('created_at',$currentMonth)->whereDay('created_at',$d)->count();
        }
        foreach ($maxYears as $y) {
            $vidMax[] = Content::where('type','video')->whereYear('created_at',$y)->count();
            $ebMax[]  = Content::where('type','ebook')->whereYear('created_at',$y)->count();
        }

        // ── BLOG / POSTS ─────────────────────────────────────────────────────
        $blogTotal = Post::count();
        $catBerita = Category::where('slug','berita')->first();
        $catAcara  = Category::where('slug','acara')->first();

        $beritaYearly = $acaraYearly = $beritaMonthly = $acaraMonthly = $beritaMax = $acaraMax = [];
        for ($m = 1; $m <= 12; $m++) {
            $beritaYearly[] = Post::where('category_id', $catBerita?->id ?? 0)->whereYear('published_at',$currentYear)->whereMonth('published_at',$m)->count();
            $acaraYearly[]  = Post::where('category_id', $catAcara?->id ?? 0)->whereYear('published_at',$currentYear)->whereMonth('published_at',$m)->count();
        }
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $beritaMonthly[] = Post::where('category_id', $catBerita?->id ?? 0)->whereYear('published_at',$currentYear)->whereMonth('published_at',$currentMonth)->whereDay('published_at',$d)->count();
            $acaraMonthly[]  = Post::where('category_id', $catAcara?->id ?? 0)->whereYear('published_at',$currentYear)->whereMonth('published_at',$currentMonth)->whereDay('published_at',$d)->count();
        }
        foreach ($maxYears as $y) {
            $beritaMax[] = Post::where('category_id', $catBerita?->id ?? 0)->whereYear('published_at',$y)->count();
            $acaraMax[]  = Post::where('category_id', $catAcara?->id ?? 0)->whereYear('published_at',$y)->count();
        }

        // ── PERTANYAAN ───────────────────────────────────────────────────────
        $pertTotal = Conversation::count();
        
        $selesaiFn = fn($q) => $q->where('is_closed', true);
        $direspondFn = fn($q) => $q->where('is_closed', false)
            ->whereHas('messages.sender', fn($sq) => $sq->whereIn('role', ['staff', 'super_admin']));
        $belumFn = fn($q) => $q->where('is_closed', false)
            ->whereDoesntHave('messages.sender', fn($sq) => $sq->whereIn('role', ['staff', 'super_admin']));

        $pertSelesai   = $selesaiFn(Conversation::query())->count();
        $pertDirespond = $direspondFn(Conversation::query())->count();
        $pertBelum     = $belumFn(Conversation::query())->count();

        $slYearly = $drYearly = $blYearly = $slMonthly = $drMonthly = $blMonthly = $slMax = $drMax = $blMax = [];
        for ($m = 1; $m <= 12; $m++) {
            $base = Conversation::whereYear('created_at', $currentYear)->whereMonth('created_at', $m);
            $slYearly[] = $selesaiFn(clone $base)->count();
            $drYearly[] = $direspondFn(clone $base)->count();
            $blYearly[] = $belumFn(clone $base)->count();
        }
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $base = Conversation::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)->whereDay('created_at', $d);
            $slMonthly[] = $selesaiFn(clone $base)->count();
            $drMonthly[] = $direspondFn(clone $base)->count();
            $blMonthly[] = $belumFn(clone $base)->count();
        }
        foreach ($maxYears as $y) {
            $base = Conversation::whereYear('created_at', $y);
            $slMax[] = $selesaiFn(clone $base)->count();
            $drMax[] = $direspondFn(clone $base)->count();
            $blMax[] = $belumFn(clone $base)->count();
        }

        // ── PAYMENT ──────────────────────────────────────────────────────────
        $payDiterima = Payment::where('status','diverifikasi')->sum('amount');
        $payDitolak  = Payment::where('status','ditolak')->sum('amount');
        $payMenunggu = Payment::where('status','menunggu')->sum('amount');
        $fmtRp = fn($v) => $v >= 1_000_000 ? 'Rp'.number_format($v/1_000_000,1,',','.').'jt' : 'Rp'.number_format($v/1_000,0,',','.').'rb';

        $dtYearly = $dkYearly = $mgYearly = $dtMonthly = $dkMonthly = $mgMonthly = $dtMax = $dkMax = $mgMax = [];
        for ($m = 1; $m <= 12; $m++) {
            $dtYearly[] = round((float) Payment::where('status','diverifikasi')->whereYear('created_at',$currentYear)->whereMonth('created_at',$m)->sum('amount')/1_000_000,2);
            $dkYearly[] = round((float) Payment::where('status','ditolak')->whereYear('created_at',$currentYear)->whereMonth('created_at',$m)->sum('amount')/1_000_000,2);
            $mgYearly[] = round((float) Payment::where('status','menunggu')->whereYear('created_at',$currentYear)->whereMonth('created_at',$m)->sum('amount')/1_000_000,2);
        }
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dtMonthly[] = round((float) Payment::where('status','diverifikasi')->whereYear('created_at',$currentYear)->whereMonth('created_at',$currentMonth)->whereDay('created_at',$d)->sum('amount')/1_000_000,2);
            $dkMonthly[] = round((float) Payment::where('status','ditolak')->whereYear('created_at',$currentYear)->whereMonth('created_at',$currentMonth)->whereDay('created_at',$d)->sum('amount')/1_000_000,2);
            $mgMonthly[] = round((float) Payment::where('status','menunggu')->whereYear('created_at',$currentYear)->whereMonth('created_at',$currentMonth)->whereDay('created_at',$d)->sum('amount')/1_000_000,2);
        }
        foreach ($maxYears as $y) {
            $dtMax[] = round((float) Payment::where('status','diverifikasi')->whereYear('created_at',$y)->sum('amount')/1_000_000,2);
            $dkMax[] = round((float) Payment::where('status','ditolak')->whereYear('created_at',$y)->sum('amount')/1_000_000,2);
            $mgMax[] = round((float) Payment::where('status','menunggu')->whereYear('created_at',$y)->sum('amount')/1_000_000,2);
        }

        return Inertia::render('Ketua/Statistik', [
            'currentYear'  => $currentYear,
            'currentMonth' => $currentMonth,
            'monthNames'   => $monthNames,
            'daysInMonth'  => $daysInMonth,
            'maxYears'     => array_map('strval', $maxYears),
            'today'        => $now->day,
            'thisMonth'    => $now->month,
            'thisYear'     => $now->year,
            'stats' => [
                'member' => [
                    'stats'  => [
                        ['label'=>'Total',   'value'=> $memberTotal],
                        ['label'=>'Premium', 'value'=> $memberPremium],
                        ['label'=>'Regular', 'value'=> $memberRegular],
                    ],
                    'series' => [
                        ['label'=>'Premium','color'=>'#3b82f6','data1M'=>$premMonthly,'data1Y'=>$premYearly,'dataMax'=>$premMax],
                        ['label'=>'Regular','color'=>'#9ca3af','data1M'=>$regMonthly, 'data1Y'=>$regYearly, 'dataMax'=>$regMax],
                    ],
                ],
                'konten' => [
                    'stats'  => [
                        ['label'=>'Total','value'=> $kontenTotal],
                        ['label'=>'Video','value'=> $kontenVideo],
                        ['label'=>'Ebook','value'=> $kontenEbook],
                    ],
                    'series' => [
                        ['label'=>'Video','color'=>'#8b5cf6','data1M'=>$vidMonthly,'data1Y'=>$vidYearly,'dataMax'=>$vidMax],
                        ['label'=>'Ebook','color'=>'#d946ef','data1M'=>$ebMonthly, 'data1Y'=>$ebYearly, 'dataMax'=>$ebMax],
                    ],
                ],
                'blog' => [
                    'stats'  => [
                        ['label'=>'Total Blog','value'=> $blogTotal],
                        ['label'=>'Berita',    'value'=> Post::where('category_id',$catBerita?->id??0)->count()],
                        ['label'=>'Acara',     'value'=> Post::where('category_id',$catAcara?->id??0)->count()],
                    ],
                    'series' => [
                        ['label'=>'Berita','color'=>'#f59e0b','data1M'=>$beritaMonthly,'data1Y'=>$beritaYearly,'dataMax'=>$beritaMax],
                        ['label'=>'Acara', 'color'=>'#ef4444','data1M'=>$acaraMonthly, 'data1Y'=>$acaraYearly, 'dataMax'=>$acaraMax],
                    ],
                ],
                'pertanyaan' => [
                    'stats'  => [
                        ['label'=>'Total',           'value'=> $pertTotal],
                        ['label'=>'Selesai',         'value'=> $pertSelesai],
                        ['label'=>'Direspond',       'value'=> $pertDirespond],
                        ['label'=>'Belum direspond', 'value'=> $pertBelum],
                    ],
                    'series' => [
                        ['label'=>'Selesai',         'color'=>'#22c55e','data1M'=>$slMonthly,'data1Y'=>$slYearly,'dataMax'=>$slMax],
                        ['label'=>'Direspond',       'color'=>'#3b82f6','data1M'=>$drMonthly,'data1Y'=>$drYearly,'dataMax'=>$drMax],
                        ['label'=>'Belum direspond', 'color'=>'#f87171','data1M'=>$blMonthly,'data1Y'=>$blYearly,'dataMax'=>$blMax],
                    ],
                ],
                'payment' => [
                    'stats'  => [
                        ['label'=>'Diterima', 'value'=> $fmtRp($payDiterima)],
                        ['label'=>'Ditolak',  'value'=> $fmtRp($payDitolak)],
                        ['label'=>'Menunggu', 'value'=> $fmtRp($payMenunggu)],
                    ],
                    'series' => [
                        ['label'=>'Diterima','color'=>'#3b82f6','data1M'=>$dtMonthly,'data1Y'=>$dtYearly,'dataMax'=>$dtMax],
                        ['label'=>'Ditolak', 'color'=>'#ef4444','data1M'=>$dkMonthly,'data1Y'=>$dkYearly,'dataMax'=>$dkMax],
                        ['label'=>'Menunggu','color'=>'#9ca3af','data1M'=>$mgMonthly,'data1Y'=>$mgYearly,'dataMax'=>$mgMax],
                    ],
                ],
            ],
        ]);
    }
}
