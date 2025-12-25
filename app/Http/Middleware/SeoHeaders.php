<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SeoHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // تحسينات الـ SEO عبر HTTP Headers

        // منع Cache للصفحات الديناميكية
        $response->header('Cache-Control', 'public, max-age=86400');

        // تحسين الأداء والأمان
        $response->header('X-Content-Type-Options', 'nosniff');
        $response->header('X-Frame-Options', 'SAMEORIGIN');
        $response->header('X-XSS-Protection', '1; mode=block');

        // تحسين التعرف على المحتوى
        $response->header('Content-Type', 'text/html; charset=utf-8');

        // السماح بـ Preload
        $response->header('Link', '<' . config('app.url') . '/fonts/Cairo.woff2>; rel=preload; as=font; crossorigin');

        return $response;
    }
}
