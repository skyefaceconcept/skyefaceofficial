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
        Schema::table('orders', function (Blueprint $table) {
            // Add missing columns for checkout
            if (!Schema::hasColumn('orders', 'address')) {
                $table->string('address')->nullable()->after('customer_phone');
            }
            if (!Schema::hasColumn('orders', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('orders', 'state')) {
                $table->string('state')->nullable()->after('city');
            }
            if (!Schema::hasColumn('orders', 'zip')) {
                $table->string('zip')->nullable()->after('state');
            }
            if (!Schema::hasColumn('orders', 'country')) {
                $table->string('country')->nullable()->after('zip');
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('currency');
            }
            if (!Schema::hasColumn('orders', 'payment_processor')) {
                $table->string('payment_processor')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'cart_items')) {
                $table->json('cart_items')->nullable()->after('transaction_reference');
            }
            if (!Schema::hasColumn('orders', 'license_duration')) {
                $table->string('license_duration')->nullable()->after('amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = [
                'address',
                'city',
                'state',
                'zip',
                'country',
                'payment_method',
                'payment_processor',
                'cart_items',
                'license_duration',
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
