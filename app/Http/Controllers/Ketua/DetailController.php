<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Conversation;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DetailController extends Controller
{
    public function index(Request $request, string $type): Response
    {
        $premiumIds = Invoice::where('is_accepted', true)
            ->pluck('user_id')->unique()->values()->all();

        [$rows, $columns, $title] = match ($type) {
            'member'     => $this->memberData($request, $premiumIds),
            'konten'     => $this->kontenData($request),
            'blog'       => $this->blogData($request),
            'pertanyaan' => $this->pertanyaanData($request),
            'payment'    => $this->paymentData($request),
            default      => abort(404),
        };

        $categories = [];
        if ($type === 'blog') {
            $categories = Category::orderBy('name')->get(['id', 'name'])->all();
        }

        return Inertia::render('Ketua/Detail', [
            'type'       => $type,
            'title'      => $title,
            'rows'       => $rows,
            'columns'    => $columns,
            'categories' => $categories,
            'filters'    => $request->only(['status', 'membership', 'content_type', 'category_id', 'start_date', 'end_date']),
        ]);
    }

    protected function memberData(Request $request, array $premiumIds): array
    {
        $query = User::where('role', 'member')->with('memberProfile');

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        if ($request->filled('membership')) {
            if ($request->membership === 'premium') {
                $query->whereIn('id', $premiumIds);
            } else {
                $query->whereNotIn('id', $premiumIds);
            }
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $rows = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(fn(User $u) => [
                'id'         => $u->id,
                'nama'       => $u->name,
                'email'      => $u->email,
                'telepon'    => $u->telephone ?? '-',
                'institusi'  => $u->memberProfile?->institution ?? '-',
                'departemen' => $u->memberProfile?->department ?? '-',
                'alamat'     => $u->memberProfile?->address ?? '-',
                'premium'    => in_array($u->id, $premiumIds) ? 'Premium' : 'Regular',
                'aktif'      => $u->is_active ? 'Aktif' : 'Nonaktif',
                'kelengkapan'=> $u->profileCompletionPercent() . '%',
                '_sort_kelengkapan' => $u->profileCompletionPercent(),
                'bergabung'  => $u->created_at->format('d M Y'),
                '_sort_bergabung' => $u->created_at->timestamp,
                'expire_date' => in_array($u->id, $premiumIds) && $u->memberProfile?->expire_date
                    ? \Carbon\Carbon::parse($u->memberProfile->expire_date)->translatedFormat('j F Y')
                    : '-',
            ])->values()->all();

        $columns = [
            ['key' => 'nama',       'label' => 'Nama',       'sortable' => true],
            ['key' => 'email',      'label' => 'Email',      'sortable' => true],
            ['key' => 'telepon',    'label' => 'Telepon',    'sortable' => false],
            ['key' => 'institusi',  'label' => 'Institusi',  'sortable' => true],
            ['key' => 'departemen', 'label' => 'Departemen', 'sortable' => true],
            ['key' => 'alamat',     'label' => 'Alamat',     'sortable' => true],
            ['key' => '_sort_kelengkapan', 'label' => 'Kelengkapan', 'sortable' => true, 'display' => 'kelengkapan', 'badge' => true],
            ['key' => 'premium',    'label' => 'Membership', 'sortable' => true, 'badge' => true],
            ['key' => 'aktif',      'label' => 'Status',      'sortable' => true, 'badge' => true],
            ['key' => '_sort_bergabung', 'label' => 'Bergabung', 'sortable' => true, 'display' => 'bergabung'],
            ['key' => 'expire_date', 'label' => 'Masa Aktif Premium', 'sortable' => false],
        ];

        return [$rows, $columns, 'Detail Member'];
    }

    protected function kontenData(Request $request): array
    {
        $query = Content::with('uploader:id,name');

        if ($request->filled('content_type')) {
            $query->where('type', $request->content_type);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $rows = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(fn(Content $c) => [
                'id'       => $c->id,
                'judul'    => $c->title,
                'tipe'     => ucfirst($c->type),
                'uploader' => $c->uploader?->name ?? '-',
                'diupload' => $c->created_at->format('d M Y'),
                '_sort_diupload' => $c->created_at->timestamp,
            ])->values()->all();

        $columns = [
            ['key' => 'judul',    'label' => 'Judul',    'sortable' => true],
            ['key' => 'tipe',     'label' => 'Tipe',     'sortable' => true, 'badge' => true],
            ['key' => 'uploader', 'label' => 'Uploader', 'sortable' => true],
            ['key' => '_sort_diupload', 'label' => 'Tanggal Upload', 'sortable' => true, 'display' => 'diupload'],
        ];

        return [$rows, $columns, 'Detail Konten'];
    }

    protected function blogData(Request $request): array
    {
        $query = Post::with(['author:id,name', 'category:id,name']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('published_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('published_at', '<=', $request->end_date);
        }

        $rows = $query->orderBy('published_at', 'desc')
            ->get()
            ->map(fn(Post $p) => [
                'id'           => $p->id,
                'judul'        => $p->title,
                'kategori'     => $p->category?->name ?? '-',
                'penulis'      => $p->author?->name ?? '-',
                'dipublikasikan' => $p->published_at?->format('d M Y') ?? '-',
                '_sort_publikasi' => $p->published_at?->timestamp ?? 0,
            ])->values()->all();

        $columns = [
            ['key' => 'judul',    'label' => 'Judul',    'sortable' => true],
            ['key' => 'kategori', 'label' => 'Kategori', 'sortable' => true, 'badge' => true],
            ['key' => 'penulis',  'label' => 'Penulis',  'sortable' => true],
            ['key' => '_sort_publikasi', 'label' => 'Dipublikasikan', 'sortable' => true, 'display' => 'dipublikasikan'],
        ];

        return [$rows, $columns, 'Detail Blog'];
    }

    protected function pertanyaanData(Request $request): array
    {
        $query = Conversation::with(['submitter:id,name', 'messages.sender:id,name,role']);

        if ($request->filled('status')) {
            if ($request->status === 'selesai') {
                $query->whereRaw('1 = 0');
            } elseif ($request->status === 'direspond') {
                $query->whereHas('messages.sender', function($q) {
                    $q->whereIn('role', ['staff', 'super_admin']);
                });
            } elseif ($request->status === 'belum_direspond') {
                $query->whereDoesntHave('messages.sender', function($q) {
                    $q->whereIn('role', ['staff', 'super_admin']);
                });
            }
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $rows = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function(Conversation $c) {
                // Find first staff/super_admin sender in replies
                $petugasName = collect($c->messages)
                    ->first(fn($m) => in_array($m->sender?->role, ['staff', 'super_admin']))
                    ?->sender?->name;

                // Determine status based on responder presence
                if ($petugasName) {
                    $status = 'Direspond';
                } else {
                    $status = 'Belum direspond';
                }

                return [
                    'id'      => $c->id,
                    'id_chat' => '#' . $c->id,
                    'penanya' => $c->submitter?->name ?? '-',
                    'petugas' => $petugasName ?? '-',
                    'status'  => $status,
                    'tanggal' => $c->created_at->format('d M Y'),
                    '_sort_tanggal' => $c->created_at->timestamp,
                ];
            })->values()->all();

        $columns = [
            ['key' => 'id_chat', 'label' => 'ID Chat',   'sortable' => true],
            ['key' => 'penanya', 'label' => 'Penanya',   'sortable' => true],
            ['key' => 'petugas', 'label' => 'Petugas',   'sortable' => true],
            ['key' => 'status',  'label' => 'Status',    'sortable' => true, 'badge' => true],
            ['key' => '_sort_tanggal', 'label' => 'Tanggal', 'sortable' => true, 'display' => 'tanggal'],
        ];

        return [$rows, $columns, 'Detail Pertanyaan'];
    }

    protected function paymentData(Request $request): array
    {
        $query = Payment::with(['payer:id,name', 'verifier:id,name']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $rows = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(fn(Payment $p) => [
                'id'      => $p->id,
                'pembayar'=> $p->payer?->name ?? '-',
                'bank'    => $p->account_bank_name,
                'rekening'=> $p->account_number,
                'nominal' => 'Rp'.number_format($p->amount, 0, ',', '.'),
                'status'  => match($p->status) {
                    'diverifikasi' => 'Diterima',
                    'ditolak'      => 'Ditolak',
                    default        => 'Menunggu',
                },
                'keuangan'=> $p->verifier?->name ?? '-',
                'tanggal' => \Carbon\Carbon::parse($p->date)->format('d M Y'),
                '_sort_nominal'  => (float)$p->amount,
                '_sort_tanggal'  => \Carbon\Carbon::parse($p->date)->timestamp,
            ])->values()->all();

        $columns = [
            ['key' => 'pembayar', 'label' => 'Pembayar', 'sortable' => true],
            ['key' => 'bank',     'label' => 'Bank',     'sortable' => true],
            ['key' => 'rekening', 'label' => 'Rekening', 'sortable' => false],
            ['key' => '_sort_nominal', 'label' => 'Nominal', 'sortable' => true, 'display' => 'nominal'],
            ['key' => 'status',   'label' => 'Status',   'sortable' => true, 'badge' => true],
            ['key' => 'keuangan', 'label' => 'Diverifikasi Oleh', 'sortable' => true],
            ['key' => '_sort_tanggal', 'label' => 'Tanggal', 'sortable' => true, 'display' => 'tanggal'],
        ];

        return [$rows, $columns, 'Detail Pembayaran'];
    }
}
