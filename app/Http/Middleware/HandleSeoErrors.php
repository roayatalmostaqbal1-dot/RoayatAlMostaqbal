<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class HandleSeoErrors
{
    /**
     * Handle an incoming request.
     * معالجة الأخطاء بطريقة آمنة للـ SEO
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $response = $next($request);
        } catch (Throwable $e) {
            // تسجيل الخطأ
            \Log::error('SEO Error Handler: ' . $e->getMessage(), [
                'url' => $request->url(),
                'method' => $request->method(),
                'exception' => $e,
            ]);

            // إذا كان طلب لـ sitemap أو robots.txt، أرجع صفحة خطأ متوافقة
            if ($request->is('sitemap*') || $request->is('robots.txt')) {
                return $this->createSafeResponse($request, $e);
            }

            throw $e;
        }

        // تأكد من وجود meta description
        if ($response instanceof Response &&
            strpos($response->headers->get('content-type', ''), 'text/html') !== false) {
            $content = $response->getContent();

            if (strpos($content, 'name="description"') === false &&
                strpos($content, '<head') !== false) {

                $description = $this->getDefaultDescription($request);
                $meta = '<meta name="description" content="' . htmlspecialchars($description, ENT_QUOTES, 'UTF-8') . '">' . "\n    ";

                $content = str_replace('<meta name="viewport"', $meta . '<meta name="viewport"', $content);
                $response->setContent($content);
            }
        }

        return $response;
    }

    /**
     * Create a safe response for sitemap/robots errors
     */
    private function createSafeResponse(Request $request, Throwable $e): Response
    {
        if ($request->is('robots.txt')) {
            return Response::make("User-agent: *\nAllow: /", 200, [
                'Content-Type' => 'text/plain; charset=UTF-8',
            ]);
        }

        if ($request->is('sitemap.xml')) {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
            $xml .= '    <url>' . "\n";
            $xml .= '        <loc>' . htmlspecialchars(url('/')) . '</loc>' . "\n";
            $xml .= '        <lastmod>' . now()->toAtomString() . '</lastmod>' . "\n";
            $xml .= '        <priority>1.0</priority>' . "\n";
            $xml .= '    </url>' . "\n";
            $xml .= '</urlset>';

            return Response::make($xml, 200, [
                'Content-Type' => 'application/xml; charset=UTF-8',
            ]);
        }

        return Response::make('Service unavailable', 503);
    }

    /**
     * Get default description based on current page
     */
    private function getDefaultDescription(Request $request): string
    {
        $locale = app()->getLocale();
        $path = $request->path();

        $descriptions = config('seo.descriptions', [
            'ar' => [
                'home' => 'رؤية المستقبل - منصة متخصصة في الاستشارات التقنية والحلول البرمجية المتكاملة',
                'about' => 'تعرف على رؤية المستقبل - شركة متخصصة في الاستشارات التقنية والحلول البرمجية',
                'services' => 'خدمات رؤية المستقبل تشمل استشارات تقنية وتطوير التطبيقات والحلول الرقمية',
                'projects' => 'اطلع على مشاريع رؤية المستقبل الناجحة والحالات الدراسية',
                'contact' => 'تواصل مع فريق رؤية المستقبل للحصول على استشارة مجانية',
            ],
            'en' => [
                'home' => 'Roayat Al Mostaqbal - Technical consulting and software solutions',
                'about' => 'Learn about Roayat Al Mostaqbal - Technical consulting and software development',
                'services' => 'Services including technical consulting, app development, and digital solutions',
                'projects' => 'Successful projects and case studies from Roayat Al Mostaqbal',
                'contact' => 'Contact Roayat Al Mostaqbal for a free consultation',
            ]
        ]);

        if (strpos($path, 'about') !== false) {
            return $descriptions[$locale]['about'] ?? 'رؤية المستقبل - Roayat Al Mostaqbal';
        } elseif (strpos($path, 'services') !== false) {
            return $descriptions[$locale]['services'] ?? 'رؤية المستقبل - Roayat Al Mostaqbal';
        } elseif (strpos($path, 'projects') !== false) {
            return $descriptions[$locale]['projects'] ?? 'رؤية المستقبل - Roayat Al Mostaqbal';
        } elseif (strpos($path, 'contact') !== false) {
            return $descriptions[$locale]['contact'] ?? 'رؤية المستقبل - Roayat Al Mostaqbal';
        }

        return $descriptions[$locale]['home'] ?? 'رؤية المستقبل - Roayat Al Mostaqbal';
    }
}
