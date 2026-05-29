<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberProfile extends Model
{
    protected $fillable = [
        'member_id', 'expire_date', 'institution', 'department', 'address', 'status',
        'gender', 'blood_type', 'last_education'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
}
