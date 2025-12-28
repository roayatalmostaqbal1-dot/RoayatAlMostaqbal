<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use App\Enums\Api\PageEnum;

class Role extends SpatieRole
{
    /**
     * Get all pages assigned to this role.
     */
    public function pages()
    {
        return $this->hasMany(RolePage::class);
    }

    /**
     * Get page keys for this role using ORM
     */
    public function getPageKeys(): array
    {
        return $this->pages()
            ->pluck('page_key')
            ->toArray();
    }

    /**
     * Check if role has access to a page
     */
    public function hasPage(PageEnum $page): bool
    {
        return $this->pages()
            ->where('page_key', $page->value)
            ->exists();
    }

    /**
     * Assign pages to role using ORM
     */
    public function assignPages(array $pageKeys): void
    {
        // Delete existing assignments
        $this->pages()->delete();

        // Create new assignments one by one to trigger UUID generation
        foreach ($pageKeys as $pageKey) {
            $this->pages()->create([
                'page_key' => $pageKey,
            ]);
        }
    }

    /**
     * Sync pages for this role (similar to sync method for relationships)
     */
    public function syncPages(array $pageKeys): void
    {
        $this->assignPages($pageKeys);
    }
}

