<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $seeders = [
            UserSeeder::class,
            BookCategorySeeder::class,
            BookSeeder::class,
            DeliverySeeder::class,
            TransactionSeeder::class,
        ];
        $this->call($seeders);
    }
}
