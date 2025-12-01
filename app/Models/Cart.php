<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'film_id',
        'rental_days',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    // Hitung subtotal
    public function getSubtotalAttribute()
    {
        return $this->film->rental_price * $this->rental_days;
    }
}
