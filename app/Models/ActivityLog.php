<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'target_type',
        'target_id',
        'target_label',
        'metadata',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record an activity for the currently authenticated super admin.
     */
    public static function record(
        string $action,
        ?string $targetType = null,
        ?int $targetId = null,
        ?string $targetLabel = null,
        ?array $metadata = null
    ): void {
        static::create([
            'user_id'      => auth()->id(),
            'action'       => $action,
            'target_type'  => $targetType,
            'target_id'    => $targetId,
            'target_label' => $targetLabel,
            'metadata'     => $metadata,
            'ip_address'   => request()->ip(),
        ]);
    }
}
