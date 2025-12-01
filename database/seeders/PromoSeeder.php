<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        // Promo Persentase
        Promo::create([
            'code' => 'WELCOME10',
            'name' => 'Promo Selamat Datang',
            'description' => 'Diskon 10% untuk pengguna baru',
            'type' => 'percentage',
            'value' => 10,
            'min_transaction' => 50000,
            'start_date' => Carbon::now()->subDays(10),
            'end_date' => Carbon::now()->addMonths(3),
            'usage_limit' => null,
            'is_active' => true,
        ]);

        Promo::create([
            'code' => 'WEEKEND25',
            'name' => 'Promo Akhir Pekan',
            'description' => 'Diskon 25% untuk sewa di akhir pekan',
            'type' => 'percentage',
            'value' => 25,
            'min_transaction' => 100000,
            'start_date' => Carbon::now()->subDays(5),
            'end_date' => Carbon::now()->addMonths(2),
            'usage_limit' => 100,
            'is_active' => true,
        ]);

        // Promo Nominal Tetap
        Promo::create([
            'code' => 'FILM20K',
            'name' => 'Diskon 20 Ribu',
            'description' => 'Potongan harga Rp 20.000 untuk transaksi minimal Rp 150.000',
            'type' => 'fixed',
            'value' => 20000,
            'min_transaction' => 150000,
            'start_date' => Carbon::now()->subDays(7),
            'end_date' => Carbon::now()->addMonths(1),
            'usage_limit' => 50,
            'is_active' => true,
        ]);

        Promo::create([
            'code' => 'BIGDEAL50',
            'name' => 'Diskon Besar 50 Ribu',
            'description' => 'Potongan harga Rp 50.000 untuk transaksi minimal Rp 300.000',
            'type' => 'fixed',
            'value' => 50000,
            'min_transaction' => 300000,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addMonths(2),
            'usage_limit' => 30,
            'is_active' => true,
        ]);

        // Promo tidak aktif (untuk testing)
        Promo::create([
            'code' => 'EXPIRED',
            'name' => 'Promo Kadaluarsa',
            'description' => 'Promo yang sudah tidak berlaku',
            'type' => 'percentage',
            'value' => 50,
            'min_transaction' => 0,
            'start_date' => Carbon::now()->subMonths(3),
            'end_date' => Carbon::now()->subDays(1),
            'usage_limit' => null,
            'is_active' => false,
        ]);
    }
}
