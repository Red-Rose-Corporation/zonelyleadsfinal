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
            'name'             => 'TLC Insurance NYC Calculator',
            'package'          => 'com.abmn.carinsurance',
            'play_url'         => 'https://play.google.com/store/apps/details?id=com.abmn.carinsurance',
            'internal_page'    => 'tools',
            'category'         => 'Tools',
            'category_schema'  => 'FinanceApplication',
            'tagline'          => 'TLC insurance NYC calculator: premium estimate, PIRP savings, TLC and DMV point tracker, shift income and congestion fee tools.',
            'keywords'         => 'TLC insurance NYC, TLC insurance calculator, PIRP calculator, TLC points tracker, NYC rideshare insurance, congestion fee calculator',
            'summary'          => 'TLC Insurance NYC Calculator is a free Android app built for NYC TLC drivers. It estimates your TLC insurance premium and your PIRP discount, then helps you run your whole shift: TLC and DMV points, income and expenses, the Manhattan congestion fee, renewals, maintenance, and your hours of service. It works in English, Spanish, Bengali and Haitian Creole, needs no account, and keeps your shift data on your phone.',
            'icon'             => 'https://play-lh.googleusercontent.com/qdLV4EzdFa6krCKuTCYjiFOiyV3aVqwYkWbHCx5OoElwkKgQhFlUb3fYbABfYtN1ZM3JGoVkrW5nz46bnUr-=w240',
            'updated'          => 'Sep 23, 2026',
            'price'            => 'Free',
            'offline'          => false,
            'iap'              => false,
            'region'           => 'US',
            'screenshots'      => [
                'https://play-lh.googleusercontent.com/loNYzMQsp14PyLK-w2_YQbjUMVYwt7SZRk20tEKLBfABDFZD8A1LtoZhM2NMaaI8CkRHX_qSupNd0ljbcWTERg=w1000',
                'https://play-lh.googleusercontent.com/6JImMNLvU-g4oekvihstBFh1_-SMhwTBux8n9UCeBW2nyVGb93SE2mVaONJ82k44tGqBXfv3Hi4LGzl5A50=w1000',
                'https://play-lh.googleusercontent.com/Fp0UUv4xy7-lD_8EpHt2mHeNQysgLBuzubVwRl7pMZhV6UTtvTmj-uRJLPU2B8F2gcHcMcP0r5pSvXlumKq9=w1000',
                'https://play-lh.googleusercontent.com/lwr7bAyhZFy6wmyvBCrywkTMfmPMEMZlfZuIM9jmPega6GdNhkPtTx9SCnQIkCpsJTFKAHZ_UP3OEKRwq_vC5Jg=w1000',
                'https://play-lh.googleusercontent.com/07THPk-CT1MYSaAA6PKpR6ECg_e5hQ2Aif8PsONFFBDxA7glQtCnncHUp_5qfFnQgSV5EMXUie25MOoUeWk9zQ=w1000',
            ],
            'features'         => [
                ['icon' => 'fa-calculator',        'title' => 'TLC insurance premium estimate',   'text' => 'Enter your TLC and DMV points, vehicle type and years licensed to get an instant monthly and yearly estimate. Save it as a PDF or share it.'],
                ['icon' => 'fa-percent',           'title' => 'PIRP savings calculator',          'text' => 'See how many points and how many dollars the 6-hour PIRP course could save you.'],
                ['icon' => 'fa-scale-balanced',    'title' => 'Real NYC TLC insurers',            'text' => 'See carriers that write NYC TLC insurance, such as American Transit, Hereford and Affirmative Direct, and request quotes.'],
                ['icon' => 'fa-gauge-high',        'title' => 'TLC and DMV points tracker',       'text' => 'Know how close you are to a suspension or revocation, using the 15-month and 24-month rolling windows.'],
                ['icon' => 'fa-wallet',            'title' => 'Shift income and expense tracker', 'text' => 'Log fares, tolls and gas in seconds and see your net earnings per shift.'],
                ['icon' => 'fa-city',              'title' => 'Congestion fee calculator',        'text' => 'Work out the Manhattan per-trip congestion surcharge, a cost unique to NYC drivers.'],
                ['icon' => 'fa-magnifying-glass',  'title' => 'Violation and fine lookup',        'text' => 'Look up the points and fine range for a violation before you even get the ticket.'],
                ['icon' => 'fa-calendar-check',    'title' => 'Renewal calendar',                 'text' => 'Get alerts before your license, registration and insurance expire.'],
                ['icon' => 'fa-wrench',            'title' => 'Maintenance reminders',            'text' => 'Track service by odometer miles or by days, so nothing gets skipped.'],
                ['icon' => 'fa-triangle-exclamation','title' => 'Post-accident checklist',        'text' => 'Quick-dial 911, 311 and the TLC Driver Protection Unit, with a step-by-step checklist.'],
                ['icon' => 'fa-clock',             'title' => 'Hours-of-service tracker',         'text' => 'Track the TLC 10-hour daily and 60-hour weekly limit across every platform you drive for.'],
                ['icon' => 'fa-folder-open',       'title' => 'Document vault and widget',        'text' => "Keep your TLC license and insurance card one tap away, and see today's points on your home screen."],
            ],
            'faqs'             => [
                ['q' => 'How much is TLC insurance in NYC?', 'a' => 'There is no single price. What TLC insurance costs in NYC depends on your TLC and DMV points, vehicle type, years licensed and the insurer. The calculator turns those inputs into a monthly and yearly estimate, but your actual premium is set by a licensed insurer.'],
                ['q' => 'How to get TLC insurance in NYC?', 'a' => 'Start with your details: your TLC and DMV points, vehicle type and years licensed. Estimate your premium, compare carriers that write NYC TLC coverage, then request quotes and confirm the policy meets the TLC insurance requirements before you buy. The app handles the estimate and shows the carriers; a licensed insurer or broker issues the policy.'],
                ['q' => 'What are the TLC insurance companies in NYC?', 'a' => 'The app lists carriers that write NYC TLC insurance, such as American Transit, Hereford Insurance and Affirmative Direct. Listing a carrier is not an endorsement, so compare quotes and coverage yourself. If you ask for quotes, the app sends only your points, vehicle type and years licensed to match you with insurers.'],
                ['q' => 'How can I get cheap TLC insurance in NYC?', 'a' => 'There is no trick, but three things move the price: fewer TLC and DMV points, a completed PIRP course (10% off the base premium for three years), and comparing quotes from several carriers. Use the calculator to see how your points change the estimate before you shop.'],
                ['q' => 'Do I need a TLC insurance broker in NYC?', 'a' => 'A broker compares several carriers for you, while buying direct means dealing with one insurer. This app is not a broker or insurer. It helps you estimate your premium and PIRP savings first, so you know what a fair quote looks like before you talk to anyone.'],
                ['q' => 'How many points suspend a TLC license?', 'a' => 'Under TLC rules, 6 to 9 points within a 15-month period can lead to suspension, and 10 or more points to revocation. The 15 months are counted from the date of conviction, not the date of the violation. Rules can change, so confirm the current thresholds with the TLC.'],
                ['q' => 'How long do TLC points stay on your record?', 'a' => "TLC points count for a rolling 15-month window. DMV points follow the DMV's own, longer window, which the app tracks as 24 months. The points tracker counts both against their windows so you can see when they drop off."],
                ['q' => 'What is PIRP?', 'a' => 'PIRP is the New York DMV Point and Insurance Reduction Program, a DMV-approved 6-hour defensive driving course. Completing it can lower the points counted toward a suspension and earn an auto insurance discount.'],
                ['q' => 'How much does PIRP save on insurance?', 'a' => 'New York insurers must give a 10% discount on the base premium of liability and collision coverage for three years after you complete PIRP, and the course can remove up to 4 points for violations in the last 18 months. It does not erase the violations, and the insurance discount requires about three years between courses. The calculator estimates your savings.'],
                ['q' => 'Does PIRP reduce points on a TLC license?', 'a' => 'PIRP reduces DMV points. The TLC has its own defensive driving requirement, and driver-resource sources describe a 3-point reduction on your TLC record for completing it. Check the current TLC rules before you rely on either reduction.'],
                ['q' => 'Is the TLC insurance calculator free?', 'a' => 'Yes. Every calculator and tracker in the app is free, with no account or login required.'],
                ['q' => 'Is my data private?', 'a' => 'Your shifts, income, points and documents stay on your phone, and there is no account. The only time anything leaves your device is when you ask for real insurance quotes.'],
                ['q' => 'What does the congestion fee calculator do?', 'a' => 'It works out the Manhattan per-trip congestion surcharge so you can see what a trip really earns you. Congestion rates and rules are set by New York authorities and can change, so check the current rates.'],
                ['q' => 'Is the app available in other languages?', 'a' => 'Yes. The whole app, not just the calculator, is available in English, Spanish, Bengali and Haitian Creole.'],
                ['q' => 'Is this app affiliated with the TLC or DMV?', 'a' => 'No. It is an independent tool and is not affiliated with, endorsed by, or sponsored by the NYC Taxi and Limousine Commission, the NY DMV or the MTA. Estimates are informational only.'],
            ],
            'whats_new'        => 'Improved dark mode colors, Android 13+ themed icon support, and general visual polish.',
        ],

        'tsa-precheck-tracker' => [
            'slug'             => 'tsa-precheck-tracker',
            'name'             => 'TSA PreCheck Renewal Tracker',
            'package'          => 'com.tensai.tsa_precheck_tracker',
            'play_url'         => 'https://play.google.com/store/apps/details?id=com.tensai.tsa_precheck_tracker',
            'internal_page'    => null,
            'category'         => 'Travel & Local',
            'category_schema'  => 'TravelApplication',
            'tagline'          => 'Never miss your TSA PreCheck renewal — expiration countdown + reminders.',
            'seo_title'        => 'How to Renew Your TSA PreCheck (App)?',
            'seo_description'  => 'How to renew your TSA PreCheck: this free app tracks your expiration date and reminds you in time, so you never miss the renewal window.',
            'keywords'         => 'TSA PreCheck renewal, TSA PreCheck expiration tracker, Global Entry renewal tracker, Trusted Traveler renewal reminder, TSA renewal countdown',
            'summary'          => 'Renewing your TSA PreCheck online only takes about five minutes — the hard part is remembering to do it before your membership expires. TSA PreCheck Renewal Tracker shows a clear countdown to your expiration date and reminds you in time. Enter your expiration date once and the app tracks your Trusted Traveler membership, counts down the days, and sends automatic reminders before you expire — so you keep your fast airport security lane with no gap.',
            'icon'             => 'https://play-lh.googleusercontent.com/hCgu2Kx8rIOJdcEa16ltUa9BPkFAVIK53IPa0NOFEAsL3FRvz-9yBe2QbPVgSpODHdvbW0yjmFwZRJoqf-W0=w240',
            'updated'          => 'Sep 14, 2026',
            'price'            => 'Free',
            'offline'          => true,
            'iap'              => true,
            'region'           => 'US',
            'screenshots'      => [
                'https://play-lh.googleusercontent.com/hOleHSxwEJ9HpmKyOKcZ1LVkOjiFj__YsF9ptureVTkWkZhXcJMe9cclZK3EwUn9rWLx4vIu90MlIl0kxqhzlQ=w1000',
                'https://play-lh.googleusercontent.com/p1d-AzA6jTlYIn9M0XEQ2htSfdTkcvtJwY2UWctwcWs7pC_xsFMAgEYsYcfyq2nm3wQJ2q8RLzHrbGbkP91C=w1000',
                'https://play-lh.googleusercontent.com/iUMJAdzpXZXkP6TFJtQ4G80orwQn7kRQlKzQJrHKMG9Pvd22qpZJ9RSQLTPSvKPh5jJPgxXmgNYPkDX79Uz3iw=w1000',
                'https://play-lh.googleusercontent.com/rR3peBMCQ3c5bhQgg6dHRe2SfGHVHi9qI56-HNnBaa6XLAwaTRlkthFFYs_L2RXNtL3UWLw6S_8hBMTjAgDb_A=w1000',
                'https://play-lh.googleusercontent.com/_beo01yNj6EyXnjuCZaD679xKtnX4po6ZAkfSxXe4Kc6EIkAJTKmYlXuI0USR5xYSWzuF8J2a_0g7QPB7yOTz8E=w1000',
                'https://play-lh.googleusercontent.com/Du8N4K9LLPHZoxLDE0YMQZ5gqqMp6x6jV7hnI4OyZNDfYOeuJzV4FrjzFdAs_-UcejzFKn2nsCSPfe6tSnaRFA=w1000',
            ],
            'features'         => [
                ['icon' => 'fa-hourglass-half',            'title' => 'Expiration countdown',      'text' => 'A clean ring and day counter show exactly how long until your membership expires.'],
                ['icon' => 'fa-gauge-high',                'title' => 'Status at a glance',        'text' => 'See instantly whether your Trusted Traveler membership is Active or Expired.'],
                ['icon' => 'fa-bell',                      'title' => 'Automatic renewal reminders', 'text' => 'Alerts at 60 days and 7 days before your expiration date, so you never miss the renewal window.'],
                ['icon' => 'fa-arrow-up-right-from-square','title' => 'One-tap renewal link',      'text' => 'Go straight to the official TSA.gov renewal page, no searching.'],
                ['icon' => 'fa-moon',                      'title' => 'Dark mode',                 'text' => "Matches your phone's theme automatically."],
                ['icon' => 'fa-cloud-arrow-up',            'title' => 'Automatic backup',          'text' => 'Your data survives a phone upgrade — no account needed.'],
                ['icon' => 'fa-wifi',                      'title' => '100% offline',               'text' => 'Your data stays on your device; no account, no login, no internet needed.'],
            ],
            'faqs'             => [
                ['q' => 'Where do I find my expiration date and Known Traveler Number (KTN)?', 'a' => 'Log in to the official Trusted Traveler Program (TTP) website. Your KTN and TSA PreCheck expiration date are shown on your dashboard. Enter that date in the app.'],
                ['q' => 'Does this work for Global Entry?', 'a' => 'Yes. Global Entry includes TSA PreCheck and also runs on a 5-year cycle. Enter whichever expiration date applies to you and the app will track it.'],
                ['q' => 'When should I renew my TSA PreCheck?', 'a' => 'You can renew online up to 6 months before your expiration date. TSA recommends renewing at least 60 days ahead to avoid a lapse in benefits. Renewing early costs you nothing — your new 5-year term starts when the current one ends.'],
                ['q' => 'How do you renew your TSA PreCheck?', 'a' => 'Renew online at TSA.gov. This app reminds you before your expiration date so you do not miss the renewal window; it does not process the renewal itself.'],
                ['q' => 'Is my data private?', 'a' => 'Yes. There is no account and no login. Your expiration date is stored locally on your device only — never uploaded, never shared.'],
                ['q' => 'Can you renew your TSA PreCheck online?', 'a' => 'Yes — most members can renew entirely online in about 5 minutes, starting up to 6 months before expiration. The one common exception is a legal name change, which requires calling the TSA Help Center first.'],
                ['q' => 'Can you renew your TSA PreCheck after it expires?', 'a' => "Yes, in most cases. You don't have to start over as a new applicant — renewing within 6 months of your expiration date can usually still be done online. Wait longer than that and you may face extra requirements, so it's worth renewing before you lapse."],
                ['q' => 'How long does it take to renew your TSA PreCheck?', 'a' => 'The online form itself takes about 5 minutes. Approval typically comes back in 3–5 business days, though TSA says it can occasionally take up to 60 days — which is exactly why this app reminds you 60 days ahead.'],
                ['q' => 'How much does it cost to renew your TSA PreCheck?', 'a' => 'Online renewal costs around $70, though the exact fee depends on your enrollment provider — for example, about $58.75 through IDEMIA or around $70 through Telos or CLEAR.'],
                ['q' => 'Does your TSA PreCheck number change when you renew?', 'a' => 'No. Your Known Traveler Number (KTN) stays the same through renewal, regardless of which provider you use.'],
                ['q' => 'Do you have to renew your TSA PreCheck?', 'a' => "Yes — membership isn't automatic. TSA PreCheck lasts 5 years and simply expires if you don't renew, dropping you back into standard screening lines. That's the entire reason this app exists: to make sure you don't find out at the airport."],
                ['q' => 'How to renew your TSA PreCheck online?', 'a' => "Go to the official Trusted Traveler Program (TTP) website, log in with your Known Traveler Number, and look for the renewal option once you're within your eligible window — up to 6 months before expiration. Confirm your details, pay the renewal fee, and submit. Most members are approved within a few business days with no in-person visit needed."],
            ],
            'whats_new'        => 'Added Dark Mode support. Your data now automatically backs up to your own Google account, so it survives a phone upgrade. New "Reminders not arriving?" help screen for phones with aggressive battery optimization (Xiaomi, Samsung, Huawei, and others). General reliability and polish improvements.',
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
                ['icon' => 'fa-clipboard-check', 'title' => 'Move-in / move-out checklists', 'text' => 'Built-in templates for rental condition reports that help protect both landlord and tenant deposits.'],
                ['icon' => 'fa-file-pdf',        'title' => 'Instant PDF export',           'text' => 'Create, edit and send inspection reports without ever leaving the property.'],
                ['icon' => 'fa-camera',          'title' => 'Photo annotations',            'text' => 'Snap photos during the walk-through and mark defects directly on the image.'],
                ['icon' => 'fa-list-check',      'title' => 'Fully custom checklists',      'text' => 'Build your own, or use the included HVAC, roof and electrical safety templates.'],
                ['icon' => 'fa-signature',       'title' => 'Digital signatures',           'text' => 'Capture landlord and tenant sign-off right on the screen.'],
                ['icon' => 'fa-wifi',            'title' => 'Offline mode',                 'text' => 'Works with no connection; all data is saved on your device.'],
                ['icon' => 'fa-building',        'title' => 'Company branding',             'text' => 'Add your logo, contact details and terms to every report you generate.'],
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
                ['icon' => 'fa-calculator',   'title' => '2026 IRS contribution limits', 'text' => 'Self-only and family limits, including the 55+ catch-up contribution.'],
                ['icon' => 'fa-piggy-bank',   'title' => 'Instant tax-savings estimate', 'text' => 'Federal tax savings based on your income bracket, the moment you calculate.'],
                ['icon' => 'fa-chart-pie',    'title' => 'Detailed breakdown',           'text' => 'Federal, state and FICA payroll-tax savings, broken out line by line.'],
                ['icon' => 'fa-people-group', 'title' => 'Family plan comparison',        'text' => 'See self-only vs family limits and savings side by side before you decide.'],
                ['icon' => 'fa-share-nodes',  'title' => 'Share your results',            'text' => 'Send a quick summary to anyone in one tap.'],
                ['icon' => 'fa-wifi',         'title' => 'Works completely offline',      'text' => 'Every calculation happens on your device.'],
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
