<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('genre_id')->constrained()->onDelete('cascade');
            $table->text('synopsis');
            $table->string('director');
            $table->string('cast');
            $table->integer('year');
            $table->integer('duration'); // dalam menit
            $table->string('poster')->nullable();
            $table->decimal('rental_price', 10, 2); // harga per hari
            $table->integer('stock');
            $table->decimal('average_rating', 2, 1)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
