@php $user = auth()->user(); $type = $user->type ?? 'user'; @endphp

{{-- Bottom navigation – mobile only (hidden lg:) --}}
<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-slate-100 shadow-lg bottom-nav-safe">
    <div class="flex items-stretch justify-around px-2 pt-2 pb-1">

        @if($type === 'seller')
            @php $sellerUnpaid = \Illuminate\Support\Facades\Cache::remember('seller_unpaid_' . $user->id, 60, fn() => $user->leads()->whereNull('paid_at')->count()); @endphp
            {{-- ── SELLER NAV ── --}}
            <a href="{{ route('seller.dashboard') }}"
               class="bnav-item flex-1 flex flex-col items-center gap-1 py-1 text-slate-400 hover:text-teal-700 {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                <div class="bnav-icon w-8 h-8 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-gauge-high text-sm"></i>
                </div>
                <span class="text-[10px] font-bold">Dashboard</span>
            </a>

            <a href="{{ route('seller.dashboard') }}"
               class="bnav-item flex-1 flex flex-col items-center gap-1 py-1 text-slate-400 hover:text-teal-700 {{ request()->routeIs('seller.lead.*') ? 'active' : '' }}">
                <div class="bnav-icon w-8 h-8 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-inbox text-sm"></i>
                </div>
                <span class="text-[10px] font-bold">Leads</span>
            </a>

            <a href="{{ route('seller.billing') }}"
               class="bnav-item flex-1 flex flex-col items-center gap-1 py-1 text-slate-400 hover:text-teal-700 {{ request()->routeIs('seller.billing*') ? 'active' : '' }}">
                <div class="bnav-icon w-8 h-8 rounded-xl flex items-center justify-center relative">
                    <i class="fa-solid fa-file-invoice-dollar text-sm"></i>
                    @if($sellerUnpaid > 0)
                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center">
                        {{ min($sellerUnpaid, 9) }}
                    </span>
                    @endif
                </div>
                <span class="text-[10px] font-bold">Billing</span>
            </a>

            <a href="{{ route('seller.notifications') }}"
               class="bnav-item flex-1 flex flex-col items-center gap-1 py-1 text-slate-400 hover:text-teal-700 {{ request()->routeIs('seller.notifications') ? 'active' : '' }}">
                <div class="bnav-icon w-8 h-8 rounded-xl flex items-center justify-center relative">
                    <i class="fa-solid fa-bell text-sm"></i>
                    @if(($unreadNotifCount ?? 0) > 0)
                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center">
                        {{ min($unreadNotifCount, 9) }}
                    </span>
                    @endif
                </div>
                <span class="text-[10px] font-bold">Alerts</span>
            </a>

            <a href="{{ route('seller.settings') }}"
               class="bnav-item flex-1 flex flex-col items-center gap-1 py-1 text-slate-400 hover:text-teal-700 {{ request()->routeIs('seller.settings') ? 'active' : '' }}">
                <div class="bnav-icon w-8 h-8 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-gear text-sm"></i>
                </div>
                <span class="text-[10px] font-bold">Settings</span>
            </a>

        @else
            {{-- ── BUYER NAV ── --}}
            <a href="{{ route('buyer.dashboard') ?? '#' }}"
               class="bnav-item flex-1 flex flex-col items-center gap-1 py-1 text-slate-400 hover:text-teal-700 {{ request()->routeIs('buyer.dashboard') ? 'active' : '' }}">
                <div class="bnav-icon w-8 h-8 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-house text-sm"></i>
                </div>
                <span class="text-[10px] font-bold">Home</span>
            </a>

            <a href="{{ route('frontend.service.all') ?? '#' }}"
               class="bnav-item flex-1 flex flex-col items-center gap-1 py-1 text-slate-400 hover:text-teal-700 {{ request()->routeIs('frontend.service.all') ? 'active' : '' }}">
                <div class="bnav-icon w-8 h-8 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </div>
                <span class="text-[10px] font-bold">Explore</span>
            </a>

            <a href="{{ route('buyer.bookings') ?? '#' }}"
               class="bnav-item flex-1 flex flex-col items-center gap-1 py-1 text-slate-400 hover:text-teal-700 {{ request()->routeIs('buyer.bookings*') ? 'active' : '' }}">
                <div class="bnav-icon w-8 h-8 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-calendar-check text-sm"></i>
                </div>
                <span class="text-[10px] font-bold">Bookings</span>
            </a>

            <a href="{{ route('buyer.notifications') ?? '#' }}"
               class="bnav-item flex-1 flex flex-col items-center gap-1 py-1 text-slate-400 hover:text-teal-700 {{ request()->routeIs('buyer.notifications') ? 'active' : '' }}">
                <div class="bnav-icon w-8 h-8 rounded-xl flex items-center justify-center relative">
                    <i class="fa-solid fa-bell text-sm"></i>
                    @if(($unreadNotifCount ?? 0) > 0)
                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center">
                        {{ min($unreadNotifCount, 9) }}
                    </span>
                    @endif
                </div>
                <span class="text-[10px] font-bold">Alerts</span>
            </a>

            <a href="{{ route('buyer.profile') ?? '#' }}"
               class="bnav-item flex-1 flex flex-col items-center gap-1 py-1 text-slate-400 hover:text-teal-700 {{ request()->routeIs('buyer.profile') ? 'active' : '' }}">
                <div class="bnav-icon w-8 h-8 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-user text-sm"></i>
                </div>
                <span class="text-[10px] font-bold">Profile</span>
            </a>
        @endif

    </div>
</nav>
