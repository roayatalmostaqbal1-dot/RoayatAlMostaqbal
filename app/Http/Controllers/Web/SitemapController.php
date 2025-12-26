<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class SitemapController extends Controller
{
    /**
     * Generate XML Sitemap for SEO
     *
     * تحسين ظهور الموقع في محركات البحث من خلال خريطة موقع محسّنة
     */
    public function index()
    {
        $locale = request('locale', 'ar');

        // البيانات الأساسية للموقع
        $urls = [
            [
                'loc' => url("/{$locale}"),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => 1.0,
                'image' => asset('RoayatAlMostaqbal.svg')
            ],
            [
                'loc' => url("/{$locale}/about"),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => 0.9,
            ],
            [
                'loc' => url("/{$locale}/services"),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => 0.95,
            ],
            [
                'loc' => url("/{$locale}/projects"),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => 0.9,
            ],
            [
                'loc' => url("/{$locale}/contact"),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => 0.8,
            ],
        ];

        $xml = $this->generateSitemapXml($urls);

        return Response::make($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600'
        ]);
    }

    /**
     * Generate Sitemap Index for multiple locales
     */
    public function sitemapIndex()
    {
        $sitemaps = [
            url('/sitemap/ar'),
            url('/sitemap/en'),
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($sitemaps as $sitemap) {
            $xml .= "    <sitemap>\n";
            $xml .= "        <loc>{$sitemap}</loc>\n";
            $xml .= "        <lastmod>" . now()->toAtomString() . "</lastmod>\n";
            $xml .= "    </sitemap>\n";
        }

        $xml .= '</sitemapindex>';

        return Response::make($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600'
        ]);
    }

    /**
     * Generate XML from URLs array
     */
    private function generateSitemapXml($urls)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '         xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"' . "\n";
        $xml .= '         xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= "    <url>\n";
            $xml .= "        <loc>" . htmlspecialchars($url['loc']) . "</loc>\n";

            if (isset($url['image'])) {
                $xml .= "        <image:image>\n";
                $xml .= "            <image:loc>" . htmlspecialchars($url['image']) . "</image:loc>\n";
                $xml .= "            <image:title>رؤية المستقبل</image:title>\n";
                $xml .= "        </image:image>\n";
            }

            $xml .= "        <lastmod>" . $url['lastmod'] . "</lastmod>\n";
            $xml .= "        <changefreq>" . $url['changefreq'] . "</changefreq>\n";
            $xml .= "        <priority>" . $url['priority'] . "</priority>\n";
            $xml .= "    </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
