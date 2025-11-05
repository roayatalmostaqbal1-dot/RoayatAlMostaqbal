# Roles and Permissions System - Setup Guide

## Overview

This comprehensive Roles and Permissions system allows complete management of user access control from the admin dashboard without requiring code changes.

## Architecture

```
User → Role → Permissions ⊇ API Route → Required Permission
```

### Components

1. **Database Layer**
   - `roles` - User roles (Super Admin, Admin, Editor, Viewer)
   - `permissions` - System permissions (users.view, users.create, etc.)
   - `role_permission` - Pivot table linking roles to permissions
   - `api_routes` - API endpoints with required permissions
   - `audit_logs` - Track all permission changes

2. **Backend Layer**
   - Models: `Role`, `Permission`, `ApiRoute`, `AuditLog`
   - Middleware: `CheckApiPermission` - Validates permissions on each request
   - Controllers: `RoleController`, `PermissionController`, `ApiRouteController`, `RolePermissionController`
   - Command: `permissions:sync` - Auto-sync routes from code

3. **Frontend Layer**
   - Pages: `RolesPage`, `PermissionsPage`, `ApiRoutesPage`
   - Components: `PermissionsModal` - Manage role permissions
   - Integration with existing CRUD modal system

## Installation Steps

### Step 1: Run Migrations

```bash
php artisan migrate
```

This creates:
- `audit_logs` table
- `api_routes` table

### Step 2: Seed Default Data

```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

This creates:
- **Roles**: super-admin, admin, editor, viewer
- **Permissions**: 24 permissions across 6 groups (users, products, categories, roles, permissions, api_routes)
- **Permission Assignments**: Each role gets appropriate permissions

### Step 3: Sync API Routes

```bash
php artisan permissions:sync
```

This:
- Scans all named routes in `routes/api.php`
- Creates entries in `api_routes` table
- Suggests permission names based on route names

### Step 4: Register Middleware

Add to `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->api(append: [
        \App\Http\Middleware\CheckApiPermission::class,
    ]);
})
```

### Step 5: Build Frontend

```bash
npm run build
```

## Usage

### Admin Dashboard

Access the new admin pages:
- `/admin/roles` - Manage roles
- `/admin/permissions` - Manage permissions
- `/admin/api-routes` - Manage API routes

### Creating a New Permission

1. Go to `/admin/permissions`
2. Click "+ Add Permission"
3. Fill in:
   - **Name**: e.g., `posts.view` (format: `resource.action`)
   - **Description**: e.g., "View blog posts"
   - **Group**: e.g., "posts"
4. Click "Create"

### Assigning Permissions to Roles

1. Go to `/admin/roles`
2. Click "Permissions" button on a role
3. Check/uncheck permissions
4. Click "Save Permissions"

### Linking Permissions to API Routes

1. Go to `/admin/api-routes`
2. Click "Edit" on a route
3. Select the required permission
4. Click "Update"

### Adding New API Endpoints

When you add new routes in `routes/api.php`:

```php
Route::get('/api/posts', [PostController::class, 'index'])->name('api.posts.index');
```

Then run:

```bash
php artisan permissions:sync
```

The system will:
- Auto-detect the new route
- Suggest permission name: `posts.view`
- Create entry in `api_routes` table

## Permission Checking

### In Controllers

```php
// Check if user has permission
if (!auth()->user()->hasPermissionTo('users.view')) {
    abort(403, 'Unauthorized');
}

// Check multiple permissions
if (!auth()->user()->hasAllPermissions(['users.view', 'users.edit'])) {
    abort(403, 'Unauthorized');
}

// Check any permission
if (!auth()->user()->hasAnyPermission(['users.view', 'users.edit'])) {
    abort(403, 'Unauthorized');
}
```

### In Middleware

The `CheckApiPermission` middleware automatically:
1. Gets the current route name
2. Looks up required permission in `api_routes` table
3. Checks if user's role has that permission
4. Returns 403 if permission denied

### In Vue Components

```vue
<script setup>
import { useAuthStore } from '../../stores/auth';

const authStore = useAuthStore();

// Check permission
const canViewUsers = authStore.hasPermission('users.view');
</script>

<template>
  <Button v-if="canViewUsers">View Users</Button>
</template>
```

## Default Roles

### Super Admin
- **Permissions**: All permissions
- **Use Case**: System administrators with full access

### Admin
- **Permissions**: All except delete operations
- **Use Case**: Administrators who can manage content but not delete

### Editor
- **Permissions**: View and edit only
- **Use Case**: Content editors who can modify but not create/delete

### Viewer
- **Permissions**: View only
- **Use Case**: Read-only access for reporting/analytics

## Audit Logging

All changes to roles, permissions, and API routes are logged in `audit_logs` table:

```php
// View audit logs
$logs = AuditLog::where('model_type', 'Role')
    ->where('action', 'updated')
    ->recent(30) // Last 30 days
    ->get();

// Each log contains:
// - user_id: Who made the change
// - model_type: What was changed (Role, Permission, ApiRoute)
// - model_id: ID of the changed model
// - action: created, updated, deleted
// - old_values: Previous values (JSON)
// - new_values: New values (JSON)
// - ip_address: User's IP
// - user_agent: User's browser
// - created_at: When it happened
```

## Caching

The system uses caching for performance:

```php
// Route-permission mappings cached for 1 hour
ApiRoute::getPermissionForRoute('api.users.index');

// Clear cache when permissions change
ApiRoute::clearRouteCache('api.users.index');

// Clear all caches
ApiRoute::clearRouteCache();
```

## Security Considerations

1. **Super Admin Bypass**: Users with `super-admin` role bypass permission checks
2. **Role Protection**: Cannot delete roles with assigned users
3. **Permission Protection**: Cannot delete permissions assigned to routes
4. **Audit Trail**: All changes are logged with user info and IP address
5. **Caching**: Route-permission mappings cached to reduce database queries

## Troubleshooting

### Routes not syncing
```bash
php artisan permissions:sync --clear
```

### Permission denied errors
1. Check user's role: `/admin/users`
2. Check role's permissions: `/admin/roles` → "Permissions"
3. Check API route permission: `/admin/api-routes`

### Cache issues
```bash
php artisan cache:clear
```

## API Endpoints

### Roles
- `GET /api/v1/SuperAdmin/roles` - List roles
- `POST /api/v1/SuperAdmin/roles` - Create role
- `PUT /api/v1/SuperAdmin/roles/{id}` - Update role
- `DELETE /api/v1/SuperAdmin/roles/{id}` - Delete role

### Permissions
- `GET /api/v1/SuperAdmin/permissions` - List permissions
- `POST /api/v1/SuperAdmin/permissions` - Create permission
- `PUT /api/v1/SuperAdmin/permissions/{id}` - Update permission
- `DELETE /api/v1/SuperAdmin/permissions/{id}` - Delete permission

### Role Permissions
- `GET /api/v1/SuperAdmin/roles/{id}/permissions` - Get role's permissions
- `POST /api/v1/SuperAdmin/roles/{id}/permissions` - Assign permissions
- `GET /api/v1/SuperAdmin/role-permissions/all` - Get all permissions grouped

### API Routes
- `GET /api/v1/SuperAdmin/api-routes` - List routes
- `POST /api/v1/SuperAdmin/api-routes` - Create route
- `PUT /api/v1/SuperAdmin/api-routes/{id}` - Update route
- `DELETE /api/v1/SuperAdmin/api-routes/{id}` - Delete route

### Sync
- `POST /api/v1/SuperAdmin/sync-routes` - Sync routes from code

## Next Steps

1. ✅ Run migrations
2. ✅ Seed default data
3. ✅ Sync API routes
4. ✅ Register middleware
5. ✅ Build frontend
6. ✅ Test permission system
7. ✅ Create custom roles as needed
8. ✅ Assign users to roles

---

**Status**: Production Ready ✅
**Last Updated**: 2025-11-04

