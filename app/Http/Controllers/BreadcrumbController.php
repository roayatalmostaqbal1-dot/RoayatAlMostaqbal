<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BreadcrumbController extends Controller
{
    /**
     * Generate Breadcrumbs for SEO
     * تحسين الملاحة وتحسين SEO من خلال Breadcrumbs
     */
    public function getBreadcrumbs(Request $request)
    {
        $locale = app()->getLocale();
        $path = $request->query('path', '/');

        $breadcrumbs = [
            [
                'name' => $locale === 'ar' ? 'الرئيسية' : 'Home',
                'url' => url("/$locale"),
                'current' => false
            ]
        ];

        $segments = array_filter(explode('/', trim($path, '/')));
        $currentUrl = "/$locale";

        foreach ($segments as $segment) {
            if ($segment === $locale) continue;

            $currentUrl .= "/$segment";

            $name = $this->getSegmentName($segment, $locale);
            $breadcrumbs[] = [
                'name' => $name,
                'url' => $currentUrl,
                'current' => end($segments) === $segment
            ];
        }

        return response()->json($breadcrumbs);
    }

    /**
     * Get localized segment name
     */
    private function getSegmentName($segment, $locale)
    {
        $names = [
            'ar' => [
                'about' => 'عن الشركة',
                'services' => 'الخدمات',
                'projects' => 'المشاريع',
                'contact' => 'اتصل بنا',
                'blog' => 'المدونة',
                'portfolio' => 'المحفظة',
            ],
            'en' => [
                'about' => 'About',
                'services' => 'Services',
                'projects' => 'Projects',
                'contact' => 'Contact',
                'blog' => 'Blog',
                'portfolio' => 'Portfolio',
            ]
        ];

        return $names[$locale][$segment] ?? ucfirst($segment);
    }

    /**
     * Generate Schema.org Breadcrumb
     */
    public function getBreadcrumbSchema($path)
    {
        $breadcrumbs = $this->generateBreadcrumbs($path);
        $itemListElement = [];

        foreach ($breadcrumbs as $index => $breadcrumb) {
            $itemListElement[] = [
                "@type" => "ListItem",
                "position" => $index + 1,
                "name" => $breadcrumb['name'],
                "item" => $breadcrumb['url']
            ];
        }

        return [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => $itemListElement
        ];
    }

    /**
     * Generate breadcrumbs array
     */
    private function generateBreadcrumbs($path)
    {
        $locale = app()->getLocale();

        $breadcrumbs = [
            [
                'name' => $locale === 'ar' ? 'الرئيسية' : 'Home',
                'url' => url("/$locale"),
            ]
        ];

        $segments = array_filter(explode('/', trim($path, '/')));
        $currentUrl = "/$locale";

        foreach ($segments as $segment) {
            if ($segment === $locale) continue;

            $currentUrl .= "/$segment";

            $name = $this->getSegmentName($segment, $locale);
            $breadcrumbs[] = [
                'name' => $name,
                'url' => $currentUrl,
            ];
        }

        return $breadcrumbs;
    }
}
