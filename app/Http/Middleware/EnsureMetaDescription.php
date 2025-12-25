<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMetaDescription
{
    /**
     * Handle an incoming request.
     * التأكد من وجود meta description في كل صفحة
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // تحقق من أن المحتوى HTML
        if ($response instanceof Response &&
            strpos($response->headers->get('content-type'), 'text/html') !== false) {

            $content = $response->getContent();

            // إذا لم توجد meta description، أضفها
            if (strpos($content, 'name="description"') === false) {
                $description = $this->getDefaultDescription($request);

                // أضف meta description بعد الـ viewport
                $newMeta = '<meta name="description" content="' . htmlspecialchars($description, ENT_QUOTES, 'UTF-8') . '">';
                $content = str_replace(
                    '<meta name="viewport"',
                    $newMeta . "\n    <meta name=\"viewport\"",
                    $content
                );

                $response->setContent($content);
            }
        }

        return $response;
    }

    /**
     * Get default description based on current page
     */
    private function getDefaultDescription(Request $request): string
    {
        $locale = app()->getLocale();
        $path = $request->path();

        $descriptions = config('seo.descriptions', []);

        // حاول الحصول على وصف محدد للصفحة
        if (strpos($path, 'about') !== false) {
            return $descriptions[$locale]['about'] ?? config('seo.default_description');
        } elseif (strpos($path, 'services') !== false) {
            return $descriptions[$locale]['services'] ?? config('seo.default_description');
        } elseif (strpos($path, 'projects') !== false) {
            return $descriptions[$locale]['projects'] ?? config('seo.default_description');
        } elseif (strpos($path, 'contact') !== false) {
            return $descriptions[$locale]['contact'] ?? config('seo.default_description');
        }

        return $descriptions[$locale]['home'] ?? config('seo.default_description');
    }
}
