<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {

        $books = [
        [
            'book_categories_id' => 1,
            'book_name' => 'Tikungan Maut Malam Hari',
            'book_image' => 'Cover_tikungan_maut_1.jpg',
            'book_description' => 'Buku tentang anak kuliah yang bertemu dengan hantu di tikungan pada malam hari',
            'book_stock' => 40,
            'book_price' => 1400000,
        ],
        [
            'book_categories_id' => 2,
            'book_name' => 'Cartoon Jokes For Kids',
            'book_image' => 'buku_comedy.jpg',
            'book_description' => 'Kumpulan cerita-cerita lucu untuk anak kecil',
            'book_stock' => 25,
            'book_price' => 1300000,
        ],
        [
            'book_categories_id' => 3,
            'book_name' => 'Lover Under Umbrella',
            'book_image' => 'buku_romance.jpg',
            'book_description' => 'Kisah cinta dibawah payung dikala hujan',
            'book_stock' => 35,
            'book_price' => 1600000,
        ],
    ];

    foreach ($books as $book) {
        Book::factory()->create($book);
    }
    Book::factory(10)->create();
    }
}
