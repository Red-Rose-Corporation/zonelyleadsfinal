@extends('frontend.layouts._app')

@section('title', 'Free Apps by the Zonely Team')

@php
    $playBadge = config('tools.play_badge');
    $utm = '&utm_source=zonelyleads&utm_medium=apps_hub&utm_campaign=free_apps';
@endphp

@section('og_title', $meta_title)
@section('og_description', $meta_description)

@section('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'ItemList',
    'name'     => 'Free apps by the Zonely team',
    'itemListElement' => collect($apps)->values()->map(function ($app, $i) {
        return [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'item'     => [
                '@type'           => 'SoftwareApplication',
                'name'            => $app['name'],
                'operatingSystem' => 'ANDROID',
                'applicationCategory' => $app['category_schema'],
                'offers'          => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD'],
                'url'             => $app['play_url'],
            ],
        ];
    })->all(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('css')
@include('frontend.apps._styles')
@endsection

@section('content')
<div class="zl">

    <header class="zl-hero">
        <p class="zl-eyebrow">Free Tools &amp; Apps</p>
        <h1 class="zl-h1">Free apps built by the <em>Zonely team</em></h1>
        <p class="zl-lede">
            Small, focused apps &mdash; each one built to do a single everyday job well.
            Browse the collection and get what you need on Google Play.
        </p>
    </header>

    <main class="zl-wrap" style="padding-bottom:5rem;">
        <div class="zl-grid">
            @foreach ($apps as $app)
                @php
                    $target = ($app['internal_page'] ?? null) === 'tools'
                        ? route('frontend.tools')
                        : route('frontend.apps.show', $app['slug']);
                @endphp
                <article class="zl-card">
                    <a href="{{ $target }}" aria-label="{{ $app['name'] }} details">
                        <img class="zl-card-ico" src="{{ $app['icon'] }}" alt="{{ $app['name'] }} icon" loading="lazy" width="64" height="64">
                        <h2 class="zl-card-name">{{ $app['name'] }}</h2>
                        <p class="zl-card-desc">{{ $app['tagline'] }}</p>
                    </a>
                    <div class="zl-card-foot">
                        <a class="zl-more" href="{{ $target }}">View details &rarr;</a>
                        <a href="{{ $app['play_url'] . $utm }}" target="_blank" rel="noopener"
                           aria-label="Get {{ $app['name'] }} on Google Play">
                            <img class="zl-badge" src="{{ $playBadge }}" alt="Get it on Google Play" loading="lazy">
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <p class="zl-note">More apps from the Zonely team coming soon.</p>
    </main>

</div>
@endsection
