<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'config.manage',
            'config.settings.manage',
            'config.roles.manage',
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
