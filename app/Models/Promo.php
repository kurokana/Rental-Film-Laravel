<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_transaction',
        'start_date',
        'end_date',
        'usage_limit',
        'usage_count',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_transaction' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    // Check apakah promo masih valid
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now()->toDateString();
        if ($now < $this->start_date || $now > $this->end_date) {
            return false;
        }

        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    // Hitung diskon
    public function calculateDiscount($subtotal)
    {
        if ($subtotal < $this->min_transaction) {
            return 0;
        }

        if ($this->type === 'percentage') {
            return ($subtotal * $this->value) / 100;
        }

        return $this->value;
    }
}
