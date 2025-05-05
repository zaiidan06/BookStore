<?php

namespace Database\Seeders;

use App\Models\Delivery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deliveries = [
            [
                'phone_number' => '(+62) 83876613377',
                'shipping_address' => 'Jln. Sunter Agung No. 19A',
                'delivery_courier' => 'SiCepat',
                'shipping_option' => 'standard',
                'shipping_cost' => 20000,
                'status_delivery' => 'delivered',
            ],
        ];

        foreach ($deliveries as $delivery) {
            Delivery::factory()->create($delivery);
        }
        Delivery::factory(10)->create();
    }
}
