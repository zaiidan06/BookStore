<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Delivery>
 */
class DeliveryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone_number' => fake('id_ID')->phoneNumber,
            'shipping_address' => fake('id_ID')->address,
            'delivery_courier' => fake('id_ID')->randomElement(['JNE','J&T','SiCepat','AnterAja']),
            'shipping_option' => fake('id_ID')->randomElement(['standard','express','same_day']),
            'shipping_cost' => fake('id_ID')->randomElement([20000,50000,100000]),
            'receipt_code' => fake('id_ID')->uuid,
            'status_delivery' => fake('id_ID')->randomElement(['processing', 'delivered']),
        ];
    }
}
