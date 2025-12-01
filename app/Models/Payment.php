<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_code',
        'rental_id',
        'user_id',
        'amount',
        'payment_type',
        'payment_method',
        'proof_image',
        'status',
        'paid_at',
        'verified_at',
        'verified_by',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Generate kode pembayaran unik
    public static function generatePaymentCode()
    {
        do {
            $code = 'PAY' . date('Ymd') . strtoupper(substr(uniqid(), -6));
        } while (self::where('payment_code', $code)->exists());

        return $code;
    }
}
