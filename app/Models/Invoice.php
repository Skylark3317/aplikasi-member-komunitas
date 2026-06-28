<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'number',
        'amount',
        'due_date',
        'is_accepted',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'is_accepted' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(MembershipPlan::class, 'plan_id')->withTrashed();
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
