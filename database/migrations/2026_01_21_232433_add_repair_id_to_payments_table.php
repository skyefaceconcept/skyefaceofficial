<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('repair_id')->nullable()->constrained('repairs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('payments', 'repair_id')) {
            Schema::table('payments', function (Blueprint $table) {
                try {
                    $table->dropForeign(['repair_id']);
                } catch (\Exception $e) {
                    // ignore if foreign key does not exist
                }
                $table->dropColumn('repair_id');
            });
        }
    }
};
