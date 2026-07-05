<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── super_admin: semua permission ────────────────────────────────────
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions(Permission::all());

        // ── admin: semua kecuali manage role & user ───────────────────────────
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(
            Permission::whereNotIn('name', [
                'create_role', 'update_role', 'delete_role', 'delete_any_role', 'view_role', 'view_any_role',
                'create_user', 'update_user', 'delete_user', 'delete_any_user',
            ])->get()
        );

        // ── validator: hanya bisa lihat tiket & order (read-only) ────────────
        $validator = Role::firstOrCreate(['name' => 'validator']);
        $validator->syncPermissions([
            'view_ticket',
            'view_any_ticket',
            'update_ticket',   // untuk check-in
            'view_order',
            'view_any_order',
            'widget_StatsOverview',
        ]);

        // ── customer: tidak punya akses ke panel ─────────────────────────────
        $customer = Role::firstOrCreate(['name' => 'customer']);
        $customer->syncPermissions([]);
    }
}
