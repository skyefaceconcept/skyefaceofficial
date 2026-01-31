<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Payment;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fill any null transaction_ids with generated UUIDs
        Payment::whereNull('transaction_id')->each(function ($payment) {
            $payment->update(['transaction_id' => (string) Str::orderedUuid()]);
        });

        // Now modify the column to be NOT NULL
        Schema::table('payments', function (Blueprint $table) {
            $table->string('transaction_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->change();
        });
    }
};
