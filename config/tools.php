<?php

/*
|--------------------------------------------------------------------------
| Zonely free apps (Google Play)
|--------------------------------------------------------------------------
|
| Data source for the /apps hub and the /apps/{slug} detail pages.
| The "car-insurance-calculator-nyc" entry has internal_page => 'tools',
| meaning its public page is the existing /tools route (which keeps its
| ranking web calculator and gets the app promo appended). Requests to
| /apps/car-insurance-calculator-nyc 301-redirect to /tools.
|
| Icons and screenshots are served from Google Play's CDN for now; swap the
| URLs for self-hosted (R2) copies later without touching any view.
|
*/

return [

    'hub' => [
        'title'       => 'Free Apps by the Zonely Team — Calculators & Utilities',
        'description' => 'A growing collection of small, focused apps from the Zonely team. Simple calculators and utilities that each do one everyday job well. Get them free on Google Play.',
        'keywords'    => 'free android apps, free calculators, utility apps, Zonely apps, TSA PreCheck tracker, HSA calculator, car insurance calculator, move in move out inspection',
    ],

    // Google Play "Get it on Google Play" official badge (English).
    'play_badge' => 'https://play.google.com/intl/en_us/badges/static/images/badges/en_badge_web_generic.png',

    'apps' => [

        'car-insurance-calculator-nyc' => [
            'slug'             => 'car-insurance-calculator-nyc',
            'name'             => 'Car Insurance Calculator NYC',
            'package'          => 'com.abmn.carinsurance',
            'play_url'         => 'https://play.google.com/store/apps/details?id=com.abmn.carinsurance',
            'internal_page'    => 'tools',
            'category'         => 'Tools',
            'category_schema'  => 'FinanceApplication',
            'tagline'          => 'NYC TLC & auto insurance rate calculator, DMV points and PIRP discount tracker.',
            'keywords'         => 'NYC car insurance calculator, TLC insurance rates, DMV points tracker, PIRP discount, rideshare insurance NYC',
            'summary'          => 'Car Insurance Calculator NYC helps NYC rideshare, Uber, Lyft, yellow-cab and commercial drivers estimate insurance premiums, track TLC and DMV points against the real suspension windows, and see how much a certified 6-hour PIRP course could save. It works fully offline.',
            'icon'             => 'https://play-lh.googleusercontent.com/qdLV4EzdFa6krCKuTCYjiFOiyV3aVqwYkWbHCx5OoElwkKgQhFlUb3fYbABfYtN1ZM3JGoVkrW5nz46bnUr-=w240',
            'updated'          => 'Sep 6, 2026',
            'price'            => 'Free',
            'offline'          => true,
            'iap'              => false,
            'region'           => 'US',
            'screenshots'      => [
                'https://play-lh.googleusercontent.com/GEp559W9iWxaZ-Wp5XRm_L67mt7le7XjeLy5exMNj2NMTgCC8u2pCi061SnOo7CxIob7w1rhx3dN81p7Msje=w1000',
                'https://play-lh.googleusercontent.com/ZodxgF-ifp9fuxaLUAs0WSvX-2mwto1F_ARQu9YTszfofQAhhBOrsh21jyt4sifKwfL_5T3GJohFGOqeu70ARw=w1000',
                'https://play-lh.googleusercontent.com/UFoQ1dA7JXPi3fTAqSbsnxr_TgUpPJoJNap1Q07LQpYJIH6SsZ1Q_Iz5BQ2etUgiV-aqu2sZkm-EggKYlFxkog=w1000',
                'https://play-lh.googleusercontent.com/5_oI--ZxOaJ9LW4B2pMFuUO3izqs5-pU-5yHFD5vOYFHD9rkv79fWQUIe_AuA4TunZnwp2GF3zCS0T4HJdcrBg=w1000',
                'https://play-lh.googleusercontent.com/qG75S9lQRnAPC8i7eZwXHY9kIMcXTdJfpMRr8r-oMha9petDJOi--2Sve5jOxGTTDx4HWnRuupnWWO9DWVQKUA=w1000',
            ],
            'features'         => [
                ['title' => 'Instant NYC rate calculator', 'text' => 'Annual and monthly premium estimates based on vehicle type, age, licence duration and point history.'],
                ['title' => 'Compare NYC insurers',        'text' => 'See verified NYC TLC insurers such as American Transit, Hereford Insurance and Affirmative Direct, and get a real quote.'],
                ['title' => 'TLC & DMV points tracker',     'text' => 'Track active points against the real suspension windows — 15 months for TLC points, 24 months for DMV points.'],
                ['title' => 'PIRP discount estimator',      'text' => 'See how a certified 6-hour DMV PIRP course can remove up to 4 points and cut roughly 10% off your premium.'],
                ['title' => 'Works fully offline',          'text' => 'Get an instant estimate with no internet connection.'],
            ],
            'faqs'             => [
                ['q' => 'Is the app free?', 'a' => 'Yes. Every calculator and tracker in the app is free to use, with no account required.'],
                ['q' => 'Does it work offline?', 'a' => 'Yes. All calculations run on your device, so you can get an estimate with no internet connection.'],
                ['q' => 'Which insurers does it compare?', 'a' => 'It shows real, verified NYC TLC insurance providers such as American Transit, Hereford Insurance and Affirmative Direct, and links you to get an actual quote.'],
                ['q' => 'How does the points tracker work?', 'a' => 'You log your active points and the app counts them against the real suspension windows — 15 months for TLC points and 24 months for DMV points.'],
                ['q' => 'What is the PIRP discount estimator?', 'a' => 'It estimates how many points and how much premium you could save by completing a certified 6-hour DMV Point & Insurance Reduction Program course.'],
            ],
            'whats_new'        => 'Added real, verified NYC TLC insurers. Fixed insurance point accuracy. Bug fixes and improvements.',
        ],

        'tsa-precheck-tracker' => [
            'slug'             => 'tsa-precheck-tracker',
            'name'             => 'TSA PreCheck Tracker',
            'package'          => 'com.tensai.tsa_precheck_tracker',
            'play_url'         => 'https://play.google.com/store/apps/details?id=com.tensai.tsa_precheck_tracker',
            'internal_page'    => null,
            'category'         => 'Travel & Local',
            'category_schema'  => 'TravelApplication',
            'tagline'          => 'Track your TSA PreCheck expiration date and get reminded before it expires.',
            'keywords'         => 'TSA PreCheck expiration, TSA PreCheck renewal reminder, Trusted Traveler tracker, TSA renewal countdown',
            'summary'          => 'TSA PreCheck Tracker is a simple countdown app for your Trusted Traveler membership. Set your expiration date once, get an automatic reminder 30 days before renewal, and jump straight to the official TSA.gov renewal page when you are ready.',
            'icon'             => 'https://play-lh.googleusercontent.com/hCgu2Kx8rIOJdcEa16ltUa9BPkFAVIK53IPa0NOFEAsL3FRvz-9yBe2QbPVgSpODHdvbW0yjmFwZRJoqf-W0=w240',
            'updated'          => 'Aug 12, 2026',
            'price'            => 'Free',
            'offline'          => true,
            'iap'              => true,
            'region'           => 'US',
            'screenshots'      => [
                'https://play-lh.googleusercontent.com/B8z3tQPPkmD3EwhhK4e_MwJK-nhW0KmmxuIjL8h3WPfFdq6XGeiptabBdMgXAtN5dSua4imO0CKu93OhcXxF=w1000',
                'https://play-lh.googleusercontent.com/KteWd8lPzvc7O6NQFy5MBn9VTFCbCFsiOMzTIntr0flmOl7KbWZ4p6PE6hFfILN65jcn7AlrwA4wBa8pyUmNdw=w1000',
                'https://play-lh.googleusercontent.com/NKHjQh5Px-U0rbcipPcjIHU-SVsUXRO4lATUTjiUo7qD7m6F3_DHGV97urmkcANuxosBRCSTybN7gWWMzuDI=w1000',
                'https://play-lh.googleusercontent.com/vFKgFQpZNi1ZmgTPUVWDHNXDNIVMWEdgIXF8xdEL4Ud19c-0QKRUzT0cQ1tovK2KH8FaQ9ODvBVneqvM41fVaZc=w1000',
                'https://play-lh.googleusercontent.com/J28f4RqqzW3SvqgayYJmg4I-YqdcxgwDhZ6Yub1QfnM2v8qBCkj-Grz-oyoemjWUZBe9xOD_x28uHPitp27e1w=w1000',
            ],
            'features'         => [
                ['title' => 'Expiration countdown',      'text' => 'A clear countdown timer shows exactly how many days until your TSA PreCheck expires.'],
                ['title' => '30-day renewal reminder',   'text' => 'An automatic alert 30 days before your expiration date, so you never miss the deadline.'],
                ['title' => 'Status at a glance',        'text' => 'Instantly see whether your Trusted Traveler membership is Active or Expired.'],
                ['title' => 'One-tap renewal',           'text' => 'Jump straight to the official TSA.gov renewal page when you are ready.'],
                ['title' => 'Works fully offline',       'text' => 'Your date is stored on your device. Every reminder and calculation runs with no connection.'],
            ],
            'faqs'             => [
                ['q' => 'Is the app free?', 'a' => 'Yes, it is free to download and the core tracking features are free to use. A few optional extras are available as in-app purchases.'],
                ['q' => 'Does it work offline?', 'a' => 'Yes. Your expiration date is stored on your device, and every countdown and reminder works with no internet connection.'],
                ['q' => 'Will it remind me before my TSA PreCheck expires?', 'a' => 'Yes. You get an automatic reminder 30 days before your renewal date.'],
                ['q' => 'Does the app renew my membership for me?', 'a' => 'No. It tracks your date and links you straight to the official TSA.gov renewal page, where you complete the renewal yourself.'],
                ['q' => 'Is my data shared with anyone?', 'a' => 'No. The app collects no data and shares nothing with third parties.'],
            ],
            'whats_new'        => 'Fixed an issue where the "Renew Now" button did not respond when tapped. Improved reliability and removed unnecessary permissions for better privacy compliance.',
        ],

        'move-in-move-out-inspection' => [
            'slug'             => 'move-in-move-out-inspection',
            'name'             => 'Move In Move Out Inspection',
            'package'          => 'com.zamanvi.move_in_move_out_inspection',
            'play_url'         => 'https://play.google.com/store/apps/details?id=com.zamanvi.move_in_move_out_inspection',
            'internal_page'    => null,
            'category'         => 'Business',
            'category_schema'  => 'BusinessApplication',
            'tagline'          => 'Create move-in, move-out, HVAC and property condition PDF reports on-site. No portal login.',
            'keywords'         => 'move in move out inspection app, rental condition report, property inspection PDF, landlord tenant checklist, HVAC inspection report',
            'summary'          => 'Move In Move Out Inspection lets landlords, tenants, property managers, agents and inspectors document a property\'s condition on a phone or tablet and turn it into a client-ready PDF on the spot — no web portal, no account, no office computer.',
            'icon'             => 'https://play-lh.googleusercontent.com/_J9NcUlAk94-fzY4Og_gPMKafF3Zj_9kH05mcJ7_tqXXrf3y_0lc-1vm0v6--TmLazNL7e3emz-CuRWss27wcxA=w240',
            'updated'          => 'Jul 30, 2026',
            'price'            => 'Free',
            'offline'          => true,
            'iap'              => false,
            'region'           => null,
            'screenshots'      => [
                'https://play-lh.googleusercontent.com/ygbEf6jEv46BOl-40CmUgQN690tWzJ83lcfn2p-QtRbNCBJ6QANnmDbQqBLpNN5oii2roOIhugIjuFg8NgVPEA=w1000',
                'https://play-lh.googleusercontent.com/6Ocj6Wyctibi8oyOCu0zXXhC9r7HzFc58LhBrbX3LKkK0Ko71jdXeJuVE21En0ff8hsOX-mlkvGZSKjInYGmQw=w1000',
                'https://play-lh.googleusercontent.com/BXX82nxqX_dlRFVdoPhvAWVFrxsPmAQ4kt6gbtM3nhtLwAxHfVQ6FzrNdCcFLRfsHrNZegDCAHpac9SMLrtAvg=w1000',
                'https://play-lh.googleusercontent.com/L6yUaBGVycse16-QMRueq1xyCiIf9Capze6YU-uMwiqzEwRDLF2yLGumm5URDriJZQQyApohCzYnJeOWnjq1aRc=w1000',
                'https://play-lh.googleusercontent.com/XPoN5T4NDtRBl1hI-zd1-A3f2sA_MErVWR7DCuGleAXXvqWBt8Aoc-OagkrzhywZ0H0Uoo9fX1xLrMxEwbA6ww=w1000',
            ],
            'features'         => [
                ['title' => 'Move-in / move-out checklists', 'text' => 'Built-in templates for rental condition reports that help protect both landlord and tenant deposits.'],
                ['title' => 'Instant PDF export',           'text' => 'Create, edit and send inspection reports without ever leaving the property.'],
                ['title' => 'Photo annotations',            'text' => 'Snap photos during the walk-through and mark defects directly on the image.'],
                ['title' => 'Fully custom checklists',      'text' => 'Build your own, or use the included HVAC, roof and electrical safety templates.'],
                ['title' => 'Digital signatures',           'text' => 'Capture landlord and tenant sign-off right on the screen.'],
                ['title' => 'Offline mode',                 'text' => 'Works with no connection; all data is saved on your device.'],
                ['title' => 'Company branding',             'text' => 'Add your logo, contact details and terms to every report you generate.'],
            ],
            'faqs'             => [
                ['q' => 'Do I need an account?', 'a' => 'No. There is no portal sign-up and no login — install it, pick a checklist and start documenting.'],
                ['q' => 'Is it free?', 'a' => 'Yes. The core features are free, with no subscription required to generate a report.'],
                ['q' => 'Does it work offline?', 'a' => 'Yes. Everything works without a connection and your data is saved on your device.'],
                ['q' => 'What reports can it create?', 'a' => 'Move-in, move-out, HVAC maintenance, roof, electrical safety and fully custom property condition reports.'],
                ['q' => 'Can I add my company branding?', 'a' => 'Yes. Add your logo, contact details and terms so every PDF looks like your own.'],
            ],
            'whats_new'        => 'Initial release: create move-in, move-out, HVAC and property inspection reports with photos, signatures and instant PDF export. Fully offline, no login required.',
        ],

        'hsa-calculator' => [
            'slug'             => 'hsa-calculator',
            'name'             => 'HSA Calculator (Independent)',
            'package'          => 'com.tensai.hsa_calculator',
            'play_url'         => 'https://play.google.com/store/apps/details?id=com.tensai.hsa_calculator',
            'internal_page'    => null,
            'category'         => 'Finance',
            'category_schema'  => 'FinanceApplication',
            'tagline'          => 'See your 2026 HSA contribution limit and estimated tax savings instantly. 100% private.',
            'keywords'         => 'HSA calculator 2026, HSA contribution limit, HSA tax savings, health savings account calculator, HSA family limit',
            'summary'          => 'HSA Calculator is an independent tool for anyone with a Health Savings Account. Enter your income, coverage type and age to get your 2026 IRS contribution limit and an estimate of federal, state and FICA tax savings — no login, no account, every calculation on your device.',
            'icon'             => 'https://play-lh.googleusercontent.com/h6olcHOpXhlTZ0KRQJfi5WF4ObIEQvLAxH5KC7d1uxww8BXtMfyBk2GIFf1bvq3xiOOIIlCPHHIcJLiDgOyM=w240',
            'updated'          => 'Aug 20, 2026',
            'price'            => 'Free',
            'offline'          => true,
            'iap'              => false,
            'region'           => 'US',
            'screenshots'      => [
                'https://play-lh.googleusercontent.com/Evk9dlyV38Lcf-HyKL15zgUKUKEwz-cRYPtTW_DU9Yhb1BC059P8F5MUHcdXlIMCtHMhnEF5UBAX_3xAzSsRj8Q=w1000',
                'https://play-lh.googleusercontent.com/LNRW8GK4IlUg1gyhD1IJ8rehHaIGcSgR7StkXYJIb-N4yFGWGraGcIuDPQbmVvVc-6_Q-bltlHNVDB2D5aLO=w1000',
                'https://play-lh.googleusercontent.com/SNoddHYOvab0Q8vARhx_h0yWFdgxG-CH0K3Lge4fX8_YAUBvM3mHKKKK7N7sMDuXoIpS22QhOA5w28btxj-LxQ=w1000',
                'https://play-lh.googleusercontent.com/u1auaxAFi7r52RAV9g6PZFpXeyuGoOB9f3GWxqIBOt8WatWFSa6C7r3q5dBTh3-2GkxuuYtUHtPEBq7ZaiGNvBs=w1000',
                'https://play-lh.googleusercontent.com/ZJKSrZDvPXrXtaU2BgnLWs89InTquKpEqiw169tiurMo_0GjLBPlmWNbVtZdg4Pz7K2gEstEeISDbi8S9yzmfQ=w1000',
            ],
            'features'         => [
                ['title' => '2026 IRS contribution limits', 'text' => 'Self-only and family limits, including the 55+ catch-up contribution.'],
                ['title' => 'Instant tax-savings estimate', 'text' => 'Federal tax savings based on your income bracket, the moment you calculate.'],
                ['title' => 'Detailed breakdown',           'text' => 'Federal, state and FICA payroll-tax savings, broken out line by line.'],
                ['title' => 'Family plan comparison',        'text' => 'See self-only vs family limits and savings side by side before you decide.'],
                ['title' => 'Share your results',            'text' => 'Send a quick summary to anyone in one tap.'],
                ['title' => 'Works completely offline',      'text' => 'Every calculation happens on your device.'],
            ],
            'faqs'             => [
                ['q' => 'Is this an official IRS app?', 'a' => 'No. HSA Calculator is an independent, unofficial tool and is not affiliated with the IRS, any HSA provider, bank or insurer. It is for informational purposes only.'],
                ['q' => 'Is it free?', 'a' => 'Yes, it is free with no account or login.'],
                ['q' => 'Where do the limits come from?', 'a' => 'IRS Publication 969 and Revenue Procedure 2025-19 for the 2026 HSA contribution limits, with calculations based on 2026 tax brackets and FICA rates.'],
                ['q' => 'Does it store my income?', 'a' => 'Nothing leaves your phone. Your last-used inputs are saved locally so you do not have to retype them.'],
                ['q' => 'Is this tax advice?', 'a' => 'No. It is an estimate — consult a tax professional or certified financial advisor for advice specific to your situation.'],
            ],
            'whats_new'        => 'Updated app icon and listing title. See your 2026 HSA contribution limit and estimated tax savings instantly — self-only or family, with a detailed federal/state/FICA breakdown.',
        ],

    ],
];
