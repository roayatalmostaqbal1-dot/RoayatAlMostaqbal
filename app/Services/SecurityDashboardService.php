<?php

namespace App\Services;

use App\Models\AIInsight;
use App\Models\SecurityLog;
use App\Models\User;
use App\Models\UserDashboardData;
use Carbon\Carbon;

class SecurityDashboardService
{
    /**
     * Get all dashboard data for a user
     */
    public function getUserDashboardData(string $userId): array
    {
        return [
            'identity' => $this->getIdentityData($userId),
            'security_logs' => $this->getSecurityLogs($userId, ['period' => 'today']),
            'ai_insight' => $this->getCurrentAIInsight($userId),
            'system_metrics' => $this->getSystemMetrics($userId),
        ];
    }

    /**
     * Get user identity data
     */
    public function getIdentityData(string $userId): array
    {
        $user = User::findOrFail($userId);
        $dashboardData = UserDashboardData::firstOrCreate(
            ['user_id' => $userId],
            [
                'digital_identity_status' => 'active',
                'nationality' => 'N/A',
                'security_level' => 'medium',
                'uae_pass_connected' => false,
            ]
        );

        // Fetch encrypted data if available
        $encryptedData = \App\Models\EncryptedUserData::forUser($userId)
            ->byType('profile')
            ->first();

        return [
            'user_name' => $user->name,
            'user_email' => $user->email,
            'identity_number' => $dashboardData->identity_number ?? 'N/A',
            'nationality' => $dashboardData->nationality ?? 'N/A',
            'digital_identity_status' => $dashboardData->digital_identity_status,
            'security_level' => $dashboardData->security_level,
            'uae_pass_connected' => $dashboardData->uae_pass_connected,
            'last_sync' => $dashboardData->formatted_last_sync ?? 'Never',
            'expiry_date' => '2030-12-31', // Mock data
            'encrypted_identity' => $encryptedData ? [
                'encrypted_dek' => $encryptedData->encrypted_dek,
                'dek_salt' => $encryptedData->dek_salt,
                'dek_nonce' => $encryptedData->dek_nonce,
                'ciphertext' => $encryptedData->profile_ciphertext,
                'nonce' => $encryptedData->profile_nonce,
            ] : null,
        ];
    }

    /**
     * Get security logs with optional filters
     */
    public function getSecurityLogs(string $userId, array $filters = []): array
    {
        $query = SecurityLog::where('user_id', $userId);

        // Apply period filter
        if (isset($filters['period'])) {
            $query = match ($filters['period']) {
                'today' => $query->today(),
                'week' => $query->lastWeek(),
                'month' => $query->lastMonth(),
                default => $query,
            };
        }

        // Apply severity filter
        if (isset($filters['severity'])) {
            $query->ofSeverity($filters['severity']);
        }

        // Apply event type filter
        if (isset($filters['event_type'])) {
            $query->ofEventType($filters['event_type']);
        }

        $perPage = $filters['per_page'] ?? 20;
        $logs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return [
            'logs' => $logs->items(),
            'total' => $logs->total(),
            'current_page' => $logs->currentPage(),
            'last_page' => $logs->lastPage(),
            'per_page' => $logs->perPage(),
        ];
    }

    /**
     * Get current AI insight for user
     */
    public function getCurrentAIInsight(string $userId): ?array
    {
        $insight = AIInsight::latestForUser($userId)->first();

        if (! $insight) {
            // Generate initial insight if none exists
            $insight = $this->generateAIInsight($userId);
        }

        return [
            'risk_level' => $insight->risk_level,
            'risk_score' => $insight->risk_score,
            'risk_percentage' => $insight->risk_percentage,
            'recommendation' => $insight->recommendation,
            'threat_indicators' => $insight->threat_indicators ?? [],
            'generated_at' => $insight->generated_at->diffForHumans(),
        ];
    }

    /**
     * Generate new AI insight
     */
    public function generateAIInsight(string $userId): AIInsight
    {
        // Get recent security logs to analyze
        $recentLogs = SecurityLog::where('user_id', $userId)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->get();

        // Calculate risk based on recent logs
        $criticalCount = $recentLogs->where('severity', 'critical')->count();
        $highCount = $recentLogs->where('severity', 'high')->count();
        $mediumCount = $recentLogs->where('severity', 'medium')->count();
        $lowCount = $recentLogs->where('severity', 'low')->count();

        // Calculate risk score
        $riskScore = min(100, ($criticalCount * 25) + ($highCount * 10) + ($mediumCount * 5) + ($lowCount * 1));

        // Determine risk level
        $riskLevel = match (true) {
            $riskScore >= 75 => 'critical',
            $riskScore >= 50 => 'high',
            $riskScore >= 25 => 'medium',
            default => 'low',
        };

        // Generate recommendation
        $recommendation = $this->generateRecommendation($riskLevel, $recentLogs);

        // Calculate threat indicators
        $threatIndicators = [
            'login_attempts' => $recentLogs->whereIn('event_type', ['login_success', 'login_failed'])->count(),
            'alerts' => $recentLogs->where('severity', 'medium')->count() + $recentLogs->where('severity', 'high')->count(),
            'access_attempts' => $recentLogs->where('event_type', 'unauthorized_access')->count(),
            'critical_threats' => $criticalCount,
        ];

        return AIInsight::create([
            'user_id' => $userId,
            'risk_level' => $riskLevel,
            'risk_score' => $riskScore,
            'recommendation' => $recommendation,
            'threat_indicators' => $threatIndicators,
            'generated_at' => now(),
        ]);
    }

    /**
     * Generate recommendation based on risk level
     */
    private function generateRecommendation(string $riskLevel, $logs): string
    {
        return match ($riskLevel) {
            'critical' => 'CRITICAL: Immediate action required! Multiple high-severity threats detected. Review access logs and enable additional security measures.',
            'high' => 'WARNING: Elevated security risk detected. Review recent security events and verify all access attempts.',
            'medium' => 'CAUTION: Moderate security activity detected. Consider reviewing access policies and user permissions.',
            'low' => 'System security appears normal. Continue monitoring and maintain current security protocols.',
            default => 'Unable to generate recommendation at this time.',
        };
    }

    /**
     * Get system metrics
     */
    public function getSystemMetrics(string $userId): array
    {
        $recentLogs = SecurityLog::where('user_id', $userId)
            ->where('created_at', '>=', Carbon::now()->subHour())
            ->count();

        return [
            'system_status' => 'stable',
            'response_time' => rand(50, 200).' ms',
            'protection_status' => 'active',
            'data_processed' => $this->formatBytes(rand(100000000, 2000000000)),
            'recent_activity' => $recentLogs,
        ];
    }

    /**
     * Generate mock security logs for testing
     */
    public function generateMockSecurityLogs(string $userId, int $count = 10): int
    {
        $eventTypes = [
            'login_success', 'access_attempt', 'system_scan',
            'data_access', 'api_request', 'file_download',
        ];
        $severities = ['low', 'low', 'low', 'medium', 'medium', 'high', 'critical'];
        $sources = [
            '192.168.1.10', '192.168.1.15', '10.0.0.45',
            'localhost', 'external_ip', '172.16.0.1',
        ];

        $created = 0;
        for ($i = 0; $i < $count; $i++) {
            SecurityLog::create([
                'user_id' => $userId,
                'event_type' => $eventTypes[array_rand($eventTypes)],
                'source' => $sources[array_rand($sources)],
                'severity' => $severities[array_rand($severities)],
                'description' => 'Auto-generated security event for testing',
                'metadata' => [
                    'user_agent' => 'Mozilla/5.0',
                    'timestamp' => now()->toIso8601String(),
                ],
                'created_at' => now()->subMinutes(rand(0, 1440)), // Random time within last 24 hours
            ]);
            $created++;
        }

        return $created;
    }

    /**
     * Export security logs as CSV
     */
    public function exportLogsAsCSV(string $userId, array $filters = []): string
    {
        $logs = SecurityLog::where('user_id', $userId);

        if (isset($filters['period'])) {
            $logs = match ($filters['period']) {
                'today' => $logs->today(),
                'week' => $logs->lastWeek(),
                'month' => $logs->lastMonth(),
                default => $logs,
            };
        }

        $logs = $logs->orderBy('created_at', 'desc')->get();

        $csv = "timestamp,event_type,source,severity,description\n";
        foreach ($logs as $log) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s\n",
                $log->formatted_timestamp,
                $log->event_type,
                $log->source,
                $log->severity,
                str_replace([',', "\n"], [';', ' '], $log->description ?? '')
            );
        }

        return $csv;
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2).' '.$units[$pow];
    }
}
