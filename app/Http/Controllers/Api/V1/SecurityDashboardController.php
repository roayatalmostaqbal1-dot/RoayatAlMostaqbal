<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\SecurityDashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SecurityDashboardController extends Controller
{
    protected SecurityDashboardService $dashboardService;

    public function __construct(SecurityDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Get all dashboard data for the authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $data = $this->dashboardService->getUserDashboardData($userId);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get digital identity data
     */
    public function identityData(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $data = $this->dashboardService->getIdentityData($userId);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch identity data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get security logs with optional filters
     */
    public function securityLogs(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $filters = [
                'period' => $request->input('period', 'today'),
                'severity' => $request->input('severity'),
                'event_type' => $request->input('event_type'),
                'per_page' => $request->input('per_page', 20),
            ];

            $data = $this->dashboardService->getSecurityLogs($userId, $filters);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch security logs',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get latest AI analysis
     */
    public function aiAnalysis(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $data = $this->dashboardService->getCurrentAIInsight($userId);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch AI analysis',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get system metrics
     */
    public function systemMetrics(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $data = $this->dashboardService->getSystemMetrics($userId);

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch system metrics',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate mock security logs for testing
     */
    public function generateLogs(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $count = $request->input('count', 10);

            $created = $this->dashboardService->generateMockSecurityLogs($userId, $count);

            return response()->json([
                'success' => true,
                'message' => "Generated {$created} security logs",
                'data' => ['created' => $created],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate logs',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate new AI insight
     */
    public function generateInsight(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $insight = $this->dashboardService->generateAIInsight($userId);

            return response()->json([
                'success' => true,
                'message' => 'AI insight generated successfully',
                'data' => [
                    'risk_level' => $insight->risk_level,
                    'risk_score' => $insight->risk_score,
                    'risk_percentage' => $insight->risk_percentage,
                    'recommendation' => $insight->recommendation,
                    'threat_indicators' => $insight->threat_indicators,
                    'generated_at' => $insight->generated_at->diffForHumans(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate AI insight',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export security logs as CSV
     */
    public function exportLogs(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $filters = [
                'period' => $request->input('period', 'today'),
            ];

            $csv = $this->dashboardService->exportLogsAsCSV($userId, $filters);

            return response()->json([
                'success' => true,
                'data' => [
                    'csv' => $csv,
                    'filename' => 'security_logs_' . date('Y-m-d_H-i-s') . '.csv',
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export logs',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
