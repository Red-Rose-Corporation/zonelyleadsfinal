@extends('frontend.layouts._app')
@section('title', 'NYC Car Insurance Calculator')
@php
    $meta_title       = 'Free NYC Car Insurance Calculator | Zonely Tools';
    $meta_description = 'Instantly estimate your monthly and yearly auto insurance costs in New York City. Free, fast & no signup required.';
    $meta_keywords    = 'NYC car insurance calculator, auto insurance estimate, New York car insurance';
    $playBadge = config('tools.play_badge');
@endphp

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
@endif
@endsection

@if ($app ?? null)
@section('css')
@include('frontend.apps._styles')
@endsection
@endif

@section('content')

    {{-- HERO SECTION (MATCHES HOME PAGE) --}}
    <header class="mt-20 max-w-5xl mx-auto pt-14 pb-10 px-4 text-center">
        <h1 class="font-serif text-4xl sm:text-6xl md:text-7xl leading-tight mb-6">
            NYC Car Insurance <br class="hidden sm:block">
            <span class="text-teal-700 italic font-normal">Calculator</span>
        </h1>

        <p class="text-slate-500 text-base sm:text-lg max-w-2xl mx-auto">
            Instantly estimate your monthly and yearly auto insurance costs in New York City.
            Free, fast & no signup required.
        </p>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="max-w-7xl mx-auto px-4 pb-10">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

            {{-- CALCULATOR CARD --}}
            <div class="bg-white rounded-3xl border p-8 shadow-sm hover:shadow-xl transition">

                <h2 class="text-xl font-bold mb-6">Enter Your Details</h2>

                <form id="calcForm" onsubmit="event.preventDefault();runCalc();" class="space-y-5">

                    <div>
                        <label class="text-sm font-semibold">Age</label>
                        <input id="age" type="number" min="16"
                               class="w-full mt-2 px-4 py-3 rounded-xl border outline-none focus:border-teal-600"
                               placeholder="Your age" required>
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Car Value (USD)</label>
                        <input id="carValue" type="number" min="0"
                               class="w-full mt-2 px-4 py-3 rounded-xl border outline-none focus:border-teal-600"
                               placeholder="Example: 20000" required>
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Driving Record</label>
                        <select id="record"
                                class="w-full mt-2 px-4 py-3 rounded-xl border">
                            <option value="clean">Clean Record</option>
                            <option value="1 accident">1 Accident</option>
                            <option value="multiple accidents">Multiple Accidents</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Coverage Type</label>
                        <select id="coverage"
                                class="w-full mt-2 px-4 py-3 rounded-xl border">
                            <option value="full">Full Coverage</option>
                            <option value="liability">Liability Only</option>
                        </select>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button class="bg-teal-700 text-white px-6 py-3 rounded-xl font-bold hover:bg-teal-800 transition">
                            Calculate Insurance
                        </button>

                        <button type="button" onclick="resetForm()"
                                class="px-6 py-3 rounded-xl border hover:bg-slate-100">
                            Reset
                        </button>
                    </div>
                </form>
            </div>

            {{-- RESULT CARD --}}
            <div id="resultCard"
                 class="bg-white rounded-3xl border p-8 shadow-sm opacity-0 translate-y-2 transition-all duration-300">

                <h2 class="text-xl font-bold mb-6">Estimated Cost</h2>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Yearly Premium</span>
                        <span id="yearlyVal" class="text-2xl font-bold">—</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Monthly Premium</span>
                        <span id="monthlyVal" class="text-2xl font-bold">—</span>
                    </div>

                    <div class="text-xs text-slate-400 pt-3 border-t">
                        <span id="breakdown">—</span>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button onclick="savePDF()"
                            class="bg-teal-700 hover:bg-teal-800 text-white px-5 py-2 rounded-xl text-xs font-bold transition">
                        Save as PDF
                    </button>

                    <a href="{{ route('frontend.home') }}"
                       class="px-5 py-2 rounded-xl border text-xs font-bold hover:bg-slate-100">
                        Get Real Offers
                    </a>
                </div>

                <p class="text-xs text-slate-400 mt-6">
                    * Estimates are approximate and not guaranteed quotes.
                </p>
            </div>

        </div>
    </main>

    @if ($app ?? null)
        <section class="zl" style="padding:0 1rem 4rem;">
            <div class="zl-promo">
                <img class="ico" src="{{ $app['icon'] }}" alt="{{ $app['name'] }} icon" loading="lazy" width="72" height="72">
                <div class="txt">
                    <h2>Prefer it on your phone?</h2>
                    <p>{{ $app['name'] }} is a free Android app &mdash; the same estimate plus a TLC &amp; DMV points tracker and a PIRP discount estimator, and it works fully offline.</p>
                </div>
                <div class="act">
                    <a href="{{ $app['play_url'] }}&utm_source=zonelyleads&utm_medium=tools_page&utm_campaign=free_apps"
                       target="_blank" rel="noopener" aria-label="Get {{ $app['name'] }} on Google Play">
                        <img class="zl-badge zl-badge-lg" src="{{ $playBadge }}" alt="Get it on Google Play">
                    </a>
                    <a class="alllink" href="{{ route('frontend.apps.index') }}">See all free apps by Zonely &rarr;</a>
                </div>
            </div>
        </section>
    @endif
@endsection

@section('scripts')
<script>
    function calculateLocal(data) {
        const age = Number(data.age);
        const car_value = Number(data.car_value);

        let base_rate = 1800;
        let age_factor = age < 25 ? 700 : age <= 40 ? 300 : 200;
        let car_factor = car_value * 0.02;

        let record_factor = 0;
        if (data.driving_record === '1 accident') record_factor = 500;
        if (data.driving_record === 'multiple accidents') record_factor = 1200;

        let coverage_factor = data.coverage === 'full' ? 900 : 300;

        const total = base_rate + age_factor + car_factor + record_factor + coverage_factor;

        return {
            yearly_premium: Math.round(total),
            monthly_premium: Math.round(total / 12),
            breakdown: {
                base_rate,
                age_factor,
                car_factor: Math.round(car_factor),
                record_factor,
                coverage_factor
            }
        };
    }

    function runCalc() {
        const data = {
            age: age.value,
            car_value: carValue.value,
            driving_record: record.value,
            coverage: coverage.value
        };

        const result = calculateLocal(data);

        yearlyVal.textContent = '$' + result.yearly_premium;
        monthlyVal.textContent = '$' + result.monthly_premium;
        breakdown.textContent =
            `Base: ${result.breakdown.base_rate}, Age: ${result.breakdown.age_factor}, Car: ${result.breakdown.car_factor}, Record: ${result.breakdown.record_factor}, Coverage: ${result.breakdown.coverage_factor}`;

        resultCard.classList.remove('opacity-0', 'translate-y-2');
    }

    function resetForm() {
        calcForm.reset();
        resultCard.classList.add('opacity-0', 'translate-y-2');
    }

    function savePDF() {
        window.print();
    }
</script>
@endsection
