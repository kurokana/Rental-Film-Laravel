<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_code',
        'user_id',
        'film_id',
        'promo_id',
        'rental_days',
        'rental_price',
        'subtotal',
        'discount',
        'total',
        'rental_date',
        'due_date',
        'return_date',
        'overdue_days',
        'overdue_fine',
        'status',
    ];

    protected $casts = [
        'rental_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'overdue_fine' => 'decimal:2',
        'rental_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Generate kode rental unik
    public static function generateRentalCode()
    {
        do {
            $code = 'RNT' . date('Ymd') . strtoupper(substr(uniqid(), -6));
        } while (self::where('rental_code', $code)->exists());

        return $code;
    }

    // Check apakah terlambat
    public function isOverdue()
    {
        if ($this->status === 'returned') {
            return false;
        }

        return Carbon::now()->gt($this->due_date);
    }

    // Hitung hari keterlambatan
    public function calculateOverdueDays()
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        return Carbon::now()->diffInDays($this->due_date);
    }

    // Hitung denda (misalnya 10% dari harga rental per hari)
    public function calculateOverdueFine()
    {
        $overdueDays = $this->calculateOverdueDays();
        if ($overdueDays <= 0) {
            return 0;
        }

        $finePerDay = $this->rental_price * 0.10; // 10% dari harga rental per hari
        return $finePerDay * $overdueDays;
    }

    // Update status overdue
    public function updateOverdueStatus()
    {
        if ($this->isOverdue() && $this->status === 'active') {
            $this->overdue_days = $this->calculateOverdueDays();
            $this->overdue_fine = $this->calculateOverdueFine();
            $this->status = 'overdue';
            $this->save();
        }
    }
}
