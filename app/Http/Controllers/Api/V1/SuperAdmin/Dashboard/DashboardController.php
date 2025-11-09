<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function statistics(Request $request)
    {
        try {
            // Get total counts
            $totalUsers = User::count();
            $totalRoles = Role::count();
            $totalPermissions = Permission::count();

            // Calculate user growth (last 7 days vs previous 7 days)
            $now = Carbon::now();
            $sevenDaysAgo = $now->copy()->subDays(7);
            $fourteenDaysAgo = $now->copy()->subDays(14);

            $usersLastWeek = User::whereBetween('created_at', [$sevenDaysAgo, $now])->count();
            $usersPreviousWeek = User::whereBetween('created_at', [$fourteenDaysAgo, $sevenDaysAgo])->count();

            $userGrowthPercentage = $usersPreviousWeek > 0
                ? round((($usersLastWeek - $usersPreviousWeek) / $usersPreviousWeek) * 100, 2)
                : ($usersLastWeek > 0 ? 100 : 0);

            // Get recent activity (last 10 actions)
            $recentActivity = AuditLog::with('user:id,name,email')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'user_name' => $log->user?->name ?? 'System',
                        'action' => $log->action,
                        'model_type' => $log->model_type,
                        'model_id' => $log->model_id,
                        'timestamp' => $log->created_at->diffForHumans(),
                        'created_at' => $log->created_at,
                    ];
                });

            // Get user registration trends (last 7 days)
            $registrationTrends = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = $now->copy()->subDays($i);
                $count = User::whereDate('created_at', $date->toDateString())->count();
                $registrationTrends[] = [
                    'date' => $date->format('M d'),
                    'count' => $count,
                ];
            }

            // Get roles distribution
            $rolesDistribution = Role::withCount('users')
                ->get()
                ->map(function ($role) {
                    return [
                        'name' => $role->name,
                        'users_count' => $role->users_count,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'statistics' => [
                        [
                            'title' => 'Total Users',
                            'value' => $totalUsers,
                            'icon' => 'users',
                            'trend' => $userGrowthPercentage > 0 ? "+{$userGrowthPercentage}%" : "{$userGrowthPercentage}%",
                            'trend_up' => $userGrowthPercentage >= 0,
                        ],
                        [
                            'title' => 'Total Roles',
                            'value' => $totalRoles,
                            'icon' => 'roles',
                            'trend' => '+0%',
                            'trend_up' => true,
                        ],
                        [
                            'title' => 'Total Permissions',
                            'value' => $totalPermissions,
                            'icon' => 'permissions',
                            'trend' => '+0%',
                            'trend_up' => true,
                        ],
                    ],
                    'recent_activity' => $recentActivity,
                    'registration_trends' => $registrationTrends,
                    'roles_distribution' => $rolesDistribution,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard statistics',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
