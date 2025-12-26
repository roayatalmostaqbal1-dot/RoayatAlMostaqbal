<?php

namespace App\Http\Controllers\Api\V1\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{AuditLog,User,Role , Contact};
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;


class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function statistics()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'statistics' => $this->getStatisticsOverview(),
                    'recent_activity' => $this->getRecentActivity(10),
                    'registration_trends' => $this->getRegistrationTrends(7),
                    'roles_distribution' => $this->getRolesDistribution(),
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

    protected function getStatisticsOverview(): array
    {
        $totalUsers = User::count();
        $totalRoles = Role::count();
        $totalPermissions = Permission::count();
        $contactMessages = Contact::count();



        $userGrowthPercentage = $this->calculateUserGrowth();

        return [
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
            [
                'title' => 'Contact Messages',
                'value' => $contactMessages,
                'icon' => 'messages',
                'trend' => '+0%',
                'trend_up' => true,
            ],

        ];
    }

    protected function calculateUserGrowth(): float
    {
        $now = Carbon::now();
        $sevenDaysAgo = $now->copy()->subDays(7);
        $fourteenDaysAgo = $now->copy()->subDays(14);

        $usersLastWeek = User::whereBetween('created_at', [$sevenDaysAgo, $now])->count();
        $usersPreviousWeek = User::whereBetween('created_at', [$fourteenDaysAgo, $sevenDaysAgo])->count();

        return $usersPreviousWeek > 0
            ? round((($usersLastWeek - $usersPreviousWeek) / $usersPreviousWeek) * 100, 2)
            : ($usersLastWeek > 0 ? 100 : 0);
    }

    protected function getRecentActivity(int $limit = 10): array
    {
        return AuditLog::with('user:id,name,email')
            ->orderBy('created_at', 'asc')
            ->limit($limit)
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
            })
            ->toArray();
    }

    /**
     * Get user registration trends for the last X days
     */
    protected function getRegistrationTrends(int $days = 7): array
    {
        $now = Carbon::now();
        $trends = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = $now->copy()->subDays($i);
            $count = User::whereDate('created_at', $date->toDateString())->count();
            $trends[] = [
                'date' => $date->format('M d'),
                'count' => $count,
            ];
        }

        return $trends;
    }

    /**
     * Get roles distribution
     */
    protected function getRolesDistribution(): array
    {
        return Role::withCount('users')
            ->get()
            ->map(function ($role) {
                return [
                    'name' => $role->name,
                    'users_count' => $role->users_count,
                ];
            })
            ->toArray();
    }
}
