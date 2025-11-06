<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Define permissions grouped by resource
        $permissions = [
            'users' => [
                ['name' => 'users.view', 'description' => 'View users'],
                ['name' => 'users.create', 'description' => 'Create users'],
                ['name' => 'users.edit', 'description' => 'Edit users'],
                ['name' => 'users.delete', 'description' => 'Delete users'],
            ],
            'roles' => [
                ['name' => 'roles.view', 'description' => 'View roles'],
                ['name' => 'roles.create', 'description' => 'Create roles'],
                ['name' => 'roles.edit', 'description' => 'Edit roles'],
                ['name' => 'roles.delete', 'description' => 'Delete roles'],
            ],
            'permissions' => [
                ['name' => 'permissions.view', 'description' => 'View permissions'],
                ['name' => 'permissions.create', 'description' => 'Create permissions'],
                ['name' => 'permissions.edit', 'description' => 'Edit permissions'],
                ['name' => 'permissions.delete', 'description' => 'Delete permissions'],
            ],

        ];

        // Create permissions
        foreach ($permissions as $group => $groupPermissions) {
            foreach ($groupPermissions as $permission) {
                Permission::firstOrCreate(
                    ['name' => $permission['name']],
                    [
                        'description' => $permission['description'],
                        'group' => $group,
                        'guard_name' => 'api',
                    ]
                );
            }
        }

        // Create roles
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super-admin'],
            ['guard_name' => 'api']
        );

        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['guard_name' => 'api']
        );

        $editorRole = Role::firstOrCreate(
            ['name' => 'editor'],
            ['guard_name' => 'api']
        );

        $viewerRole = Role::firstOrCreate(
            ['name' => 'viewer'],
            ['guard_name' => 'api']
        );

        // Assign all permissions to super-admin
        $superAdminRole->syncPermissions(Permission::all());

        // Assign permissions to admin (all except delete)
        $adminPermissions = Permission::where('name', 'not like', '%.delete')->get();
        $adminRole->syncPermissions($adminPermissions);

        // Assign permissions to editor (view and edit only)
        $editorPermissions = Permission::where('name', 'like', '%.view')
            ->orWhere('name', 'like', '%.edit')
            ->get();
        $editorRole->syncPermissions($editorPermissions);

        // Assign permissions to viewer (view only)
        $viewerPermissions = Permission::where('name', 'like', '%.view')->get();
        $viewerRole->syncPermissions($viewerPermissions);

        $this->command->info('✓ Roles and permissions seeded successfully!');
        $this->command->info('  - Super Admin: All permissions');
        $this->command->info('  - Admin: All except delete');
        $this->command->info('  - Editor: View and edit only');
        $this->command->info('  - Viewer: View only');
    }
}

