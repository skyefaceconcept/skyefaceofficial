<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            // Add new columns for enhanced quote management
            $table->string('ip_address')->nullable()->after('notified');
            $table->text('admin_notes')->nullable()->after('ip_address');
            $table->decimal('quoted_price', 10, 2)->nullable()->after('admin_notes');
            $table->text('response')->nullable()->after('quoted_price');
            $table->timestamp('responded_at')->nullable()->after('response');
        });
    }

    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn([
                'ip_address',
                'admin_notes',
                'quoted_price',
                'response',
                'responded_at',
            ]);
        });
    }
};
