<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'payer_id',
        'verifier_id',
        'payment_proof_url',
        'account_holder_name',
        'account_number',
        'account_bank_name',
        'amount',
        'date',
        'status',
        'reject_reason',
        'verified_at',
    ];

    protected $casts = [
        'date' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }
}
