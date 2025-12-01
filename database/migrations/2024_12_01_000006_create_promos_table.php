<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed']); // persentase atau nominal tetap
            $table->decimal('value', 10, 2); // nilai diskon
            $table->decimal('min_transaction', 10, 2)->default(0); // minimal transaksi
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('usage_limit')->nullable(); // batas penggunaan total
            $table->integer('usage_count')->default(0); // jumlah sudah digunakan
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
