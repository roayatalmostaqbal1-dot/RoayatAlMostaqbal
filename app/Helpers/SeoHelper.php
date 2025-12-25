<?php

/**
 * SEO Configuration Helper
 * مساعد تكوين محسّنات SEO
 *
 * هذا الملف يحتوي على دوال مساعدة للـ SEO يمكن استخدامها في الـ Views
 */

// دالة مساعدة لإنشاء Meta Description
if (!function_exists('generateMetaDescription')) {
    function generateMetaDescription($text, $maxLength = 160)
    {
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', $text);

        if (mb_strlen($text) > $maxLength) {
            $text = mb_substr($text, 0, $maxLength);
            $text = mb_substr($text, 0, mb_strrpos($text, ' '));
            $text .= '...';
        }

        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

// دالة مساعدة لإنشاء Social Media Meta Tags
if (!function_exists('getSocialMediaMeta')) {
    function getSocialMediaMeta($title, $description, $image = null, $url = null)
    {
        return [
            'og:title' => $title,
            'og:description' => $description,
            'og:image' => $image ?? asset('RoayatAlMostaqbal.svg'),
            'og:url' => $url ?? url()->current(),
            'og:type' => 'website',
            'twitter:title' => $title,
            'twitter:description' => $description,
            'twitter:image' => $image ?? asset('RoayatAlMostaqbal.svg'),
            'twitter:card' => 'summary_large_image',
        ];
    }
}

// دالة مساعدة لإنشاء Structured Data
if (!function_exists('generateOrganizationSchema')) {
    function generateOrganizationSchema()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'رؤية المستقبل - Roayat Al Mostaqbal',
            'url' => config('app.url'),
            'logo' => asset('RoayatAlMostaqbal.svg'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'Customer Support',
                'availableLanguage' => ['ar', 'en'],
            ],
        ];
    }
}

// دالة مساعدة لإنشاء Breadcrumb Schema
if (!function_exists('generateBreadcrumbSchema')) {
    function generateBreadcrumbSchema($breadcrumbs)
    {
        $itemListElement = [];

        foreach ($breadcrumbs as $index => $breadcrumb) {
            $itemListElement[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['name'],
                'item' => $breadcrumb['url'] ?? '',
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemListElement,
        ];
    }
}

// دالة مساعدة لتحسين كلمات مفتاحية عربية
if (!function_exists('getArabicKeywords')) {
    function getArabicKeywords($pageType = 'general')
    {
        $keywords = [
            'general' => 'رؤية المستقبل, استشارات, تقنية, حلول برمجية, تحول رقمي, استشارات تقنية',
            'services' => 'خدمات استشارة تقنية, تطوير التطبيقات, حلول رقمية, تحول رقمي, استشارات تكنولوجيا',
            'about' => 'عن رؤية المستقبل, الشركة, الفريق, الخبرة, الرؤية, الرسالة',
            'projects' => 'مشاريع ناجحة, حالات دراسية, محفظة الأعمال, أمثلة الأعمال',
            'contact' => 'اتصل بنا, استفسارات, رسالة, تواصل معنا, رقم الهاتف, البريد الإلكتروني',
        ];

        return $keywords[$pageType] ?? $keywords['general'];
    }
}

// دالة مساعدة لإنشاء Canonical URL
if (!function_exists('getCanonicalUrl')) {
    function getCanonicalUrl()
    {
        return url()->current();
    }
}

// دالة مساعدة لإنشاء Language Alternate Links
if (!function_exists('getLanguageAlternates')) {
    function getLanguageAlternates()
    {
        $current = app()->getLocale();
        $alternates = [];

        foreach (['ar', 'en'] as $locale) {
            if ($locale !== $current) {
                $alternates[$locale] = route(Route::current()->getName(), [
                    'locale' => $locale,
                    ...Route::current()->parameters()
                ]);
            }
        }

        return $alternates;
    }
}

// دالة مساعدة لإنشاء OG Image
if (!function_exists('getOgImage')) {
    function getOgImage($image = null)
    {
        return $image ?? asset('RoayatAlMostaqbal.svg');
    }
}

// دالة مساعدة لإنشاء SEO-friendly Slug
if (!function_exists('createSlug')) {
    function createSlug($text)
    {
        // تحويل نصوص عربية
        $arabic = [
            'ا', 'أ', 'إ', 'آ', 'ب', 'ت', 'ث', 'ج', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز',
            'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ك', 'ل', 'م', 'ن',
            'ه', 'و', 'ي', 'ة', 'ى'
        ];

        $english = [
            'a', 'a', 'i', 'a', 'b', 't', 'th', 'j', 'h', 'kh', 'd', 'dh', 'r', 'z',
            's', 'sh', 's', 'd', 't', 'z', 'a', 'gh', 'f', 'q', 'k', 'l', 'm', 'n',
            'h', 'w', 'y', 'a', 'a'
        ];

        $text = str_replace($arabic, $english, $text);
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        $text = trim($text, '-');

        return $text;
    }
}

// دالة مساعدة لإنشاء Twitter Card
if (!function_exists('getTwitterCard')) {
    function getTwitterCard($title, $description, $image = null)
    {
        return [
            'twitter:card' => 'summary_large_image',
            'twitter:title' => $title,
            'twitter:description' => $description,
            'twitter:image' => $image ?? asset('RoayatAlMostaqbal.svg'),
            'twitter:creator' => '@RoayatAlMostaqbal',
        ];
    }
}

// دالة مساعدة لإنشاء Robots Meta Tag
if (!function_exists('getRobotsMeta')) {
    function getRobotsMeta($index = true, $follow = true, $snippet = true)
    {
        $parts = [];

        if ($index) $parts[] = 'index';
        else $parts[] = 'noindex';

        if ($follow) $parts[] = 'follow';
        else $parts[] = 'nofollow';

        if ($snippet) $parts[] = 'max-snippet:-1';

        $parts[] = 'max-image-preview:large';
        $parts[] = 'max-video-preview:-1';

        return implode(', ', $parts);
    }
}
