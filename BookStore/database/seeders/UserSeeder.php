<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt(value: 'admin'),
                'phone_number' => '083876613377',
                'shipping_address' => 'Jln. Sunter Agung No. 19A',
                'role' => 'admin',
                'balance' => 0,
            ],
            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'password' => bcrypt('user'),
                'phone_number' => '083111462006',
                'shipping_address' => 'Jln. Bundaran Akbar No. 28',
                'role' => 'user',
                'balance' => 100000000,
            ],
            [
                'name' => 'Zaidan',
                'email' => 'zaiidan06@gmail.com',
                'password' => bcrypt('zaidan'),
                'phone_number' => '087788772419',
                'shipping_address' => 'Jln. Ancol Selatan No. 19B',
                'role' => 'user',
                'balance' => 1000000,
            ],
        ];

        foreach ($users as $user) {
            User::factory()->create($user);
        }
        User::factory(10)->create();
    }
}
