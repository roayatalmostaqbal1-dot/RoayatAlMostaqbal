<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Security Headers Middleware for Government Compliance
 *
 * This middleware adds essential security headers to all responses
 * to meet UAE government security standards and best practices.
 */
class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Strict-Transport-Security (HSTS)
        // Forces HTTPS for 1 year, includes subdomains, allows preload
        $response->headers->set(
            'Strict-Transport-Security',
            'max-age=31536000; includeSubDomains; preload'
        );

        // Content-Security-Policy (CSP)
        // Restricts resource loading to prevent XSS attacks
        $csp = $this->buildContentSecurityPolicy();
        $response->headers->set('Content-Security-Policy', $csp);

        // X-Content-Type-Options
        // Prevents MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-Frame-Options
        // Prevents clickjacking attacks
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // X-XSS-Protection
        // Enables browser's XSS filter (legacy but still useful)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy
        // Controls referrer information sent with requests
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy (formerly Feature-Policy)
        // Restricts browser features
        $response->headers->set(
            'Permissions-Policy',
            'accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=()'
        );

        // Cache-Control for sensitive pages
        if ($this->isSensitivePage($request)) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, private, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        // Hide Server details if possible at app level (though server config is better)
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }

    /**
     * Build Content Security Policy header value.
     */
    protected function buildContentSecurityPolicy(): string
    {
        $directives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://192.168.100.12:5173 https://192.168.100.12:5173 http://192.168.100.12:5174 https://192.168.100.12:5174 http://127.0.0.1:5173 https://127.0.0.1:5173 http://127.0.0.1:5174 https://127.0.0.1:5174 https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://www.googletagmanager.com https://www.google-analytics.com https://maps.googleapis.com",
            "style-src 'self' 'unsafe-inline' http://192.168.100.12:5173 https://192.168.100.12:5173 http://192.168.100.12:5174 https://192.168.100.12:5174 http://127.0.0.1:5173 https://127.0.0.1:5173 http://127.0.0.1:5174 https://127.0.0.1:5174 https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://maps.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net https://cdnjs.cloudflare.com data:",
            "img-src 'self' data: https: blob: http://192.168.100.12:5173 https://192.168.100.12:5173 http://192.168.100.12:5174 https://192.168.100.12:5174 http://127.0.0.1:5173 https://127.0.0.1:5173 http://127.0.0.1:5174 https://127.0.0.1:5174 https://maps.googleapis.com https://maps.gstatic.com",
            "connect-src 'self' http://192.168.100.12:5173 https://192.168.100.12:5173 ws://192.168.100.12:5173 wss://192.168.100.12:5173 http://192.168.100.12:5174 https://192.168.100.12:5174 ws://192.168.100.12:5174 wss://192.168.100.12:5174 ws://192.168.100.12:6001 wss://192.168.100.12:6001 http://127.0.0.1:5173 https://127.0.0.1:5173 ws://127.0.0.1:5173 wss://127.0.0.1:5173 http://127.0.0.1:5174 https://127.0.0.1:5174 ws://127.0.0.1:5174 wss://127.0.0.1:5174 ws://127.0.0.1:6001 wss://127.0.0.1:6001 http://127.0.0.1:8000 http://192.168.100.12:8000 https://www.google-analytics.com https://region1.google-analytics.com https://maps.googleapis.com wss:",
            "frame-src 'self' https://www.google.com https://www.recaptcha.net",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
        ];

        if (! config('app.debug') || app()->environment('production')) {
            $directives[] = 'upgrade-insecure-requests';
        }

        return implode('; ', $directives);
    }

    /**
     * Check if the current request is for a sensitive page.
     */
    protected function isSensitivePage(Request $request): bool
    {
        // Check if user is authenticated or requesting sensitive paths
        if ($request->user() || $request->bearerToken()) {
            return true;
        }

        $sensitivePaths = [
            'admin',
            'login',
            'register',
            'password',
            'two-factor',
            'api/auth',
            'api/admin',
            'api/v1',
            'oauth',
        ];

        foreach ($sensitivePaths as $path) {
            if ($request->is($path) || $request->is($path.'/*')) {
                return true;
            }
        }

        return false;
    }
}
