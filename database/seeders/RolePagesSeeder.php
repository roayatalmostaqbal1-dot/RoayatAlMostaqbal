<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Enums\Api\PageEnum;

class RolePagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Assign pages to roles based on their access level.
     */
    public function run(): void
    {
        // Get all roles
        $superAdminRole = Role::where('name', 'super admin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $editorRole = Role::where('name', 'editor')->first();
        $viewerRole = Role::where('name', 'viewer')->first();
        $userRole = Role::where('name', 'user')->first();

        // All available pages
        $allPages = PageEnum::keys();

        // Super Admin: All pages
        if ($superAdminRole) {
            $superAdminRole->assignPages($allPages);
            $this->command->info('✓ Super Admin: All pages assigned');
        }

        // Admin: All pages except encrypted-data, but include encrypted-data-recovery
        if ($adminRole) {
            $adminPages = array_filter($allPages, fn($page) => $page !== 'encrypted-data');
            $adminRole->assignPages($adminPages);
            $this->command->info('✓ Admin: All pages except encrypted-data assigned (includes recovery)');
        }

        // Editor: Dashboard, Contacts, OAuth2 Clients
        if ($editorRole) {
            $editorPages = ['dashboard', 'contacts', 'oauth2-clients'];
            $editorRole->assignPages($editorPages);
            $this->command->info('✓ Editor: Dashboard, Contacts, OAuth2 Clients assigned');
        }

        // Viewer: Dashboard, Contacts
        if ($viewerRole) {
            $viewerPages = ['dashboard', 'contacts'];
            $viewerRole->assignPages($viewerPages);
            $this->command->info('✓ Viewer: Dashboard, Contacts assigned');
        }

        // User: Dashboard, Contacts (regular users)
        if ($userRole) {
            $userPages = ['dashboard', 'contacts'];
            $userRole->assignPages($userPages);
            $this->command->info('✓ User: Dashboard, Contacts assigned');
        }

        $this->command->info('✓ Role pages seeded successfully!');
    }
}

