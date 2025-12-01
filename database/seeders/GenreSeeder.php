<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $genres = [
            'Action',
            'Adventure',
            'Comedy',
            'Drama',
            'Horror',
            'Romance',
            'Sci-Fi',
            'Thriller',
            'Animation',
            'Documentary',
            'Fantasy',
            'Mystery',
            'Crime',
            'Biography',
            'Family',
        ];

        foreach ($genres as $genre) {
            Genre::create([
                'name' => $genre,
                'slug' => Str::slug($genre),
            ]);
        }
    }
}
