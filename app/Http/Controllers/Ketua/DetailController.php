<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Conversation;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Post;
use App\Models\User;
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
            'member'     => $this->memberData($premiumIds),
            'konten'     => $this->kontenData(),
            'blog'       => $this->blogData(),
            'pertanyaan' => $this->pertanyaanData(),
            'payment'    => $this->paymentData(),
            default      => abort(404),
        };

        return Inertia::render('Ketua/Detail', [
            'type'    => $type,
            'title'   => $title,
            'rows'    => $rows,
            'columns' => $columns,
        ]);
    }

    private function memberData(array $premiumIds): array
    {
        $rows = User::where('role', 'member')
            ->orderBy('created_at', 'desc')
            ->get(['id', 'name', 'email', 'telephone', 'is_active', 'created_at'])
            ->map(fn($u) => [
                'id'       => $u->id,
                'nama'     => $u->name,
                'email'    => $u->email,
                'telepon'  => $u->telephone ?? '-',
                'premium'  => in_array($u->id, $premiumIds) ? 'Premium' : 'Regular',
                'aktif'    => $u->is_active ? 'Aktif' : 'Nonaktif',
                'bergabung' => $u->created_at->format('d M Y'),
                '_sort_bergabung' => $u->created_at->timestamp,
            ])->values()->all();

        $columns = [
            ['key' => 'nama',     'label' => 'Nama',       'sortable' => true],
            ['key' => 'email',    'label' => 'Email',       'sortable' => true],
            ['key' => 'telepon',  'label' => 'Telepon',     'sortable' => false],
            ['key' => 'premium',  'label' => 'Membership',  'sortable' => true, 'badge' => true],
            ['key' => 'aktif',    'label' => 'Status',      'sortable' => true, 'badge' => true],
            ['key' => '_sort_bergabung', 'label' => 'Bergabung', 'sortable' => true, 'display' => 'bergabung'],
        ];

        return [$rows, $columns, 'Detail Member'];
    }

    private function kontenData(): array
    {
        $rows = Content::with('uploader:id,name')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($c) => [
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

    private function blogData(): array
    {
        $rows = Post::with(['author:id,name', 'category:id,name'])
            ->orderBy('published_at', 'desc')
            ->get()
            ->map(fn($p) => [
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

    private function pertanyaanData(): array
    {
        $rows = Conversation::with(['submitter:id,name', 'messages.sender:id,name,role'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($c) {
                // Find first staff/super_admin sender in replies
                $petugasName = $c->messages
                    ->first(fn($m) => in_array($m->sender?->role, ['staff', 'super_admin']))
                    ?->sender?->name;

                // Determine status based on closed state and responder presence
                if ($c->is_closed) {
                    $status = 'Selesai';
                } elseif ($petugasName) {
                    $status = 'Direspond';
                } else {
                    $status = 'Belum direspond';
                }

                return [
                    'id'      => $c->id,
                    'tiket'   => $c->ticket_number,
                    'penanya' => $c->submitter?->name ?? '-',
                    'petugas' => $petugasName ?? '-',
                    'status'  => $status,
                    'tanggal' => $c->created_at->format('d M Y'),
                    '_sort_tanggal' => $c->created_at->timestamp,
                ];
            })->values()->all();

        $columns = [
            ['key' => 'tiket',   'label' => 'No. Tiket', 'sortable' => true],
            ['key' => 'penanya', 'label' => 'Penanya',   'sortable' => true],
            ['key' => 'petugas', 'label' => 'Petugas',   'sortable' => true],
            ['key' => 'status',  'label' => 'Status',    'sortable' => true, 'badge' => true],
            ['key' => '_sort_tanggal', 'label' => 'Tanggal', 'sortable' => true, 'display' => 'tanggal'],
        ];

        return [$rows, $columns, 'Detail Pertanyaan'];
    }

    private function paymentData(): array
    {
        $rows = Payment::with(['payer:id,name', 'verifier:id,name'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($p) => [
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
