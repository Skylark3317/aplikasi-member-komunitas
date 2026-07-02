<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'telephone', 'role', 'is_active', 'delete_requested_at', 'email_verified_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $appends = ['avatar_url'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'delete_requested_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function ($user) {
            $user->memberProfile()->delete();
        });
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'payer_id');
    }

    public function verifiedPayments()
    {
        return $this->hasMany(Payment::class, 'verifier_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function memberProfile()
    {
        return $this->hasOne(MemberProfile::class, 'member_id');
    }

    public function isKetua(): bool
    {
        return $this->role === 'leader' || $this->role === 'ketua';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isPetugas(): bool
    {
        return $this->role === 'staff';
    }

    public function isKeuangan(): bool
    {
        return $this->role === 'finance';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    public function isPremium(): bool
    {
        if ($this->role !== 'member') {
            return false;
        }
        $profile = $this->memberProfile;
        return $profile && $profile->status === 'active' && now()->lt($profile->expire_date);
    }

    public function membershipStatus(): string
    {
        if ($this->role !== 'member') {
            return 'none';
        }

        $latestInvoice = $this->invoices()->latest()->first();

        if ($latestInvoice) {
            $payment = $latestInvoice->payment;
            
            // Prioritaskan status tagihan yang belum selesai
            if (!$payment) {
                return 'pending_invoice';
            }
            if ($payment->status === 'menunggu') {
                return 'pending_verification';
            }
        }

        $profile = $this->memberProfile;

        // Jika tidak ada tagihan yang tertunda, cek status profil
        if ($profile && $profile->status === 'active') {
            if (now()->lt($profile->expire_date)) {
                return 'active';
            }
            return 'expired';
        }

        // Jika profil tidak aktif, cek apakah tagihan terakhir ditolak
        if ($latestInvoice) {
            $payment = $latestInvoice->payment;
            if ($payment && $payment->status === 'ditolak') {
                return 'rejected';
            }
        }

        return 'none';
    }

    public function getAvatarUrlAttribute()
    {
        $extensions = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
        foreach ($extensions as $ext) {
            $path = 'avatars/user_' . $this->id . '.' . $ext;
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                return '/storage/' . $path;
            }
        }
        return null;
    }

    public function profileCompletionPercent(): int
    {
        $profile = $this->memberProfile;
        $fields = [
            'avatar' => !empty($this->avatar_url),
            'institution' => !empty($profile?->institution) && $profile->institution !== '-',
            'department' => !empty($profile?->department) && $profile->department !== '-',
            'telephone' => !empty($this->telephone) && $this->telephone !== '-',
            'address' => !empty($profile?->address) && $profile->address !== '-',
            'expertise' => !empty($profile?->expertise) && count((array)$profile->expertise) > 0,
            'expertise_proof' => !empty($profile?->expertise_proof) && count((array)$profile->expertise_proof) > 0,
        ];
        
        $filledCount = count(array_filter($fields));
        return (int) round(($filledCount / count($fields)) * 100);
    }
}
