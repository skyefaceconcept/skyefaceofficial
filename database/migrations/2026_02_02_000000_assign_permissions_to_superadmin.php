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
        // Ensure SuperAdmin role exists
        $roleId = DB::table('roles')->where('slug', 'superadmin')->value('id');
        if (! $roleId) {
            $roleId = DB::table('roles')->insertGetId([
                'name' => 'SuperAdmin',
                'slug' => 'superadmin',
                'description' => 'System Super Administrator',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Gather all permission ids
        $permissionIds = DB::table('permissions')->pluck('id')->all();
        if (! empty($permissionIds)) {
            $now = now();
            $rows = [];
            foreach ($permissionIds as $pid) {
                $rows[] = [
                    'role_id' => $roleId,
                    'permission_id' => $pid,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Insert ignoring duplicates (unique constraint on role_id + permission_id)
            DB::table('role_permission')->insertOrIgnore($rows);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $roleId = DB::table('roles')->where('slug', 'superadmin')->value('id');
        if ($roleId) {
            DB::table('role_permission')->where('role_id', $roleId)->delete();
        }
    }
};
