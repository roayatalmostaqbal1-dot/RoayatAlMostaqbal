<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\EncryptionService;
use App\Services\SecurityDashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SecurityDashboardController extends Controller
{
    public function __construct(
        protected SecurityDashboardService $dashboardService,
        protected EncryptionService $encryptionService
    ) {}

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
                    'filename' => 'security_logs_'.date('Y-m-d_H-i-s').'.csv',
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

    /**
     * Get encrypted data in raw format (for demo purposes)
     *
     * Returns the data exactly as stored in the database - fully encrypted.
     * This endpoint demonstrates that sensitive data is stored encrypted.
     *
     * GET /api/v1/security-dashboard/encrypted-raw
     */
    public function encryptedRaw(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $dataType = $request->input('type', 'profile');

            $encryptedData = $this->encryptionService->getEncryptedDataRaw($userId, $dataType);

            if (! $encryptedData) {
                return response()->json([
                    'success' => false,
                    'message' => 'No encrypted data found for this user',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Encrypted data retrieved (stored in encrypted format)',
                'data' => $encryptedData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve encrypted data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all encrypted data for the user (for demo purposes)
     *
     * GET /api/v1/security-dashboard/encrypted-all
     */
    public function encryptedAll(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;

            $encryptedDataList = $this->encryptionService->getAllEncryptedDataRaw($userId);

            return response()->json([
                'success' => true,
                'message' => 'All encrypted data retrieved (stored in encrypted format)',
                'data' => $encryptedDataList,
                'count' => count($encryptedDataList),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve encrypted data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get encryption metadata for user's data
     *
     * GET /api/v1/security-dashboard/encryption-metadata
     */
    public function encryptionMetadata(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->id;
            $dataType = $request->input('type', 'profile');

            $metadata = $this->encryptionService->getEncryptionMetadata($userId, $dataType);

            if (! $metadata) {
                return response()->json([
                    'success' => false,
                    'message' => 'No encryption metadata found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $metadata,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve encryption metadata',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
