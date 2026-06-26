<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class MembershipPlan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
        'duration_unit',
        'is_lifetime',
        'features',
        'is_recommended',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'is_lifetime'    => 'boolean',
        'is_recommended' => 'boolean',
        'is_active'      => 'boolean',
        'features'       => 'array',
        'duration'       => 'integer',
        'sort_order'     => 'integer',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Hitung tanggal kedaluwarsa berdasarkan durasi & satuan.
     * Mengembalikan null bila paket seumur hidup (lifetime).
     */
    public function computeExpiry(?Carbon $from = null): ?Carbon
    {
        if ($this->is_lifetime) {
            return null;
        }

        $from = $from ?: now();

        return match ($this->duration_unit) {
            'day'   => $from->copy()->addDays($this->duration),
            'year'  => $from->copy()->addYears($this->duration),
            default => $from->copy()->addMonths($this->duration),
        };
    }

    /**
     * Label durasi yang ramah-baca, mis. "12 Bulan", "1 Tahun", "Seumur Hidup".
     */
    public function durationLabel(): string
    {
        if ($this->is_lifetime) {
            return 'Seumur Hidup';
        }

        $unitLabel = match ($this->duration_unit) {
            'day'   => 'Hari',
            'year'  => 'Tahun',
            default => 'Bulan',
        };

        return "{$this->duration} {$unitLabel}";
    }
}
