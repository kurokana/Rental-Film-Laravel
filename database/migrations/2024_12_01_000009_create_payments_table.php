<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_code')->unique();
            $table->foreignId('rental_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->decimal('amount', 10, 2); // jumlah yang harus dibayar
            $table->enum('payment_type', ['rental', 'extension', 'fine']); // pembayaran sewa, perpanjangan, atau denda
            $table->enum('payment_method', ['online', 'manual']); // online (otomatis) atau manual (upload bukti)
            $table->string('proof_image')->nullable(); // bukti transfer untuk manual payment
            
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            // pending: belum diverifikasi, verified: sudah diverifikasi, rejected: ditolak
            
            $table->timestamp('paid_at')->nullable(); // waktu user melakukan pembayaran
            $table->timestamp('verified_at')->nullable(); // waktu pegawai verifikasi
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null'); // pegawai yang verifikasi
            $table->text('notes')->nullable(); // catatan verifikasi
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
