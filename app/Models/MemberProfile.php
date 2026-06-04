<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberProfile extends Model
{
    protected $fillable = [
        'member_id', 'member_number', 'expire_date', 'institution', 'department', 'address', 'status',
        'gender', 'blood_type', 'last_education'
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

        return $prefix . str_pad($nextSeq, 3, '0', STR_PAD_LEFT);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
}
