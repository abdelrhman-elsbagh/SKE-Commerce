<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Forget cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage users',
            'create posts',
            'update posts',
            'delete posts',
            'publish posts',
            'create purchase-request',
            'update purchase-request',
            'delete purchase-request',
            'view user-wallets',
            'view business-client-wallets',
            'edit configs',
            'update configs',
            'edit terms',
            'update terms',
            'create category',
            'create diamond-rate',
            'create item',
            'create plan',
            'create payment-method',
            'create subscription',
            'create tag',
            'create fee-group',
            'create notification',
            'create currency',
            'export orders',
            'edit news',
            'update news',
            'view routing'
        ];

        // Create or update permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Create roles if they do not exist
        $editor = Role::firstOrCreate(['name' => 'editor']);
        $user = Role::firstOrCreate(['name' => 'user']);

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo([
            'view users',
            'view articles',
            'view orders',
            'view categories',
            'view posts',
        ]);

        $editorPermissions = [
            'create posts',
            'update posts',
            'delete posts',
            'publish posts',
            'create purchase-request',
            'update purchase-request',
            'delete purchase-request',
            'edit news',
            'update news'
        ];

        $editor->givePermissionTo($editorPermissions);
    }
}
