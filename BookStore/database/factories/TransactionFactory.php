<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
  /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $book = Book::inRandomOrder()->first();

        if (!$book) {
            return [];
        }

        $book_price = $book->book_price;
        $quantity = fake('id_ID')->numberBetween(1, 10);
        $total_price = $book_price * $quantity;

        $total_payment = fake('id_ID')->randomFloat(2, $total_price, $total_price + 1000000);

        $payment_status = $total_payment >= $total_price ? 'paid' : fake('id_ID')->randomElement(['pending', 'cancel']);

        return [
            'user_id' => fake('id_ID')->numberBetween(1, 10),
            'book_id' => $book->id,
            'delivery_id' => fake('id_ID')->numberBetween(1, 10),
            'quantity' => $quantity,
            'total_price' => $total_price,
            'total_payment' => $total_payment,
            'payment_type' => fake('id_ID')->randomElement(['cash', 'bank', 'ovo']),
            'payment_status' => $payment_status,
        ];
    }
}
