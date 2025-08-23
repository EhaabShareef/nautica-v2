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
            'clients.manage',
            'clients.approve',
            'vessels.view',
            'vessels.create',
            'vessels.update',
            'vessels.delete',
            'vessels.assign_renter',
            'bookings.manage',
            'bookings.approve',
            'contracts.manage',
            'invoices.manage',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $roles = [
            'admin' => $permissions,
            'agent' => [
                'vessels.view',
                'vessels.create', 
                'vessels.update',
                'vessels.assign_renter',
                'bookings.manage', 
                'contracts.manage', 
                'invoices.manage'
            ],
            'client' => [
                'vessels.view',
                'vessels.create',
                'vessels.update'
            ],
        ];

        foreach ($roles as $role => $perms) {
            $r = Role::firstOrCreate(['name' => $role]);
            $r->syncPermissions($perms);
        }
    }
}
