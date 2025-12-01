<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'genre_id',
        'synopsis',
        'director',
        'cast',
        'year',
        'duration',
        'poster',
        'rental_price',
        'stock',
        'average_rating',
        'total_reviews',
        'is_available',
    ];

    protected $casts = [
        'rental_price' => 'decimal:2',
        'average_rating' => 'decimal:1',
        'is_available' => 'boolean',
    ];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // Method untuk update rating
    public function updateRating()
    {
        $this->average_rating = $this->reviews()->avg('rating') ?? 0;
        $this->total_reviews = $this->reviews()->count();
        $this->save();
    }

    // Check ketersediaan stock
    public function isAvailable()
    {
        return $this->stock > 0 && $this->is_available;
    }
}
