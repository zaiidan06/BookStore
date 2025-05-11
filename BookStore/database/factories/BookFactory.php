<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_categories_id' => fake('id_ID')->numberBetween(1,3),
            'book_name' => fake('id_ID')->word(),
            'book_image' => 'buku_comedy.jpg',
            'book_description' => fake('id_ID')->sentence(),
            'book_stock' => fake('id_ID')->numberBetween(0,1000),
            'book_price' => fake('id_ID')->randomFloat(2,100000,100000),
        ];
    }
}
