<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml for Arabic and English pages';

    public function handle()
    {
        $baseUrl = config('app.domin'); // تأكد من APP_URL=https://roayatalmostaqbal.net

        $pages = [
            ''          => 'home',
            'about'     => 'about',
            'services'  => 'services',
            'projects'  => 'projects',
            'contact'   => 'contact',
        ];

        $sitemap = Sitemap::create();

        foreach ($pages as $path => $name) {
            $arUrl = $baseUrl . '/ar' . ($path ? "/$path" : '');
            $enUrl = $baseUrl . '/en' . ($path ? "/$path" : '');

            $sitemap->add(
                Url::create($arUrl)
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->addAlternate($enUrl, 'en')
            );

            $sitemap->add(
                Url::create($enUrl)
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->addAlternate($arUrl, 'ar')
            );
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('✅ sitemap.xml generated successfully');
    }
}
