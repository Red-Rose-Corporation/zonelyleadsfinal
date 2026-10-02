@extends('frontend.layouts._app')
@section('title', 'TLC Insurance NYC Calculator: See Premium + PIRP Savings')
@php
    $meta_title       = 'TLC Insurance NYC Calculator: See Premium + PIRP Savings';
    $meta_description = 'TLC Insurance NYC Calculator: see premium and PIRP savings, track TLC and DMV points, shift income and congestion fees. Free app for NYC drivers, no login.';
    $meta_keywords    = 'TLC insurance NYC, TLC insurance calculator, PIRP calculator, TLC points tracker';

    $playBadge = config('tools.play_badge');
    $utm       = '&utm_source=zonelyleads&utm_medium=tools_page&utm_campaign=free_apps';
    $store     = ($app['play_url'] ?? '#') . $utm;
    $hubUrl    = route('frontend.apps.index');
    $related   = collect(config('tools.apps', []))->except('car-insurance-calculator-nyc')->take(3)->values();
@endphp

@section('og_title', $meta_title)
@section('og_description', $meta_description)
@section('og_image', $app['icon'] ?? '')

@section('schema')
@if ($app ?? null)
<script type="application/ld+json">
{!! json_encode([
    '@context'            => 'https://schema.org',
    '@type'               => 'SoftwareApplication',
    'name'                => $app['name'],
    'operatingSystem'     => 'ANDROID',
    'applicationCategory' => $app['category_schema'],
    'description'         => $app['summary'],
    'image'               => $app['icon'],
    'url'                 => url()->current(),
    'installUrl'          => $app['play_url'],
    'downloadUrl'         => $app['play_url'],
    'offers'              => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD'],
    'publisher'           => ['@type' => 'Organization', 'name' => 'Zonely'],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('frontend.home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Free Apps', 'item' => $hubUrl],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $app['name'], 'item' => url()->current()],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'FAQPage',
    'mainEntity' => collect($app['faqs'])->map(fn ($f) => [
        '@type'          => 'Question',
        'name'           => $f['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
    ])->all(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif
@endsection

@section('css')
@include('frontend.apps._styles')
<style>
    .zl-glance{background:linear-gradient(165deg,var(--zl-teal-50),#fff 70%);border:1px solid #bdeae6;border-radius:20px;padding:1.4rem 1.5rem;}
    .zl-glance-t{font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--zl-teal-d);margin:0 0 1rem;}
    .zl-glance dl{display:grid;grid-template-columns:1fr;gap:.75rem;margin:0;}
    @media(min-width:560px){.zl-glance dl{grid-template-columns:1fr 1fr;}}
    .zl-glance dl > div{background:#fff;border:1px solid #cdeeeb;border-radius:14px;padding:.85rem 1rem;}
    .zl-glance dt{font-size:11px;letter-spacing:.06em;text-transform:uppercase;color:var(--zl-slate);font-weight:600;margin:0 0 .25rem;}
    .zl-glance dd{margin:0;font-weight:700;font-size:.98rem;color:var(--zl-ink);}
    .zl-glance-n{font-size:.78rem;color:var(--zl-slate);margin:1rem 0 0;line-height:1.5;}
    .zl-steps{list-style:none;counter-reset:s;margin:0 0 1.25rem;padding:0;}
    .zl-steps li{counter-increment:s;position:relative;padding:.2rem 0 1rem 3rem;color:var(--zl-slate);line-height:1.7;}
    .zl-steps li::before{content:counter(s);position:absolute;left:0;top:0;width:2.1rem;height:2.1rem;border-radius:50%;background:var(--zl-teal);color:#fff;font-weight:700;display:flex;align-items:center;justify-content:center;font-size:.9rem;}
    .zl-steps b{color:var(--zl-ink);}
    .zl-tablewrap{overflow-x:auto;margin:1.25rem 0;border:1px solid var(--zl-line);border-radius:14px;}
    .zl-table{width:100%;border-collapse:collapse;font-size:.92rem;min-width:320px;}
    .zl-table th{background:var(--zl-teal-d);color:#fff;text-align:left;padding:.7rem 1rem;font-size:.78rem;letter-spacing:.04em;}
    .zl-table td{padding:.7rem 1rem;border-top:1px solid var(--zl-line);color:var(--zl-slate);}
    .zl-table tbody tr:nth-child(even){background:#fafcfc;}
    .zl-list{margin:0 0 1.25rem;padding-left:1.2rem;color:var(--zl-slate);line-height:1.75;}
    .zl-list b{color:var(--zl-ink);}
    .zl-faq summary{min-height:44px;}
    .zl-disc{font-size:.8rem;color:var(--zl-mut);line-height:1.6;border-top:1px solid var(--zl-line);padding-top:1.25rem;margin:0;}
</style>
@endsection

@section('content')
<div class="zl">

    <div class="zl-herowrap">
        <div class="zl-wrap-sm">
            <nav class="zl-crumb" aria-label="Breadcrumb">
                <a href="{{ route('frontend.home') }}">Home</a><span>/</span>
                <a href="{{ $hubUrl }}">Free Apps</a><span>/</span>
                <span class="cur">{{ $app['name'] ?? 'TLC Insurance NYC Calculator' }}</span>
            </nav>

            <header class="zl-apphero">
                @if ($app ?? null)
                    <img class="zl-apphero-ico" src="{{ $app['icon'] }}" alt="TLC Insurance NYC Calculator icon" width="96" height="96">
                @endif
                <div>
                    <h1>TLC Insurance NYC Calculator</h1>
                    <p>See your TLC insurance premium, how much PIRP saves you, and how close your points are to a suspension. A free app built for NYC TLC drivers, with no account needed.</p>
                    <div class="zl-chips">
                        <span class="zl-chip">Tools</span>
                        <span class="zl-chip">NYC TLC drivers</span>
                        <span class="zl-chip">Android</span>
                        <span class="zl-chip">Free</span>
                        <span class="zl-chip">4 languages</span>
                    </div>
                    @if ($app ?? null)
                    <a class="zl-cta-inline" href="{{ $store }}" target="_blank" rel="noopener"
                       aria-label="Get the TLC Insurance NYC Calculator app on Google Play">
                        <img class="zl-badge zl-badge-lg" src="{{ $playBadge }}" alt="Get it on Google Play">
                    </a>
                    <div class="zl-trust">
                        <span class="zl-trust-item"><i class="fas fa-lock"></i> No account needed</span>
                        <span class="zl-trust-item"><i class="fas fa-language"></i> English, Español, বাংলা, Kreyòl</span>
                        <span class="zl-trust-item"><i class="fas fa-shield-halved"></i> Privacy-first</span>
                    </div>
                    @endif
                </div>
            </header>
        </div>
    </div>

    @if (!empty($app['screenshots']))
    <section class="zl-wrap">
        <div class="zl-shots">
            @foreach ($app['screenshots'] as $i => $shot)
                <div class="zl-phone">
                    <img src="{{ $shot }}" alt="TLC Insurance NYC Calculator screenshot {{ $i + 1 }}" loading="lazy">
                </div>
            @endforeach
        </div>
    </section>
    @endif

    @if ($app ?? null)
    <div class="zl-wrap-sm">
        <div class="zl-cols">
            <div>
                <section class="zl-sec">
                    <div class="zl-glance">
                        <p class="zl-glance-t">TLC insurance in NYC at a glance</p>
                        <dl>
                            <div><dt>TLC suspension</dt><dd>6 to 9 points in 15 months</dd></div>
                            <div><dt>TLC revocation</dt><dd>10 or more points</dd></div>
                            <div><dt>PIRP insurance discount</dt><dd>10% of base premium, 3 years</dd></div>
                            <div><dt>PIRP point reduction</dt><dd>Up to 4 DMV points</dd></div>
                        </dl>
                        <p class="zl-glance-n">Thresholds come from TLC and DMV rules and can change. Confirm current rules with the TLC and DMV.</p>
                    </div>
                </section>

                <section class="zl-sec">
                    <h2 class="zl-h2">About this app</h2>
                    <p class="zl-p">{{ $app['summary'] }}</p>

                    <h3 class="zl-h3">Key features</h3>
                    <div class="zl-featgrid">
                        @foreach ($app['features'] as $f)
                            <div class="zl-featcard">
                                <div class="ic"><i class="fas {{ $f['icon'] ?? 'fa-circle-check' }}"></i></div>
                                <b>{{ $f['title'] }}</b>
                                <span>{{ $f['text'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="zl-sec" id="how-it-works">
                    <h2 class="zl-h2">How to estimate your TLC insurance in 3 steps</h2>
                    <ol class="zl-steps">
                        <li><b>Enter your details.</b> Add your TLC and DMV points, your vehicle type and how many years you have been licensed.</li>
                        <li><b>See your estimate.</b> The app shows a monthly and yearly premium estimate. Save it as a PDF or share it.</li>
                        <li><b>Check PIRP.</b> See how many points and dollars the 6-hour PIRP course could save, then decide whether it is worth taking.</li>
                    </ol>
                    <p class="zl-p">When you are ready, you can ask for real quotes from NYC TLC insurers. That is the only time anything leaves your phone, and it sends just your points, vehicle type and years licensed.</p>
                </section>

                <section class="zl-sec" id="tlc-points">
                    <h2 class="zl-h2">How TLC points work</h2>
                    <p class="zl-p">The TLC counts points over a shorter, stricter window than the DMV. Points are counted from the date of conviction, not the date of the violation, and points from other states can count too.</p>
                    <div class="zl-tablewrap">
                        <table class="zl-table">
                            <thead><tr><th>Points in 15 months</th><th>What can happen</th></tr></thead>
                            <tbody>
                                <tr><td>Under 6</td><td>No suspension from points alone</td></tr>
                                <tr><td>6 to 9</td><td>License can be suspended</td></tr>
                                <tr><td>10 or more</td><td>License can be revoked</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="zl-p">The points tracker in the app counts your active points against these windows, so you can see when they drop off. These rules can change, so confirm them with the TLC.</p>
                </section>

                <section class="zl-sec" id="pirp">
                    <h2 class="zl-h2">What PIRP actually does</h2>
                    <p class="zl-p">PIRP is the DMV-approved 6-hour Point and Insurance Reduction Program. It helps in two ways, and it is worth knowing the limits of both.</p>
                    <ul class="zl-list">
                        <li><b>Insurance discount.</b> A 10% discount on the base premium of liability and collision coverage for three years.</li>
                        <li><b>Point reduction.</b> Up to 4 points are removed from your DMV point total for violations in the last 18 months.</li>
                        <li><b>Limits.</b> It does not erase the violations, and the insurance discount needs about three years between courses.</li>
                    </ul>
                    <p class="zl-p">The PIRP calculator in the app estimates your savings from your own numbers before you spend the time and money on a course.</p>
                </section>

                <section class="zl-sec" id="insurers">
                    <h2 class="zl-h2">TLC insurance companies in NYC</h2>
                    <p class="zl-p">Rates for TLC vehicles vary a lot between carriers, so comparing is where most drivers save. The app shows carriers that write NYC TLC insurance, including American Transit, Hereford Insurance and Affirmative Direct, and lets you request quotes.</p>
                    <ul class="zl-list">
                        <li><b>What sets your price.</b> Your TLC and DMV points, vehicle type and years licensed are the inputs the calculator uses.</li>
                        <li><b>How to pay less.</b> Keep points low and consider PIRP, which can earn a 10% discount on the base premium for three years.</li>
                        <li><b>Before you buy.</b> Confirm the policy meets the TLC insurance requirements. A carrier appearing in the app is not an endorsement.</li>
                    </ul>
                </section>

                <section class="zl-sec">
                    <h2 class="zl-h2">Frequently asked questions</h2>
                    @foreach ($app['faqs'] as $f)
                        <details class="zl-faq">
                            <summary>{{ $f['q'] }}<span class="pm">+</span></summary>
                            <p>{{ $f['a'] }}</p>
                        </details>
                    @endforeach
                </section>

                <p class="zl-disc">This app is an independent tool for NYC TLC drivers and is not affiliated with, endorsed by, or sponsored by the NYC Taxi and Limousine Commission, the NY DMV or the MTA. Insurance estimates are informational only; your actual premium is set by a licensed insurer.</p>
            </div>

            <aside class="zl-aside">
                <div class="zl-panel">
                    <h3>App details</h3>
                    <dl class="zl-dl">
                        <div class="row"><dt><i class="fas fa-tag"></i>Category</dt><dd>{{ $app['category'] }}</dd></div>
                        <div class="row"><dt><i class="fas fa-mobile-screen"></i>Platform</dt><dd>Android</dd></div>
                        <div class="row"><dt><i class="fas fa-calendar"></i>Updated</dt><dd>{{ $app['updated'] }}</dd></div>
                        <div class="row"><dt><i class="fas fa-dollar-sign"></i>Price</dt><dd>{{ $app['iap'] ? 'Free · IAP' : 'Free' }}</dd></div>
                        <div class="row"><dt><i class="fas fa-language"></i>Languages</dt><dd>4</dd></div>
                        <div class="row"><dt><i class="fas fa-user-lock"></i>Account</dt><dd>Not needed</dd></div>
                        <div class="row"><dt><i class="fas fa-building"></i>Offered by</dt><dd>Zonely</dd></div>
                    </dl>
                    <a class="zl-privacy" href="{{ $app['play_url'] }}" target="_blank" rel="noopener">Data safety &amp; privacy &rarr;</a>
                </div>

                @if ($related->isNotEmpty())
                <div>
                    <h3 style="font-size:1rem;font-weight:600;margin:0 0 .85rem;">More apps by Zonely</h3>
                    <div class="zl-rel">
                        @foreach ($related as $r)
                            @php
                                $rt = ($r['internal_page'] ?? null) === 'tools'
                                    ? route('frontend.tools')
                                    : route('frontend.apps.show', $r['slug']);
                            @endphp
                            <a href="{{ $rt }}">
                                <img src="{{ $r['icon'] }}" alt="" loading="lazy">
                                <span>{{ $r['name'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </aside>
        </div>

        <section class="zl-band">
            <h2>Get the TLC Insurance NYC Calculator app</h2>
            <p>Free on Google Play. See your premium, your PIRP savings and your points, and run your whole shift in one app.</p>
            <a href="{{ $store }}" target="_blank" rel="noopener" aria-label="Get the app on Google Play">
                <img class="zl-badge zl-badge-lg" src="{{ $playBadge }}" alt="Get it on Google Play" style="margin:0 auto;">
            </a>
        </section>

        <a class="zl-back" href="{{ $hubUrl }}">&larr; Back to all apps</a>
    </div>

    <div class="zl-footbridge" aria-hidden="true"></div>

    <div class="zl-stickybar" id="zlStickyBar">
        <img class="ico" src="{{ $app['icon'] }}" alt="">
        <div>
            <div class="nm">{{ $app['name'] }}</div>
            <div class="sub">Free on Google Play</div>
        </div>
        <a class="go" href="{{ $store }}" target="_blank" rel="noopener">Install</a>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
    (function () {
        var bar = document.getElementById('zlStickyBar');
        var hero = document.querySelector('.zl-herowrap');
        var footer = document.querySelector('footer');
        if (!bar || !hero) return;

        var pastHero = false;
        var footerVisible = false;
        function sync() {
            bar.classList.toggle('show', pastHero && !footerVisible);
        }

        window.addEventListener('scroll', function () {
            pastHero = window.scrollY > hero.offsetHeight;
            sync();
        }, { passive: true });

        // Don't let the bar cover the footer once it scrolls into view.
        if (footer && 'IntersectionObserver' in window) {
            new IntersectionObserver(function (entries) {
                footerVisible = entries[0].isIntersecting;
                sync();
            }).observe(footer);
        }
    })();
</script>
@endsection
