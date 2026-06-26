<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class RiwayatAktivitasController extends Controller
{
    public function index(Request $request): Response
    {
        $query = ActivityLog::with('user:id,name,email')
            ->orderByDesc('created_at');

        // Filter by action type
        if ($request->filled('action') && $request->action !== 'semua') {
            $query->where('action', $request->action);
        }

        // Filter by target type
        if ($request->filled('target') && $request->target !== 'semua') {
            $query->where('target_type', $request->target);
        }

        // Filter by date range
        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        // Search by target label
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('target_label', 'like', '%' . $request->search . '%')
                  ->orWhere('action', 'like', '%' . $request->search . '%');
            });
        }

        $logs = $query->paginate(25)->withQueryString()->through(function ($log) {
            return [
                'id'           => $log->id,
                'action'       => $log->action,
                'target_type'  => $log->target_type,
                'target_id'    => $log->target_id,
                'target_label' => $log->target_label,
                'metadata'     => $log->metadata,
                'ip_address'   => $log->ip_address,
                'actor_name'   => $log->user?->name ?? '-',
                'actor_email'  => $log->user?->email ?? '-',
                'created_at'   => $log->created_at->translatedFormat('j F Y, H:i'),
                'created_at_iso' => $log->created_at->toISOString(),
            ];
        });

        // Unique action types for filter dropdown
        $actionTypes = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $targetTypes = ActivityLog::select('target_type')
            ->whereNotNull('target_type')
            ->distinct()
            ->orderBy('target_type')
            ->pluck('target_type');

        return Inertia::render('Admin/RiwayatAktivitas', [
            'logs'        => $logs,
            'actionTypes' => $actionTypes,
            'targetTypes' => $targetTypes,
            'filters'     => $request->only(['action', 'target', 'dari', 'sampai', 'search']),
            'totalCount'  => ActivityLog::count(),
        ]);
    }

    public function revert(Request $request, $id)
    {
        $log = ActivityLog::findOrFail($id);
        $metadata = $log->metadata;

        if (!$metadata || !is_array($metadata)) {
            return back()->with('error', 'Aktivitas ini tidak memiliki detail perubahan yang bisa dikembalikan.');
        }

        if (strpos($log->action, 'Ubah') === false && strpos($log->action, 'Update') === false) {
            return back()->with('error', 'Hanya aksi perubahan (update) yang saat ini dapat dikembalikan versinya.');
        }

        DB::beginTransaction();
        try {
            if ($log->target_type === 'Setting') {
                foreach ($metadata as $key => $change) {
                    if (isset($change['old'])) {
                        // If it's a JSON string we should decode/encode properly? No, setting values are strings or json strings.
                        $val = is_array($change['old']) ? json_encode($change['old']) : $change['old'];
                        Setting::set($key, $val);
                    }
                }
            } else {
                // Dynamically resolve model class
                $modelClass = '\\App\\Models\\' . $log->target_type;
                if (!class_exists($modelClass)) {
                    throw new \Exception("Model {$log->target_type} tidak ditemukan.");
                }

                $model = $modelClass::find($log->target_id);
                if (!$model) {
                    throw new \Exception("Data {$log->target_type} (ID: {$log->target_id}) tidak ditemukan, mungkin sudah dihapus.");
                }

                foreach ($metadata as $key => $change) {
                    if (is_array($change) && array_key_exists('old', $change)) {
                        $model->{$key} = $change['old'];
                    }
                }
                $model->save();
            }

            ActivityLog::record(
                'Kembalikan ke Versi (Revert)',
                $log->target_type,
                $log->target_id,
                $log->target_label,
                ['reverted_log_id' => $log->id]
            );

            DB::commit();
            return back()->with('success', 'Berhasil mengembalikan ke versi sebelumnya.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengembalikan versi: ' . $e->getMessage());
        }
    }
}
