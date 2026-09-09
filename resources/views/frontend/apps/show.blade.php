@extends('frontend.layouts._app')

@section('title', $app['name'] . ' — Free ' . $app['category'] . ' App')

@php
    $playBadge = config('tools.play_badge');
    $utm   = '&utm_source=zonelyleads&utm_medium=app_page&utm_campaign=free_apps';
    $store = $app['play_url'] . $utm;
    $hubUrl = route('frontend.apps.index');
@endphp

@section('og_title', $app['name'])
@section('og_description', $app['tagline'])
@section('og_image', $app['icon'])

@section('schema')
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
    'datePublished'       => $app['updated'],
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
@endsection

@section('css')
@include('frontend.apps._styles')
@endsection

@section('content')
<div class="zl">
    <div class="zl-wrap-sm">
        <nav class="zl-crumb" aria-label="Breadcrumb">
            <a href="{{ route('frontend.home') }}">Home</a><span>/</span>
            <a href="{{ $hubUrl }}">Free Apps</a><span>/</span>
            <span class="cur">{{ $app['name'] }}</span>
        </nav>

        <header class="zl-apphero">
            <img class="zl-apphero-ico" src="{{ $app['icon'] }}" alt="{{ $app['name'] }} icon" width="88" height="88">
            <div>
                <h1>{{ $app['name'] }}</h1>
                <p>{{ $app['tagline'] }}</p>
                <div class="zl-chips">
                    <span class="zl-chip">{{ $app['category'] }}</span>
                    <span class="zl-chip">Android</span>
                    <span class="zl-chip">{{ $app['iap'] ? 'Free · In-app purchases' : 'Free' }}</span>
                    @if ($app['offline'])<span class="zl-chip">Works offline</span>@endif
                    @if ($app['region'])<span class="zl-chip">{{ $app['region'] }}</span>@endif
                </div>
                <a class="zl-cta-inline" href="{{ $store }}" target="_blank" rel="noopener"
                   aria-label="Get {{ $app['name'] }} on Google Play">
                    <img class="zl-badge zl-badge-lg" src="{{ $playBadge }}" alt="Get it on Google Play">
                </a>
            </div>
        </header>
    </div>

    @if (!empty($app['screenshots']))
    <section class="zl-wrap" style="margin-top:3.5rem;">
        <div class="zl-shots">
            @foreach ($app['screenshots'] as $i => $shot)
                <img src="{{ $shot }}" alt="{{ $app['name'] }} screenshot {{ $i + 1 }}" loading="lazy">
            @endforeach
        </div>
    </section>
    @endif

    <div class="zl-wrap-sm">
        <div class="zl-cols">
            <div>
                <section class="zl-sec">
                    <h2 class="zl-h2">About this app</h2>
                    <p class="zl-p">{{ $app['summary'] }}</p>

                    <h3 class="zl-h3">Key features</h3>
                    @foreach ($app['features'] as $i => $f)
                        <div class="zl-feat">
                            <div class="zl-feat-n">{{ $i + 1 }}</div>
                            <div>
                                <b>{{ $f['title'] }}</b>
                                <span>{{ $f['text'] }}</span>
                            </div>
                        </div>
                    @endforeach
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

                @if (!empty($app['whats_new']))
                <section class="zl-sec">
                    <h2 class="zl-h2">What&rsquo;s new</h2>
                    <p class="zl-p" style="font-size:.9rem;">{{ $app['whats_new'] }}</p>
                </section>
                @endif
            </div>

            <aside class="zl-aside">
                <div class="zl-panel">
                    <h3>App details</h3>
                    <dl class="zl-dl">
                        <div class="row"><dt>Category</dt><dd>{{ $app['category'] }}</dd></div>
                        <div class="row"><dt>Platform</dt><dd>Android</dd></div>
                        <div class="row"><dt>Updated</dt><dd>{{ $app['updated'] }}</dd></div>
                        <div class="row"><dt>Price</dt><dd>{{ $app['iap'] ? 'Free · IAP' : 'Free' }}</dd></div>
                        <div class="row"><dt>Offline</dt><dd>{{ $app['offline'] ? 'Yes' : 'No' }}</dd></div>
                        <div class="row"><dt>Offered by</dt><dd>Zonely</dd></div>
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
            <h2>Get {{ $app['name'] }}</h2>
            <p>{{ $app['iap'] ? 'Free on Google Play. Core features free, optional in-app purchases.' : 'Free on Google Play. No account needed.' }}</p>
            <a href="{{ $store }}" target="_blank" rel="noopener" aria-label="Get {{ $app['name'] }} on Google Play">
                <img class="zl-badge zl-badge-lg" src="{{ $playBadge }}" alt="Get it on Google Play" style="margin:0 auto;">
            </a>
        </section>

        <a class="zl-back" href="{{ $hubUrl }}">&larr; Back to all apps</a>
    </div>

</div>
@endsection
