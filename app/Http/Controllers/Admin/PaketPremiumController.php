<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Invoice;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PaketPremiumController extends Controller
{
    public function index(): Response
    {
        $plans = MembershipPlan::ordered()
            ->get()
            ->map(fn (MembershipPlan $plan) => $this->toResource($plan));

        $availableBenefits = \App\Models\Setting::get('available_benefits');
        $availableBenefits = $availableBenefits ? json_decode($availableBenefits, true) : [];

        return Inertia::render('Admin/PaketPremium', [
            'plans' => $plans,
            'availableBenefits' => $availableBenefits,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatePlan($request);

        $plan = MembershipPlan::create($data);

        $this->ensureSingleRecommended($plan);

        ActivityLog::record(
            'Buat Paket Premium',
            'MembershipPlan',
            $plan->id,
            $plan->name,
            $data
        );

        return redirect()->route('superadmin.paket-premium.index')
            ->with('success', 'Paket premium berhasil dibuat.');
    }

    public function update(Request $request, MembershipPlan $plan)
    {
        $data = $this->validatePlan($request, $plan);

        $changes = [];
        foreach ($data as $key => $value) {
            $current = $plan->{$key};
            // Normalisasi perbandingan untuk array & boolean
            $normalizedCurrent = is_array($current) ? $current : (is_bool($current) ? (int) $current : $current);
            $normalizedValue   = is_array($value) ? $value : (is_bool($value) ? (int) $value : $value);
            if ($normalizedCurrent != $normalizedValue) {
                $changes[$key] = ['old' => $current, 'new' => $value];
            }
        }

        $plan->update($data);

        $this->ensureSingleRecommended($plan);

        if (!empty($changes)) {
            ActivityLog::record(
                'Ubah Paket Premium',
                'MembershipPlan',
                $plan->id,
                $plan->name,
                $changes
            );
        }

        return redirect()->route('superadmin.paket-premium.index')
            ->with('success', 'Paket premium berhasil diperbarui.');
    }

    public function destroy(MembershipPlan $plan)
    {
        $name = $plan->name;
        $id   = $plan->id;

        $plan->delete();

        ActivityLog::record(
            'Hapus Paket Premium',
            'MembershipPlan',
            $id,
            $name,
            null
        );

        return redirect()->route('superadmin.paket-premium.index')
            ->with('success', 'Paket premium berhasil dihapus.');
    }

    public function toggleStatus(MembershipPlan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);

        ActivityLog::record(
            $plan->is_active ? 'Aktifkan Paket Premium' : 'Nonaktifkan Paket Premium',
            'MembershipPlan',
            $plan->id,
            $plan->name,
            ['is_active' => $plan->is_active]
        );

        return back()->with('success', $plan->is_active
            ? 'Paket berhasil diaktifkan.'
            : 'Paket berhasil dinonaktifkan.'
        );
    }

    public function toggleRecommended(MembershipPlan $plan)
    {
        $plan->update(['is_recommended' => !$plan->is_recommended]);

        $this->ensureSingleRecommended($plan);

        ActivityLog::record(
            $plan->is_recommended ? 'Tandai Paket Direkomendasikan' : 'Hapus Tanda Direkomendasikan',
            'MembershipPlan',
            $plan->id,
            $plan->name,
            ['is_recommended' => $plan->is_recommended]
        );

        return back()->with('success', $plan->is_recommended
            ? 'Paket ditandai sebagai rekomendasi.'
            : 'Tanda rekomendasi dihapus.'
        );
    }

    /**
     * Validasi payload paket premium.
     */
    private function validatePlan(Request $request, ?MembershipPlan $plan = null): array
    {
        $uniqueRule = 'unique:membership_plans,name';
        if ($plan) {
            $uniqueRule .= ',' . $plan->id;
        }

        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255', $uniqueRule],
            'description'    => 'nullable|string|max:1000',
            'price'          => 'required|numeric|min:0',
            'duration'       => 'required|integer|min:0',
            'duration_unit'  => 'required|in:day,month,year',
            'is_lifetime'    => 'boolean',
            'features'       => 'nullable|array',
            'features.*'     => 'string|max:255',
            'is_recommended' => 'boolean',
            'is_active'      => 'boolean',
            'sort_order'     => 'nullable|integer|min:0',
        ], [
            'name.unique' => 'Nama paket premium sudah digunakan. Silakan gunakan nama yang berbeda.',
        ]);

        // Lifetime mengabaikan durasi
        if (!empty($validated['is_lifetime'])) {
            $validated['duration'] = 0;
        }

        return [
            'name'           => $validated['name'],
            'description'    => $validated['description'] ?? null,
            'price'          => $validated['price'],
            'duration'       => $validated['duration'],
            'duration_unit'  => $validated['duration_unit'],
            'is_lifetime'    => (bool) ($validated['is_lifetime'] ?? false),
            'features'       => array_values(array_filter($validated['features'] ?? [])),
            'is_recommended' => (bool) ($validated['is_recommended'] ?? false),
            'is_active'      => (bool) ($validated['is_active'] ?? true),
            'sort_order'     => $validated['sort_order'] ?? 0,
        ];
    }

    /**
     * Hanya boleh ada satu paket rekomendasi pada satu waktu.
     */
    private function ensureSingleRecommended(MembershipPlan $plan): void
    {
        if ($plan->is_recommended) {
            MembershipPlan::where('id', '!=', $plan->id)
                ->where('is_recommended', true)
                ->update(['is_recommended' => false]);
        }
    }

    private function toResource(MembershipPlan $plan): array
    {
        $availableBenefits = \App\Models\Setting::get('available_benefits');
        $availableBenefits = $availableBenefits ? json_decode($availableBenefits, true) : [];
        
        $features = $plan->features ?? [];
        if (!empty($availableBenefits)) {
            $features = array_values(array_intersect($features, $availableBenefits));
        }

        return [
            'id'             => $plan->id,
            'name'           => $plan->name,
            'description'    => $plan->description,
            'price'          => (float) $plan->price,
            'duration'       => $plan->duration,
            'duration_unit'  => $plan->duration_unit,
            'is_lifetime'    => $plan->is_lifetime,
            'features'       => $features,
            'is_recommended' => $plan->is_recommended,
            'is_active'      => $plan->is_active,
            'sort_order'     => $plan->sort_order,
            'duration_label' => $plan->durationLabel(),
        ];
    }
}
