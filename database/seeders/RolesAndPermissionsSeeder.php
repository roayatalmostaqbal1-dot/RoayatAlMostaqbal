<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder creates all core permissions for the application.
     * Permissions are marked as "seeded" and cannot be edited or deleted via UI.
     *
     * API Route Mappings:
     * ==================
     *
     * USERS MANAGEMENT:
     * - users.view    → GET /api/v1/SuperAdmin/users (list users)
     * - users.view    → GET /api/v1/SuperAdmin/users/{id} (view single user)
     * - users.create  → POST /api/v1/SuperAdmin/users (create user)
     * - users.edit    → PUT /api/v1/SuperAdmin/users/{id} (update user)
     * - users.delete  → DELETE /api/v1/SuperAdmin/users/{id} (delete user)
     *
     * ROLES MANAGEMENT:
     * - roles.view    → GET /api/v1/SuperAdmin/roles (list roles)
     * - roles.view    → GET /api/v1/SuperAdmin/roles/{id} (view single role)
     * - roles.create  → POST /api/v1/SuperAdmin/roles (create role)
     * - roles.edit    → PUT /api/v1/SuperAdmin/roles/{id} (update role)
     * - roles.delete  → DELETE /api/v1/SuperAdmin/roles/{id} (delete role)
     *
     * PERMISSIONS MANAGEMENT:
     * - permissions.view    → GET /api/v1/SuperAdmin/permissions (list permissions)
     * - permissions.view    → GET /api/v1/SuperAdmin/permissions/{id} (view single permission)
     * - permissions.create  → POST /api/v1/SuperAdmin/permissions (create permission)
     * - permissions.edit    → PUT /api/v1/SuperAdmin/permissions/{id} (update permission)
     * - permissions.delete  → DELETE /api/v1/SuperAdmin/permissions/{id} (delete permission)
     *
     * ROLE-PERMISSION ASSIGNMENT:
     * - roles.edit    → GET /api/v1/SuperAdmin/roles/{role}/permissions (view role permissions)
     * - roles.edit    → POST /api/v1/SuperAdmin/roles/{role}/permissions (assign permissions to role)
     *
     * DASHBOARD:
     * - dashboard.view → GET /api/v1/SuperAdmin/dashboard/statistics (view dashboard stats)
     *
     * SETTINGS:
     * - settings.view → GET /api/v1/SuperAdmin/settings (view settings)
     * - settings.edit → PUT /api/v1/SuperAdmin/settings (update settings)
     *
     * TWO-FACTOR AUTHENTICATION:
     * - auth.2fa.enable  → POST /api/v1/SuperAdmin/two-factor/enable (enable 2FA)
     * - auth.2fa.disable → POST /api/v1/SuperAdmin/two-factor/disable (disable 2FA)
     * - auth.2fa.verify  → POST /api/v1/auth/two-factor/verify (verify 2FA code)
     *
     * ENCRYPTED DATA:
     * - encrypted_data.view   → GET /api/v1/auth/encrypted-data (view encrypted data)
     * - encrypted_data.view   → GET /api/v1/auth/admin/encrypted-data/{userId} (admin view user encrypted data)
     * - encrypted_data.create → POST /api/v1/auth/encrypted-data (create encrypted data)
     * - encrypted_data.edit   → PUT /api/v1/auth/encrypted-data/{id} (update encrypted data)
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Define permissions grouped by resource
        // All permissions are marked as seeded and cannot be edited/deleted via UI
        $permissions = [
            'users' => [
                ['name' => 'users.view', 'description' => 'View users list and details'],
                ['name' => 'users.create', 'description' => 'Create new users'],
                ['name' => 'users.edit', 'description' => 'Edit user information'],
                ['name' => 'users.delete', 'description' => 'Delete users'],
            ],
            'roles' => [
                ['name' => 'roles.view', 'description' => 'View roles list and details'],
                ['name' => 'roles.create', 'description' => 'Create new roles'],
                ['name' => 'roles.edit', 'description' => 'Edit roles and assign permissions'],
                ['name' => 'roles.delete', 'description' => 'Delete roles'],
            ],
            'permissions' => [
                ['name' => 'permissions.view', 'description' => 'View permissions list'],
                ['name' => 'permissions.create', 'description' => 'Create new permissions'],
                ['name' => 'permissions.edit', 'description' => 'Edit permissions'],
                ['name' => 'permissions.delete', 'description' => 'Delete permissions'],
            ],
            'dashboard' => [
                ['name' => 'dashboard.view', 'description' => 'View dashboard and statistics'],
            ],
            'settings' => [
                ['name' => 'settings.view', 'description' => 'View application settings'],
                ['name' => 'settings.edit', 'description' => 'Edit application settings'],
            ],
            'auth' => [
                ['name' => 'auth.2fa.enable', 'description' => 'Enable two-factor authentication'],
                ['name' => 'auth.2fa.disable', 'description' => 'Disable two-factor authentication'],
                ['name' => 'auth.2fa.verify', 'description' => 'Verify two-factor authentication code'],
            ],
            'encrypted_data' => [
                ['name' => 'encrypted_data.view', 'description' => 'View encrypted data'],
                ['name' => 'encrypted_data.create', 'description' => 'Create encrypted data'],
                ['name' => 'encrypted_data.edit', 'description' => 'Edit encrypted data'],
                ['name' => 'encrypted_data.recover', 'description' => 'Recover encrypted data (Admin only)'],
            ],
            'pages' => [
                ['name' => 'pages.view', 'description' => 'View pages list and details'],
                ['name' => 'pages.create', 'description' => 'Create new pages'],
                ['name' => 'pages.edit', 'description' => 'Edit pages'],
                ['name' => 'pages.delete', 'description' => 'Delete pages'],
            ],
            "oauth2_clients"=>[
                ['name' => 'oauth2_clients.view', 'description' => 'View OAuth2 clients list and details'],
                ['name' => 'oauth2_clients.create', 'description' => 'Create new OAuth2 clients'],
                ['name' => 'oauth2_clients.edit', 'description' => 'Edit OAuth2 clients'],
                ['name' => 'oauth2_clients.delete', 'description' => 'Delete OAuth2 clients'],
            ],
            "contacts"=>[
                ['name' => 'contacts.view', 'description' => 'View contacts list and details'],
                ['name' => 'contacts.create', 'description' => 'Create new contacts'],
                ['name' => 'contacts.edit', 'description' => 'Edit contacts'],
                ['name' => 'contacts.delete', 'description' => 'Delete contacts'],
            ],
            "telegram-chats"=>[
                ['name' => 'telegram-chats.manage', 'description' => 'Manage Telegram chats and respond to messages'],
            ],
        ];

        // Create permissions
        foreach ($permissions as $group => $groupPermissions) {
            foreach ($groupPermissions as $permission) {
                Permission::updateOrCreate(
                    ['name' => $permission['name']],
                    [
                        'description' => $permission['description'] ?? '',
                        'guard_name' => 'api',
                        'is_seeded' => true,  // Mark as seeded - cannot be edited/deleted via UI
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

        $userRole = Role::firstOrCreate(
            ['name' => 'user'],
            ['guard_name' => 'api']
        );

        // Assign all permissions to super-admin
        $superAdminRole->syncPermissions(Permission::all());

        // Assign permissions to admin (all except delete, but include recover)
        $adminPermissions = Permission::where(function($query) {
            $query->where('name', 'not like', '%.delete')
                  ->orWhere('name', 'encrypted_data.recover');
        })->get();
        $adminRole->syncPermissions($adminPermissions);

        // Assign permissions to editor (view and edit only)
        $editorPermissions = Permission::where('name', 'like', '%.view')
            ->orWhere('name', 'like', '%.edit')
            ->get();
        $editorRole->syncPermissions($editorPermissions);

        // Assign permissions to viewer (view only)
        $viewerPermissions = Permission::where('name', 'like', '%.view')->get();
        $viewerRole->syncPermissions($viewerPermissions);

        // Assign permissions to user (view only)
        $userPermissions = Permission::where('name', 'like', '%.view')->get();
        $userRole->syncPermissions($userPermissions);

        $this->command->info('✓ Roles and permissions seeded successfully!');
        $this->command->info('  - Super Admin: All permissions');
        $this->command->info('  - Admin: All except delete');
        $this->command->info('  - Editor: View and edit only');
        $this->command->info('  - Viewer: View only');
        $this->command->info('  - User: View only');
    }
}

