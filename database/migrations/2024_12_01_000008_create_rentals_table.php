<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->string('rental_code')->unique(); // kode unik rental
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('film_id')->constrained()->onDelete('cascade');
            $table->foreignId('promo_id')->nullable()->constrained()->onDelete('set null');
            
            $table->integer('rental_days'); // durasi sewa
            $table->decimal('rental_price', 10, 2); // harga per hari saat rental
            $table->decimal('subtotal', 10, 2); // rental_price * rental_days
            $table->decimal('discount', 10, 2)->default(0); // diskon dari promo
            $table->decimal('total', 10, 2); // subtotal - discount
            
            $table->date('rental_date'); // tanggal mulai sewa
            $table->date('due_date'); // tanggal jatuh tempo
            $table->date('return_date')->nullable(); // tanggal pengembalian aktual
            
            $table->integer('overdue_days')->default(0); // hari keterlambatan
            $table->decimal('overdue_fine', 10, 2)->default(0); // denda keterlambatan
            
            $table->enum('status', ['pending', 'active', 'extended', 'returned', 'overdue'])->default('pending');
            // pending: menunggu pembayaran, active: sedang disewa, extended: diperpanjang, returned: sudah dikembalikan, overdue: terlambat
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
