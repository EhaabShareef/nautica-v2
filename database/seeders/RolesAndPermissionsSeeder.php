<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Seed initial roles and permissions into the database.
     *
     * Clears Spatie's permission cache, ensures a set of application permissions exist,
     * and creates/synchronizes roles with their assigned permissions (idempotent).
     *
     * @return void
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'manage properties',
            'manage resources',
            'manage slots',
            'manage bookings',
            'approve bookings',
            'manage contracts',
            'manage invoices',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $roles = [
            'admin' => $permissions,
            'agent' => ['manage bookings', 'approve bookings'],
            'client' => [],
        ];

        foreach ($roles as $role => $perms) {
            $r = Role::firstOrCreate(['name' => $role]);
            $r->syncPermissions($perms);
        }
    }
}
