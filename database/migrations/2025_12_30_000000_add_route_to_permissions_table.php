<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('route')->nullable()->after('slug')->index();
        });

        // Backfill existing permissions: set route to a reasonable default
        // using the existing slug (prefixing with 'admin.'). This helps
        // preserve permission→route linkage for current records before
        // making the column non-nullable or relying on it elsewhere.
        try {
            DB::table('permissions')
                ->whereNull('route')
                ->update(['route' => DB::raw("CONCAT('admin.', slug)")]);
        } catch (\Exception $e) {
            // If update fails (e.g., during initial deploy when DB not present),
            // swallow so migration still adds the column. Logging can be added
            // here if you have a logger available.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('route');
        });
    }
};
