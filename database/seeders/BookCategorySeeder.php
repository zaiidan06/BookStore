<?php

namespace Database\Seeders;

use App\Models\BookCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Horror',
                'description' => 'Buku yang menceritakan tentang kisah hantu / hal menyeramkan',
            ],
            [
                'name' => 'Comedy',
                'description' => 'Buku yang menceritakan tentang kisah lucu',
            ],
            [
                'name' => 'Romance',
                'description' => 'Buku yang menceritakan tentang kisah cinta',
            ],
        ];

        foreach ($categories as $category) {
            BookCategory::create($category);
        }
    }
}
