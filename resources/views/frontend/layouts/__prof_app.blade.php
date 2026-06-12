<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Zonely Dashboard') — Zonely</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('frontend/img/favicon.svg') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    teal: {
                        50:  '#eafaf9', 100: '#d5f3f1', 200: '#b0e7e4',
                        300: '#86d9d5', 400: '#5dcbc6', 500: '#3cbab4',
                        600: '#32a29d', 700: '#2a8c87', 800: '#1e6e6a',
                        900: '#135150', 950: '#0d3836',
                    },
                },
            },
        },
    };
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .menu-active { background-color: #CCFBF1; color: #2a8c87; font-weight: 600; }
        .landing-preview {
            height: 620px; overflow-y: auto;
            scrollbar-width: thin; scrollbar-color: #64748b #f1f5f9; border-radius: 24px;
        }
        .landing-preview::-webkit-scrollbar { width: 6px; }
        .landing-preview::-webkit-scrollbar-thumb { background-color: #64748b; border-radius: 20px; }
    </style>
    @yield('css')
</head>

<body class="bg-slate-50 min-h-screen flex flex-col">

    {{-- ── TOP HEADER ── --}}
    <header class="sticky top-0 z-50 bg-white border-b border-slate-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">

            {{-- Logo --}}
            <a href="{{ route('frontend.home') }}" class="flex items-center gap-2 shrink-0">
                <img src="{{ asset('frontend/img/zonely_logo.png') }}" class="w-8 h-8" alt="Zonely">
                <span class="font-extrabold text-teal-700 tracking-tight text-base hidden sm:inline">
                    ZONELY<span class="text-teal-900">.</span>
                </span>
            </a>

            {{-- Page title (desktop) --}}
            <h1 class="hidden md:block text-sm font-semibold text-slate-500 flex-1 text-center">
                @yield('page-title', 'Dashboard')
            </h1>

            {{-- Right controls --}}
            <div class="flex items-center gap-2 shrink-0">

                {{-- Notifications bell --}}
                <a href="{{ route('seller.notifications') }}"
                   class="relative w-9 h-9 rounded-xl bg-slate-100 hover:bg-teal-50 flex items-center justify-center text-slate-500 hover:text-teal-700 transition">
                    <i class="fas fa-bell text-sm"></i>
                </a>

                {{-- User dropdown --}}
                <div class="relative" id="userMenuWrap">
                    <button onclick="toggleUserMenu()"
                            class="flex items-center gap-2 px-2.5 py-2 rounded-xl hover:bg-slate-100 transition">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset(auth()->user()->profile_photo) }}"
                                 class="w-8 h-8 rounded-full object-cover border border-slate-200" alt="">
                        @else
                            <div class="w-8 h-8 rounded-full bg-teal-700 flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
                            </div>
                        @endif
                        <span class="hidden sm:block text-sm font-medium text-slate-700 max-w-[100px] truncate">
                            {{ explode(' ', trim(auth()->user()->name ?? ''))[0] }}
                        </span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400"></i>
                    </button>

                    <div id="userMenu"
                         class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 text-sm">
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="font-semibold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('seller.onboarding') }}"
                           class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 text-slate-700 transition">
                            <i class="fas fa-user-pen w-4 text-center text-teal-600"></i> My Profile
                        </a>
                        <a href="{{ route('seller.settings') }}"
                           class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 text-slate-700 transition">
                            <i class="fas fa-gear w-4 text-center text-slate-400"></i> Settings
                        </a>
                        @if(auth()->user()->slug)
                        <a href="{{ route('frontend.service.show', auth()->user()->slug) }}" target="_blank"
                           class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 text-slate-700 transition">
                            <i class="fas fa-arrow-up-right-from-square w-4 text-center text-slate-400"></i> View Public Profile
                        </a>
                        @endif
                        <a href="{{ route('frontend.home') }}"
                           class="flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 text-slate-700 transition">
                            <i class="fas fa-globe w-4 text-center text-slate-400"></i> Back to Site
                        </a>
                        <div class="border-t border-slate-100 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-red-600 hover:bg-red-50 transition text-left">
                                <i class="fas fa-sign-out-alt w-4 text-center"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </header>

    {{-- ── BODY: sidebar + content ── --}}
    <div class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 py-6 pb-24 lg:pb-6">
        <div class="flex gap-6 items-start">

            {{-- Sidebar — desktop only --}}
            <aside class="hidden lg:flex flex-col w-52 xl:w-56 shrink-0">

                {{-- Profile card --}}
                <div class="bg-white rounded-2xl p-4 mb-3 text-center border border-slate-100 shadow-sm">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ asset(auth()->user()->profile_photo) }}"
                             class="w-14 h-14 rounded-xl object-cover mx-auto mb-2 border border-slate-200" alt="">
                    @else
                        <div class="w-14 h-14 rounded-xl bg-teal-700 flex items-center justify-center text-white text-xl font-bold mx-auto mb-2">
                            {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
                        </div>
                    @endif
                    <p class="font-semibold text-slate-900 text-sm truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate mb-3">{{ auth()->user()->business_name ?? 'Seller Account' }}</p>
                    @if(auth()->user()->slug)
                    <a href="{{ route('frontend.service.show', auth()->user()->slug) }}" target="_blank"
                       class="text-xs text-teal-700 font-semibold hover:text-teal-800 hover:underline transition">
                        View Public Profile →
                    </a>
                    @endif
                </div>

                {{-- Nav links --}}
                @php
                    $cr = Route::currentRouteName();
                    $isProfileSubPage = str_starts_with($cr ?? '', 'user.');
                    $sideNavItems = [
                        ['route' => 'seller.dashboard',     'icon' => 'fa-gauge-high',          'label' => 'Lead Dashboard'],
                        ['route' => 'seller.onboarding',    'icon' => 'fa-user-pen',            'label' => 'My Profile'],
                        ['route' => 'seller.billing',       'icon' => 'fa-file-invoice-dollar', 'label' => 'Billing'],
                        ['route' => 'seller.schedule',      'icon' => 'fa-calendar-days',       'label' => 'Schedule'],
                        ['route' => 'seller.reviews',       'icon' => 'fa-star',                'label' => 'Reviews'],
                        ['route' => 'seller.affiliate',     'icon' => 'fa-link',                'label' => 'Affiliate'],
                        ['route' => 'seller.notifications', 'icon' => 'fa-bell',                'label' => 'Notifications'],
                        ['route' => 'seller.settings',      'icon' => 'fa-gear',                'label' => 'Settings'],
                    ];
                @endphp
                <nav class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    @foreach($sideNavItems as $item)
                    <a href="{{ route($item['route']) }}"
                       @php $active = $cr === $item['route'] || ($item['route'] === 'seller.onboarding' && $isProfileSubPage); @endphp
                       class="flex items-center gap-3 px-4 py-3 text-sm border-b border-slate-50 last:border-0 transition
                              {{ $active ? 'bg-teal-50 text-teal-800 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fas {{ $item['icon'] }} w-4 text-center
                                  {{ $active ? 'text-teal-700' : 'text-slate-400' }}"></i>
                        {{ $item['label'] }}
                    </a>
                    @endforeach
                </nav>

            </aside>

            {{-- Main content --}}
            <main class="flex-1 min-w-0">
                @php
                    $__seller = auth()->user();
                    $__overdue = $__seller && $__seller->type === 'seller' && $__seller->isOverdue();
                    if ($__overdue) {
                        $__unpaid    = $__seller->unpaidLeadsCount();
                        $__threshold = $__seller->leadThreshold();
                        $__amount    = $__seller->leads()->whereNull('paid_at')->sum('fee');
                    }
                @endphp
                @if($__overdue)
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-triangle-exclamation text-red-600 text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-red-700">Your profile is currently private</p>
                            <p class="text-xs text-red-500 mt-0.5">
                                You have <strong>{{ $__unpaid }} unpaid lead{{ $__unpaid > 1 ? 's' : '' }}</strong>
                                — <strong>${{ number_format($__amount, 2) }} due</strong>.
                                Pay now to go live and receive new leads again.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('seller.billing') }}"
                       class="shrink-0 bg-red-600 hover:bg-red-700 text-white font-bold px-5 py-2.5 rounded-xl text-xs transition text-center whitespace-nowrap">
                        Pay Now →
                    </a>
                </div>
                @endif
                @yield('content')
            </main>

        </div>
    </div>

    {{-- ── FOOTER ── --}}
    <footer class="bg-white border-t border-slate-100 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <img src="{{ asset('frontend/img/zonely_logo.png') }}" class="w-6 h-6" alt="Zonely">
                <span class="text-xs font-bold text-slate-500">ZONELY<span class="text-teal-700">.</span></span>
            </div>
            <p class="text-xs text-slate-400">© {{ date('Y') }} Zonely. All rights reserved.</p>
            <div class="flex gap-4 text-xs text-slate-400">
                <a href="{{ route('frontend.privacy-policy') }}"      class="hover:text-teal-700 transition">Privacy</a>
                <a href="{{ route('frontend.terms-and-condition') }}" class="hover:text-teal-700 transition">Terms</a>
                <a href="{{ route('frontend.help') }}"                class="hover:text-teal-700 transition">Help</a>
                <a href="{{ route('frontend.home') }}"                class="hover:text-teal-700 transition">Back to Site</a>
            </div>
        </div>
    </footer>

    {{-- Mobile bottom nav --}}
    @include('frontend.layouts._account_nav')

    <script>
        function toggleUserMenu() {
            document.getElementById('userMenu').classList.toggle('hidden');
        }
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#userMenuWrap')) {
                document.getElementById('userMenu')?.classList.add('hidden');
            }
        });

        // Legacy helpers used by some seller pages
        function selectTitle(btn) {
            document.querySelectorAll('.title-btn').forEach(b => {
                b.classList.remove('bg-teal-700', 'text-white');
                b.classList.add('bg-gray-100');
            });
            btn.classList.add('bg-teal-700', 'text-white');
        }
        function toggleLanguage(btn) {
            if (btn.classList.contains('bg-teal-100')) {
                btn.classList.remove('bg-teal-100', 'text-teal-800', 'border-teal-600');
                btn.classList.add('bg-gray-100');
            } else {
                btn.classList.add('bg-teal-100', 'text-teal-800', 'border-teal-600');
            }
        }
    </script>

    @yield('js')
    @yield('scripts')
</body>

</html>
