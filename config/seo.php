<?php

/**
 * SEO Configuration
 * تكوين محسّنات SEO للموقع
 */

return [
    'default_description' => 'منصة رؤية المستقبل - استشارات وحلول برمجية متكاملة | Roayat Al Mostaqbal - Integrated consulting and software solutions',

    'descriptions' => [
        'ar' => [
            'home' => 'رؤية المستقبل - منصة متخصصة في الاستشارات التقنية والحلول البرمجية المتكاملة لتحويل عملك رقمياً.',
            'about' => 'تعرف على رؤية المستقبل، شركة متخصصة في تقديم استشارات تقنية وحلول برمجية مبتكرة منذ سنوات.',
            'services' => 'خدمات رؤية المستقبل تشمل استشارات تقنية، تطوير تطبيقات، حلول رقمية، وتحول تكنولوجي شامل.',
            'projects' => 'اطلع على مشاريع رؤية المستقبل الناجحة والحالات الدراسية لعملائنا من الشركات الكبرى.',
            'contact' => 'تواصل مع فريق رؤية المستقبل للاستفسار عن خدماتنا والحصول على استشارة مجانية.',
        ],
        'en' => [
            'home' => 'Roayat Al Mostaqbal - A specialized platform for technical consulting and integrated software solutions for digital transformation.',
            'about' => 'Learn about Roayat Al Mostaqbal, a company specializing in providing innovative technical consulting and software solutions.',
            'services' => 'Roayat Al Mostaqbal services include technical consulting, app development, digital solutions, and comprehensive technology transformation.',
            'projects' => 'Explore successful projects from Roayat Al Mostaqbal and case studies from our major clients.',
            'contact' => 'Contact Roayat Al Mostaqbal team to inquire about our services and get a free consultation.',
        ]
    ],

    'keywords' => [
        'ar' => [
            'general' => 'رؤية المستقبل, استشارات تقنية, حلول برمجية, تحول رقمي, تطوير التطبيقات, خدمات رقمية, استشارات تكنولوجيا',
            'services' => 'خدمات استشارة تقنية, تطوير تطبيقات ويب, تطوير تطبيقات الهاتف, حلول رقمية, تحويل رقمي, أتمتة الأعمال',
            'about' => 'عن رؤية المستقبل, الشركة, الفريق, الخبرة, الرؤية, الرسالة, تاريخ الشركة',
            'projects' => 'مشاريع ناجحة, حالات دراسية, محفظة الأعمال, أمثلة الأعمال, عينات من الأعمال',
            'contact' => 'اتصل بنا, تواصل معنا, استفسارات, رسالة, رقم الهاتف, البريد الإلكتروني, العنوان',
        ],
        'en' => [
            'general' => 'Roayat Al Mostaqbal, technical consulting, software solutions, digital transformation, app development, digital services',
            'services' => 'technical consulting services, web app development, mobile app development, digital solutions, digital transformation',
            'about' => 'about Roayat Al Mostaqbal, company, team, experience, vision, mission, company history',
            'projects' => 'successful projects, case studies, portfolio, work samples, project examples',
            'contact' => 'contact us, get in touch, inquiries, message, phone number, email, address',
        ]
    ],

    'og_images' => [
        'default' => 'RoayatAlMostaqbal.svg',
        'width' => 1200,
        'height' => 630,
    ],

    'social_media' => [
        'twitter' => '@RoayatAlMostaqbal',
        'linkedin' => 'roayat-al-mostaqbal',
        'facebook' => 'roayatalmostaqbal',
        'instagram' => 'roayatalmostaqbal',
    ],

    'organization' => [
        'name' => 'رؤية المستقبل - Roayat Al Mostaqbal',
        'description' => 'منصة متخصصة في الاستشارات التقنية والحلول البرمجية المتكاملة',
        'url' => env('APP_DOMIN', 'https://roayatalmostaqbal.net'),
        'logo' => 'RoayatAlMostaqbal.svg',
        'contact' => [
            'type' => 'Customer Support',
            'email' => 'info@roayatalmostaqbal.net',
            'languages' => ['ar', 'en'],
        ],
    ],

    'locale_mapping' => [
        'ar' => 'ar_AR',
        'en' => 'en_US',
    ],
];
