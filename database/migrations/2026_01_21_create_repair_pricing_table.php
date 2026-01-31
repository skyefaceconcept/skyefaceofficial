<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repair_pricing', function (Blueprint $table) {
            $table->id();
            $table->string('device_type')->unique();
            $table->decimal('price', 8, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default pricing
        DB::table('repair_pricing')->insert([
            ['device_type' => 'Laptop', 'price' => 35.00, 'description' => 'Laptop diagnosis and repair', 'created_at' => now(), 'updated_at' => now()],
            ['device_type' => 'Desktop Computer', 'price' => 30.00, 'description' => 'Desktop computer diagnosis and repair', 'created_at' => now(), 'updated_at' => now()],
            ['device_type' => 'Mobile Phone', 'price' => 25.00, 'description' => 'Mobile phone diagnosis and repair', 'created_at' => now(), 'updated_at' => now()],
            ['device_type' => 'Tablet', 'price' => 28.00, 'description' => 'Tablet diagnosis and repair', 'created_at' => now(), 'updated_at' => now()],
            ['device_type' => 'Printer', 'price' => 40.00, 'description' => 'Printer diagnosis and repair', 'created_at' => now(), 'updated_at' => now()],
            ['device_type' => 'Other', 'price' => 50.00, 'description' => 'Other electronic device diagnosis and repair', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_pricing');
    }
};
