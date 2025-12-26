<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RolePage;
use App\Enums\Api\PageEnum;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of all pages.
     */
    public function index()
    {
        $pages = PageEnum::toArray();

        return response()->json([
            'data' => $pages,
            'total' => \count($pages),
        ]);
    }

    /**
     * Get all pages with their role assignments.
     */
    public function getAllWithRoles()
    {
        $pages = PageEnum::toArray();
        $roles = Role::all();

        // Add roles to each page
        $pagesWithRoles = array_map(function ($page) use ($roles) {
            $assignedRoles = [];
            $pageEnum = PageEnum::tryFrom($page['key']);

            if ($pageEnum) {
                foreach ($roles as $role) {
                    if ($role->hasPage($pageEnum)) {
                        $assignedRoles[] = [
                            'id' => $role->id,
                            'name' => $role->name,
                        ];
                    }
                }
            }

            $page['roles'] = $assignedRoles;
            $page['roles_count'] = count($assignedRoles);
            return $page;
        }, $pages);

        return response()->json([
            'data' => $pagesWithRoles,
        ]);
    }

    /**
     * Assign pages to a role.
     */
    public function assignPagesToRole(Request $request, Role $role)
    {
        $validated = $request->validate([
            'page_keys' => 'required|array',
            'page_keys.*' => 'string|in:' . implode(',', PageEnum::keys()),
        ]);

        // Sync pages (this will replace existing assignments)
        $role->assignPages($validated['page_keys']);

        return response()->json([
            'message' => 'Pages assigned to role successfully',
            'data' => [
                'role_id' => $role->id,
                'role_name' => $role->name,
                'page_keys' => $validated['page_keys'],
            ],
        ]);
    }

    /**
     * Get pages assigned to a specific role.
     */
    public function getPagesForRole(Role $role)
    {
        $pageKeys = $role->getPageKeys();
        $pages = \array_filter(PageEnum::toArray(), fn($page) => \in_array($page['key'], $pageKeys));

        return response()->json([
            'data' => \array_values($pages),
            'role_id' => $role->id,
            'role_name' => $role->name,
        ]);
    }

    /**
     * Remove a page from a role using ORM.
     */
    public function removePageFromRole(Role $role, string $pageKey)
    {
        // Validate page key
        if (!\in_array($pageKey, PageEnum::keys())) {
            return response()->json([
                'message' => 'Invalid page key',
            ], 422);
        }

        // Remove the page from role using ORM
        RolePage::where('role_id', $role->id)
            ->where('page_key', $pageKey)
            ->delete();

        return response()->json([
            'message' => 'Page removed from role successfully',
        ]);
    }
}

