<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MemberController extends Controller
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

        // Historical premium member IDs (for stats consistency with Ketua)
        $premiumIds = Invoice::where('is_accepted', true)
            ->pluck('user_id')->unique()->values()->all();

        $memberTotal   = User::where('role', 'member')->count();
        $memberPremium = User::where('role', 'member')->whereIn('id', $premiumIds)->count();
        $memberRegular = User::where('role', 'member')->whereNotIn('id', $premiumIds)->count();

        // ── MEMBER LIST QUERY (WITH FILTERS) ──────────────────────────────────
        $query = User::where('role', 'member')->with(['memberProfile.plan']);

        // Date Filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Status Filter
        if ($request->filled('status')) {
            $statusVal = $request->status;
            if ($statusVal === 'premium') {
                $query->whereHas('memberProfile', function ($q) {
                    $q->where('status', 'active')->where('expire_date', '>', now());
                });
            } elseif ($statusVal === 'regular') {
                $query->where(function ($q) {
                    $q->whereDoesntHave('memberProfile')
                      ->orWhereHas('memberProfile', function ($sq) {
                          $sq->where('status', '!=', 'active')->orWhere('expire_date', '<=', now());
                      });
                });
            }
        }

        // Premium Plan Filter
        if ($request->filled('plan_id')) {
            $query->whereHas('memberProfile', function ($q) use ($request) {
                $q->where('plan_id', $request->plan_id);
            });
        }

        $members = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($user) {
                $isPremium = $user->isPremium();
                $profile = $user->memberProfile;
                
                $status = $isPremium ? 'Premium' : 'Regular';
                $planName = '-';
                if ($isPremium && $profile) {
                    if ($profile->plan_snapshot && isset($profile->plan_snapshot['name'])) {
                        $planName = $profile->plan_snapshot['name'];
                    } elseif ($profile->plan) {
                        $planName = $profile->plan->name;
                    }
                }
                $expireDate = ($isPremium && $profile && $profile->expire_date) 
                    ? Carbon::parse($profile->expire_date)->format('d M Y') 
                    : '-';

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'telephone' => $user->telephone ?? '-',
                    'status' => $status,
                    'plan_name' => $planName,
                    'expire_date' => $expireDate,
                    'joined_at' => $user->created_at->format('d M Y'),
                ];
            });

        // Get Premium Plans for filter options
        $plans = \App\Models\MembershipPlan::ordered()->get(['id', 'name'])->all();

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

        return Inertia::render('Petugas/Member/Index', [
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
                ]
            ],
            'plans'   => $plans,
            'filters' => $request->only(['status', 'plan_id', 'start_date', 'end_date']),
            'members' => $members,
        ]);
    }
}
