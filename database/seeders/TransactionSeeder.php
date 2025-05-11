<?php

namespace Database\Seeders;

use App\Models\Transaction;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

            $transactions = [
                [
                    'user_id' => 1,
                    'book_id' => 1,
                    'delivery_id' => 1,
                    'quantity' => 1,
                    'total_price' => 1400000,
                    'total_payment' => 1400000,
                    'payment_type' => 'cash',
                    'payment_status' => 'paid',
                ],
            ];

            foreach ($transactions as $transaction) {
                Transaction::factory()->create($transaction);
            }
        Transaction::factory(10)->create();
    }
}
