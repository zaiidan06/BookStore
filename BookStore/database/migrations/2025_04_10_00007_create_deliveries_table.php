<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number')->nullable();
            $table->text('shipping_address');
            $table->enum('delivery_courier',['JNE','J&T','SiCepat','AnterAja'])->default('JNE');
            $table->enum('shipping_option',['standard','express','same_day'])->default('standard');
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->string('receipt_code')->nullable();
            $table->enum('status_delivery', ['processing', 'delivered'])->default('processing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
