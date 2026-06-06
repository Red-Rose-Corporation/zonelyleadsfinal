@php
    $userType  = auth()->user()?->type;
    $isAdmin   = $userType === 'admin';
    $isCoo     = $userType === 'coo';
    $mgProfile = auth()->user()?->managerProfile;
    $canSee    = fn(string $module) => $isAdmin || $isCoo || ($mgProfile && $mgProfile->hasModule($module));
@endphp

<nav class="sidebar" id="sidebar">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-white d-flex align-items-center gap-2 logo-text">
            <img src="{{ asset('frontend/img/zonely_logo.png') }}" width="32" height="32" style="filter:brightness(0) invert(1)" alt="Zonely">
            <span class="fw-black fs-5">Zonely</span>
        </a>
        <i class="fas fa-bars btn-toggle-sidebar text-white" id="toggleSidebar" style="cursor:pointer"></i>
    </div>

    <ul class="nav flex-column gap-1">

        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}"
               class="{{ Route::is('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-gauge-high"></i>
                <span class="nav-text ms-2">Dashboard</span>
            </a>
        </li>

        @if($canSee('profiles'))
        <li class="nav-item">
            <a href="{{ route('admin.profiles.index') }}"
               class="{{ Route::is('admin.profiles.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span class="nav-text ms-2">All Profiles</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.profiles.index', ['type' => 'seller', 'status' => 'unverified']) }}"
               class="{{ request('status') === 'unverified' ? 'active' : '' }}">
                <i class="fas fa-user-check"></i>
                <span class="nav-text ms-2">Pending Verification</span>
            </a>
        </li>
        @endif

        @if($canSee('leads'))
        <li class="nav-item mt-2">
            <a href="{{ route('admin.leads') }}"
               class="{{ Route::is('admin.leads') ? 'active' : '' }}">
                <i class="fas fa-bolt"></i>
                <span class="nav-text ms-2">Lead Dashboard</span>
            </a>
        </li>
        @endif

        @if($canSee('affiliate'))
        <li class="nav-item">
            <a href="{{ route('admin.affiliate') }}"
               class="{{ Route::is('admin.affiliate') ? 'active' : '' }}">
                <i class="fas fa-share-nodes"></i>
                <span class="nav-text ms-2">Affiliate</span>
            </a>
        </li>
        @endif

        @if($canSee('hierarchy'))
        <li class="nav-item">
            <a href="{{ route('admin.hierarchy') }}"
               class="{{ Route::is('admin.hierarchy*') ? 'active' : '' }}">
                <i class="fas fa-sitemap"></i>
                <span class="nav-text ms-2">Hierarchy</span>
            </a>
        </li>
        @endif

        @if($canSee('blogs'))
        <li class="nav-item">
            <a href="{{ route('admin.blogs.index') }}"
               class="{{ Route::is('admin.blogs.*') ? 'active' : '' }}">
                <i class="fas fa-pen-nib"></i>
                <span class="nav-text ms-2">Blog</span>
            </a>
        </li>
        @endif

        @if($canSee('categories'))
        <li class="nav-item">
            <a href="{{ route('admin.categories.index') }}"
               class="{{ Route::is('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i>
                <span class="nav-text ms-2">Categories</span>
            </a>
        </li>
        @endif

        @if($canSee('locations'))
        <li class="nav-item">
            <a href="{{ route('admin.locations') }}"
               class="{{ Route::is('admin.locations') || Route::is('admin.countries.*') || Route::is('admin.states.*') || Route::is('admin.cities.*') ? 'active' : '' }}">
                <i class="fas fa-map-marked-alt"></i>
                <span class="nav-text ms-2">Locations</span>
            </a>
        </li>
        @endif

        @if($canSee('services'))
        <li class="nav-item">
            <a href="{{ route('admin.services.index') }}"
               class="{{ Route::is('admin.services.*') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i>
                <span class="nav-text ms-2">Services</span>
            </a>
        </li>
        @endif

        {{-- Managers section — admin and COO --}}
        @if($isAdmin || $isCoo)
        <li class="nav-item">
            <a href="{{ route('admin.pricing.index') }}"
               class="{{ Route::is('admin.pricing.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i>
                <span class="nav-text ms-2">Pricing Rules</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.managers.index') }}"
               class="{{ Route::is('admin.managers.*') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i>
                <span class="nav-text ms-2">Managers</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.twilio.settings') }}"
               class="{{ Route::is('admin.twilio.*') ? 'active' : '' }}">
                <i class="fas fa-message"></i>
                <span class="nav-text ms-2">SMS Settings</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.phone-pool.index') }}"
               class="{{ Route::is('admin.phone-pool.*') ? 'active' : '' }}">
                <i class="fas fa-phone-volume"></i>
                <span class="nav-text ms-2">Phone Pool</span>
            </a>
        </li>

        @if($isAdmin)
        <li class="nav-item mt-4 border-top pt-3">
            <a href="{{ route('admin.clear.cache') }}"
               onclick="return confirm('Clear all cache?')">
                <i class="fas fa-broom"></i>
                <span class="nav-text ms-2">Clear Cache</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.storage.link') }}"
               onclick="return confirm('Link storage? This restores access to uploaded images.')">
                <i class="fas fa-link"></i>
                <span class="nav-text ms-2">Link Storage</span>
            </a>
        </li>
        @endif
        @endif

        <li class="nav-item {{ ($isAdmin || $isCoo) ? '' : 'mt-4 border-top pt-3' }}">
            <a href="{{ route('frontend.home') }}" target="_blank">
                <i class="fas fa-arrow-up-right-from-square"></i>
                <span class="nav-text ms-2">View Site</span>
            </a>
        </li>

    </ul>
</nav>
