<?php

namespace App\Console\Commands;

use App\Models\ApiRoute;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class SyncApiRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:sync {--clear : Clear all existing routes before syncing}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Sync all named API routes to the api_routes table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('clear')) {
            ApiRoute::truncate();
            $this->info('Cleared all existing API routes.');
        }

        $routes = Route::getRoutes();
        $syncedCount = 0;
        $skippedCount = 0;

        foreach ($routes as $route) {
            // Only process API routes with names
            if (!$route->getName() || !str_starts_with($route->getPrefix(), 'api')) {
                continue;
            }

            $routeName = $route->getName();
            $methods = $route->methods;

            // Skip HEAD and OPTIONS methods
            $methods = array_filter($methods, fn($method) => !in_array($method, ['HEAD', 'OPTIONS']));

            if (empty($methods)) {
                continue;
            }

            foreach ($methods as $method) {
                $existingRoute = ApiRoute::where('route_name', $routeName)
                    ->where('http_method', strtoupper($method))
                    ->first();

                if ($existingRoute) {
                    $skippedCount++;
                    continue;
                }

                // Suggest permission name based on route name
                $suggestedPermission = $this->suggestPermissionName($routeName, $method);

                ApiRoute::create([
                    'route_name' => $routeName,
                    'route_path' => $route->uri,
                    'http_method' => strtoupper($method),
                    'description' => "Auto-synced route: {$routeName}",
                    'is_active' => true,
                ]);

                $this->line("✓ Synced: <fg=green>{$routeName}</> [{$method}] → Suggested permission: <fg=yellow>{$suggestedPermission}</>");
                $syncedCount++;
            }
        }

        $this->info("\n✓ Sync completed!");
        $this->info("  Synced: <fg=green>{$syncedCount}</> routes");
        $this->info("  Skipped: <fg=yellow>{$skippedCount}</> routes (already exist)");
        $this->info("\nNext steps:");
        $this->info("1. Review the suggested permissions above");
        $this->info("2. Create permissions in the admin dashboard");
        $this->info("3. Assign permissions to API routes");
        $this->info("4. Assign permissions to roles");
    }

    /**
     * Suggest a permission name based on route name and method
     */
    private function suggestedPermissionName($routeName, $method): string
    {
        // Extract resource name from route (e.g., 'api.users.index' -> 'users')
        $parts = explode('.', $routeName);
        $resource = $parts[1] ?? 'unknown';

        // Map HTTP methods to permission actions
        $actionMap = [
            'GET' => 'view',
            'POST' => 'create',
            'PUT' => 'edit',
            'PATCH' => 'edit',
            'DELETE' => 'delete',
        ];

        $action = $actionMap[strtoupper($method)] ?? 'manage';

        return "{$resource}.{$action}";
    }
}

