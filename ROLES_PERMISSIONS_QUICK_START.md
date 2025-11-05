# Roles and Permissions System - Quick Start Guide

## 🚀 5-Minute Setup

### Step 1: Run Migrations
```bash
php artisan migrate
```

### Step 2: Seed Default Data
```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

### Step 3: Register Middleware
Edit `bootstrap/app.php` and add to the middleware configuration:
```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->api(append: [
        \App\Http\Middleware\CheckApiPermission::class,
    ]);
})
```

### Step 4: Sync Routes
```bash
php artisan permissions:sync
```

### Step 5: Build
```bash
npm run build
```

## 📍 Access Points

| Page | URL | Purpose |
|------|-----|---------|
| Roles | `/admin/roles` | Manage roles and assign permissions |
| Permissions | `/admin/permissions` | Create and manage permissions |
| API Routes | `/admin/api-routes` | Link permissions to API endpoints |

## 🎯 Common Tasks

### Create a New Permission
1. Go to `/admin/permissions`
2. Click "+ Add Permission"
3. Enter name (e.g., `posts.view`)
4. Enter description
5. Select group (e.g., `posts`)
6. Click "Create"

### Assign Permission to Role
1. Go to `/admin/roles`
2. Click "Permissions" on the role
3. Check the permission checkbox
4. Click "Save Permissions"

### Link Permission to API Route
1. Go to `/admin/api-routes`
2. Click "Edit" on the route
3. Select the permission
4. Click "Update"

### Create New Role
1. Go to `/admin/roles`
2. Click "+ Add Role"
3. Enter role name (e.g., `moderator`)
4. Click "Create"
5. Click "Permissions" to assign permissions

### Sync New Routes
When you add new routes in `routes/api.php`:
```php
Route::get('/api/posts', [PostController::class, 'index'])->name('api.posts.index');
```

Then run:
```bash
php artisan permissions:sync
```

## 🔐 Default Roles

| Role | Permissions | Use Case |
|------|-------------|----------|
| super-admin | All | System administrators |
| admin | All except delete | Content administrators |
| editor | View + Edit | Content editors |
| viewer | View only | Read-only access |

## 💻 Code Examples

### Check Permission in Controller
```php
if (!auth()->user()->hasPermissionTo('users.view')) {
    abort(403, 'Unauthorized');
}
```

### Check Multiple Permissions
```php
// All permissions required
if (!auth()->user()->hasAllPermissions(['users.view', 'users.edit'])) {
    abort(403);
}

// Any permission required
if (!auth()->user()->hasAnyPermission(['users.view', 'users.edit'])) {
    abort(403);
}
```

### In Vue Component
```vue
<script setup>
import { useAuthStore } from '../../stores/auth';
const authStore = useAuthStore();
</script>

<template>
  <Button v-if="authStore.hasPermission('users.view')">
    View Users
  </Button>
</template>
```

## 📊 Permission Naming Convention

Use format: `resource.action`

Examples:
- `users.view` - View users
- `users.create` - Create users
- `users.edit` - Edit users
- `users.delete` - Delete users
- `posts.view` - View posts
- `posts.create` - Create posts

## 🔄 How It Works

```
1. User makes API request
   ↓
2. Middleware intercepts request
   ↓
3. Gets route name from request
   ↓
4. Looks up required permission in api_routes table
   ↓
5. Checks if user's role has permission
   ↓
6. Allow or deny request (403)
```

## 🛡️ Security

- Super-admin users bypass all permission checks
- All changes are logged in audit_logs table
- Cannot delete roles with assigned users
- Cannot delete permissions assigned to routes
- Route-permission mappings cached for performance

## 🐛 Troubleshooting

### Routes not appearing after sync
```bash
php artisan permissions:sync --clear
```

### Permission denied on valid route
1. Check user's role: `/admin/users`
2. Check role's permissions: `/admin/roles` → "Permissions"
3. Check route's permission: `/admin/api-routes`

### Clear cache
```bash
php artisan cache:clear
```

## 📚 Full Documentation

- **Setup Guide**: `ROLES_PERMISSIONS_SETUP.md`
- **Implementation Details**: `ROLES_PERMISSIONS_IMPLEMENTATION.md`
- **This Guide**: `ROLES_PERMISSIONS_QUICK_START.md`

## 🎯 Next Steps

1. ✅ Complete 5-minute setup above
2. ✅ Test by accessing `/admin/roles`
3. ✅ Create a custom role
4. ✅ Assign permissions to role
5. ✅ Create a test user with new role
6. ✅ Test API access with new role

## 📞 API Endpoints

### Roles
```
GET    /api/v1/SuperAdmin/roles
POST   /api/v1/SuperAdmin/roles
PUT    /api/v1/SuperAdmin/roles/{id}
DELETE /api/v1/SuperAdmin/roles/{id}
```

### Permissions
```
GET    /api/v1/SuperAdmin/permissions
POST   /api/v1/SuperAdmin/permissions
PUT    /api/v1/SuperAdmin/permissions/{id}
DELETE /api/v1/SuperAdmin/permissions/{id}
```

### Role Permissions
```
GET    /api/v1/SuperAdmin/roles/{id}/permissions
POST   /api/v1/SuperAdmin/roles/{id}/permissions
GET    /api/v1/SuperAdmin/role-permissions/all
```

### API Routes
```
GET    /api/v1/SuperAdmin/api-routes
POST   /api/v1/SuperAdmin/api-routes
PUT    /api/v1/SuperAdmin/api-routes/{id}
DELETE /api/v1/SuperAdmin/api-routes/{id}
```

### Sync
```
POST   /api/v1/SuperAdmin/sync-routes
```

## ✨ Features

✅ No code changes needed to add permissions
✅ Dynamic role and permission management
✅ Automatic API route detection
✅ Comprehensive audit logging
✅ Performance optimized with caching
✅ Responsive admin dashboard
✅ Integrated with existing CRUD system
✅ Super-admin role bypass
✅ Role and permission protection

---

**Ready to go!** 🚀

Start with the 5-minute setup above, then access `/admin/roles` to begin managing your permissions system.

