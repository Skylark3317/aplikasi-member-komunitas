<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberProfile extends Model
{
    protected $fillable = [
        'member_id', 'plan_id', 'plan_snapshot', 'member_number', 'expire_date', 'institution', 'department', 'address', 'status',
        'gender', 'blood_type', 'last_education', 'expertise', 'expertise_proof'
    ];

    protected $casts = [
        'expertise' => 'array',
        'expertise_proof' => 'array',
        'plan_snapshot' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function ($profile) {
            if (empty($profile->member_number)) {
                $profile->member_number = self::generateMemberNumber();
            }
        });
    }

    public static function generateMemberNumber(?\DateTimeInterface $date = null): string
    {
        $date = $date ?: now();
        $prefix = $date->format('dmY');

        $lastMember = self::where('member_number', 'like', $prefix . '%')
            ->orderBy('member_number', 'desc')
            ->first();

        if ($lastMember) {
            $lastSeq = (int) substr($lastMember->member_number, -3);
            $nextSeq = $lastSeq + 1;
        } else {
            $nextSeq = 1;
        }

        return $prefix . $nextSeq;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function plan()
    {
        return $this->belongsTo(MembershipPlan::class, 'plan_id')->withTrashed();
    }

    /**
     * Check whether the member's active plan includes a given benefit string.
     */
    public function hasBenefit(string $benefit): bool
    {
        $features = [];
        if ($this->plan_snapshot && isset($this->plan_snapshot['features'])) {
            $features = $this->plan_snapshot['features'];
        } elseif ($this->plan) {
            $features = $this->plan->features ?? [];
        } else {
            return false;
        }
        return in_array($benefit, $features, true);
    }
}
