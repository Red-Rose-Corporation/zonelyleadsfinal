@extends('frontend.layouts._app')
@section('title', 'NYC Car Insurance Calculator')
@php
    $meta_title       = 'Free NYC Car Insurance Calculator | Zonely Tools';
    $meta_description = 'Instantly estimate your monthly and yearly auto insurance costs in New York City. Free, fast & no signup required.';
    $meta_keywords    = 'NYC car insurance calculator, auto insurance estimate, New York car insurance';

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
        ['@type' => 'ListItem', 'position' => 3, 'name' => 'NYC Car Insurance Calculator', 'item' => url()->current()],
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
@endsection

@section('content')
<div class="zl">

    <div class="zl-herowrap">
        <div class="zl-wrap-sm">
            <nav class="zl-crumb" aria-label="Breadcrumb">
                <a href="{{ route('frontend.home') }}">Home</a><span>/</span>
                <a href="{{ $hubUrl }}">Free Apps</a><span>/</span>
                <span class="cur">NYC Car Insurance Calculator</span>
            </nav>

            <header class="zl-apphero">
                @if ($app ?? null)
                    <img class="zl-apphero-ico" src="{{ $app['icon'] }}" alt="NYC Car Insurance Calculator icon" width="96" height="96">
                @endif
                <div>
                    <h1>NYC Car Insurance Calculator</h1>
                    <p>Instantly estimate your monthly and yearly auto insurance costs in New York City. Free, fast &amp; no signup required.</p>
                    <div class="zl-chips">
                        <span class="zl-chip">Tools</span>
                        <span class="zl-chip">Android</span>
                        <span class="zl-chip">Free</span>
                        <span class="zl-chip">US</span>
                    </div>
                    @if ($app ?? null)
                    <a class="zl-cta-inline" href="{{ $store }}" target="_blank" rel="noopener"
                       aria-label="Get the Car Insurance Calculator NYC app on Google Play">
                        <img class="zl-badge zl-badge-lg" src="{{ $playBadge }}" alt="Get it on Google Play">
                    </a>
                    <div class="zl-trust">
                        <span class="zl-trust-item"><i class="fas fa-lock"></i> No account needed</span>
                        <span class="zl-trust-item"><i class="fas fa-wifi"></i> Works offline</span>
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
                    <img src="{{ $shot }}" alt="NYC Car Insurance Calculator screenshot {{ $i + 1 }}" loading="lazy">
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

                <section class="zl-sec">
                    <h2 class="zl-h2">Frequently asked questions</h2>
                    @foreach ($app['faqs'] as $f)
                        <details class="zl-faq">
                            <summary>{{ $f['q'] }}<span class="pm">+</span></summary>
                            <p>{{ $f['a'] }}</p>
                        </details>
                    @endforeach
                </section>
            </div>

            <aside class="zl-aside">
                <div class="zl-panel">
                    <h3>App details</h3>
                    <dl class="zl-dl">
                        <div class="row"><dt><i class="fas fa-tag"></i>Category</dt><dd>{{ $app['category'] }}</dd></div>
                        <div class="row"><dt><i class="fas fa-mobile-screen"></i>Platform</dt><dd>Android</dd></div>
                        <div class="row"><dt><i class="fas fa-calendar"></i>Updated</dt><dd>{{ $app['updated'] }}</dd></div>
                        <div class="row"><dt><i class="fas fa-dollar-sign"></i>Price</dt><dd>{{ $app['iap'] ? 'Free · IAP' : 'Free' }}</dd></div>
                        <div class="row"><dt><i class="fas fa-wifi"></i>Offline</dt><dd>{{ $app['offline'] ? 'Yes' : 'No' }}</dd></div>
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
            <h2>Get the Car Insurance Calculator NYC app</h2>
            <p>Free on Google Play &mdash; estimate your NYC premium, track TLC &amp; DMV points, and see your PIRP discount. Works fully offline.</p>
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
            <div class="nm">Car Insurance Calculator NYC</div>
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
