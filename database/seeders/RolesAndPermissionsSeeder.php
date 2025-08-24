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
            'config.view',
            'clients.view',
            'clients.manage',
            'clients.approve',
            'vessels.view',
            'vessels.create',
            'vessels.update',
            'vessels.delete',
            'vessels.assign_renter',
            'bookings.view',
            'bookings.create',
            'bookings.manage',
            'bookings.approve',
            'bookings.logs.create',
            'contracts.manage',
            'invoices.manage',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $roles = [
            'admin' => $permissions,
            'agent' => [
                'config.view',
                'clients.view',
                'vessels.view',
                'vessels.create', 
                'vessels.update',
                'vessels.assign_renter',
                'bookings.view',
                'bookings.create',
                'bookings.manage', 
                'bookings.logs.create',
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
