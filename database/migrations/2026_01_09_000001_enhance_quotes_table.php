<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Only add columns if table exists and columns missing
        if (!Schema::hasTable('quotes')) {
            return;
        }

        if (!Schema::hasColumn('quotes', 'ip_address')) {
            Schema::table('quotes', function (Blueprint $table) {
                $table->string('ip_address')->nullable()->after('notified');
            });
        }

        if (!Schema::hasColumn('quotes', 'admin_notes')) {
            Schema::table('quotes', function (Blueprint $table) {
                $table->text('admin_notes')->nullable()->after('ip_address');
            });
        }

        if (!Schema::hasColumn('quotes', 'quoted_price')) {
            Schema::table('quotes', function (Blueprint $table) {
                $table->decimal('quoted_price', 10, 2)->nullable()->after('admin_notes');
            });
        }

        if (!Schema::hasColumn('quotes', 'response')) {
            Schema::table('quotes', function (Blueprint $table) {
                $table->text('response')->nullable()->after('quoted_price');
            });
        }

        if (!Schema::hasColumn('quotes', 'responded_at')) {
            Schema::table('quotes', function (Blueprint $table) {
                $table->timestamp('responded_at')->nullable()->after('response');
            });
        }
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
