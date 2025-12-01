<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action'); // create, update, delete, verify, etc.
            $table->string('model'); // nama model yang diubah
            $table->unsignedBigInteger('model_id')->nullable(); // id record yang diubah
            $table->text('description'); // deskripsi aksi
            $table->json('old_values')->nullable(); // nilai lama
            $table->json('new_values')->nullable(); // nilai baru
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
