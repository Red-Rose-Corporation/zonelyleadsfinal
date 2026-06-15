@extends('layouts.admin2')
@section('title', 'Dashboard')

@section('content')
<div class="mt-5 pt-3">

{{-- ── Page Header ──────────────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Dashboard Overview</h4>
        <p class="text-muted small mb-0">{{ now()->format('l, F j, Y') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.leads') }}" class="btn btn-sm btn-primary px-3">
            <i class="fas fa-bolt me-1"></i> Leads
        </a>
        <a href="{{ route('admin.profiles.index', ['status'=>'unverified']) }}" class="btn btn-sm btn-warning px-3">
            <i class="fas fa-user-check me-1"></i> Verify
            @if($unverified > 0)<span class="badge bg-dark ms-1">{{ $unverified }}</span>@endif
        </a>
    </div>
</div>

{{-- ── KPI Strip ─────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">

    {{-- Revenue --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="section-card p-4 h-100" style="border-left:4px solid #10b981">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small fw-semibold mb-1 text-uppercase" style="font-size:11px;letter-spacing:.06em">Revenue Collected</p>
                    <h3 class="fw-black mb-0" style="font-size:1.75rem">${{ number_format($revenue, 0) }}</h3>
                    <p class="text-muted small mt-1 mb-0">${{ number_format($pendingRev, 0) }} pending</p>
                </div>
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:#10b98115;flex-shrink:0">
                    <i class="fas fa-dollar-sign" style="color:#10b981;font-size:1.1rem"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Leads --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="section-card p-4 h-100" style="border-left:4px solid #ef4444">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small fw-semibold mb-1 text-uppercase" style="font-size:11px;letter-spacing:.06em">Total Leads</p>
                    <h3 class="fw-black mb-0" style="font-size:1.75rem">{{ number_format($totalLeads) }}</h3>
                    <p class="text-muted small mt-1 mb-0">{{ number_format($newLeads) }} new · {{ number_format($wonLeads) }} won</p>
                </div>
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:#ef444415;flex-shrink:0">
                    <i class="fas fa-bolt" style="color:#ef4444;font-size:1.1rem"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Sellers --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="section-card p-4 h-100" style="border-left:4px solid #0ea5e9">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small fw-semibold mb-1 text-uppercase" style="font-size:11px;letter-spacing:.06em">Sellers</p>
                    <h3 class="fw-black mb-0" style="font-size:1.75rem">{{ number_format($sellers) }}</h3>
                    <p class="text-muted small mt-1 mb-0">{{ number_format($buyers) }} buyers · {{ number_format($unverified) }} pending</p>
                </div>
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:#0ea5e915;flex-shrink:0">
                    <i class="fas fa-user-tie" style="color:#0ea5e9;font-size:1.1rem"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Affiliate --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="section-card p-4 h-100" style="border-left:4px solid #f59e0b">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small fw-semibold mb-1 text-uppercase" style="font-size:11px;letter-spacing:.06em">Comm. Pending</p>
                    <h3 class="fw-black mb-0" style="font-size:1.75rem">${{ number_format($pendingComm, 0) }}</h3>
                    <p class="text-muted small mt-1 mb-0">${{ number_format($paidComm, 0) }} paid out</p>
                </div>
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:#f59e0b15;flex-shrink:0">
                    <i class="fas fa-share-nodes" style="color:#f59e0b;font-size:1.1rem"></i>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Secondary Stats Row ───────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    @php
    $statCards = [
        ['label'=>'Buyers',      'val'=>$buyers,    'icon'=>'fa-users',         'color'=>'#10b981', 'route'=>route('admin.profiles.index',['type'=>'user'])],
        ['label'=>'Pending',     'val'=>$unverified,'icon'=>'fa-user-clock',     'color'=>'#f59e0b', 'route'=>route('admin.profiles.index',['status'=>'unverified'])],
        ['label'=>'Staff',       'val'=>$staffCount,'icon'=>'fa-sitemap',        'color'=>'#8b5cf6', 'route'=>route('admin.hierarchy')],
        ['label'=>'New Leads',   'val'=>$newLeads,  'icon'=>'fa-star',           'color'=>'#06b6d4', 'route'=>route('admin.leads',['status'=>'new'])],
        ['label'=>'Blog Posts',  'val'=>$blogCount, 'icon'=>'fa-pen-nib',        'color'=>'#6366f1', 'route'=>route('admin.blogs.index')],
        ['label'=>'Categories',  'val'=>$catCount,  'icon'=>'fa-tags',           'color'=>'#14b8a6', 'route'=>route('admin.categories.index')],
        ['label'=>'Cities',      'val'=>$cityCount, 'icon'=>'fa-city',           'color'=>'#94a3b8', 'route'=>route('admin.locations',['tab'=>'cities'])],
    ];
    @endphp
    @foreach($statCards as $s)
    <div class="col-6 col-sm-4 col-lg-3 col-xl" style="min-width:130px">
        <a href="{{ $s['route'] }}" class="section-card p-3 d-flex align-items-center gap-3 text-decoration-none h-100">
            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                 style="width:40px;height:40px;background:{{ $s['color'] }}18">
                <i class="fas {{ $s['icon'] }}" style="color:{{ $s['color'] }};font-size:.95rem"></i>
            </div>
            <div>
                <div class="fw-black text-dark" style="font-size:1.25rem;line-height:1">{{ number_format($s['val']) }}</div>
                <div class="text-muted" style="font-size:11px;font-weight:600">{{ $s['label'] }}</div>
            </div>
        </a>
    </div>
    @endforeach
</div>

{{-- ── Charts ────────────────────────────────────────────────────── --}}
<div class="row g-4 mb-4">

    <div class="col-lg-8">
        <div class="section-card h-100">
            <div class="card-body p-4 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-0">Activity</h6>
                    <p class="text-muted small mb-0">Leads &amp; new users — last 6 months</p>
                </div>
            </div>
            <div class="card-body p-4">
                <canvas id="activityChart" height="110"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="section-card h-100">
            <div class="card-body p-4 border-bottom">
                <h6 class="fw-bold mb-0">Lead Status</h6>
                <p class="text-muted small mb-0">Distribution by outcome</p>
            </div>
            <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center">
                <canvas id="leadStatusChart" style="max-height:200px"></canvas>
                <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
                    @foreach(['New'=>['#06b6d4',$leadStatusData['new']],'Pending'=>['#f59e0b',$leadStatusData['pending']],'Won'=>['#10b981',$leadStatusData['won']],'Lost'=>['#ef4444',$leadStatusData['lost']]] as $lbl=>$d)
                    <span class="badge rounded-pill" style="background:{{ $d[0] }}18;color:{{ $d[0] }};border:1px solid {{ $d[0] }}40;font-size:11px;font-weight:700">
                        {{ $lbl }}: {{ $d[1] }}
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Recent Leads + Pending Verify ───────────────────────────── --}}
<div class="row g-4 mb-4">

    <div class="col-lg-6">
        <div class="section-card h-100">
            <div class="card-body p-4 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-0"><i class="fas fa-bolt text-danger me-2" style="font-size:.8rem"></i>Recent Leads</h6>
                    <p class="text-muted small mb-0">Latest incoming leads</p>
                </div>
                <a href="{{ route('admin.leads') }}" class="btn btn-sm btn-outline-secondary px-3" style="font-size:12px">View All</a>
            </div>
            <div class="card-body p-0">
                @if($recentLeads->count())
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-2">Contact</th>
                            <th class="py-2">Seller</th>
                            <th class="py-2 text-center">Fee</th>
                            <th class="pe-4 py-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentLeads as $lead)
                        @php $sc = match($lead->status){'won'=>'bg-success','lost'=>'bg-danger','pending'=>'bg-warning text-dark',default=>'bg-primary'}; @endphp
                        <tr>
                            <td class="ps-4 py-2">
                                <div class="fw-semibold">{{ Str::limit($lead->name, 20) }}</div>
                                <div class="text-muted" style="font-size:11px">{{ $lead->created_at?->format('M d') }}</div>
                            </td>
                            <td class="py-2 text-muted">{{ Str::limit($lead->seller?->name ?? '—', 18) }}</td>
                            <td class="py-2 text-center fw-bold">${{ number_format($lead->fee, 0) }}</td>
                            <td class="pe-4 py-2 text-center"><span class="badge {{ $sc }}">{{ ucfirst($lead->status) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-5 text-muted small">
                    <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>No leads yet.
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="section-card h-100">
            <div class="card-body p-4 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-user-check me-2" style="font-size:.8rem;color:#f59e0b"></i>Pending Verification
                        @if($unverified > 0)<span class="badge bg-warning text-dark ms-1" style="font-size:10px">{{ $unverified }}</span>@endif
                    </h6>
                    <p class="text-muted small mb-0">Sellers awaiting approval</p>
                </div>
                <a href="{{ route('admin.profiles.index',['status'=>'unverified']) }}" class="btn btn-sm btn-outline-secondary px-3" style="font-size:12px">View All</a>
            </div>
            <div class="card-body p-0">
                @if($pendingVerify->count())
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-2">Seller</th>
                            <th class="py-2">City</th>
                            <th class="py-2">Joined</th>
                            <th class="pe-4 py-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingVerify as $s)
                        <tr>
                            <td class="ps-4 py-2">
                                <div class="fw-semibold">{{ Str::limit($s->name, 20) }}</div>
                                <div class="text-muted" style="font-size:11px">{{ Str::limit($s->designation ?? $s->email, 22) }}</div>
                            </td>
                            <td class="py-2 text-muted">{{ $s->city ?? '—' }}</td>
                            <td class="py-2 text-muted">{{ $s->created_at?->format('M d') }}</td>
                            <td class="pe-4 py-2 text-center">
                                <a href="{{ route('admin.profiles.edit', $s->id) }}" class="btn btn-sm btn-outline-success px-3" style="font-size:11px">
                                    Verify
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-5 text-muted small">
                    <i class="fas fa-check-circle text-success fa-2x mb-2 d-block"></i>All sellers verified!
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- ── Recent Sellers ────────────────────────────────────────────── --}}
<div class="section-card mb-4">
    <div class="card-body p-4 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h6 class="fw-bold mb-0"><i class="fas fa-user-tie me-2" style="font-size:.8rem;color:#0ea5e9"></i>Recently Joined Sellers</h6>
            <p class="text-muted small mb-0">Newest sellers on the platform</p>
        </div>
        <a href="{{ route('admin.profiles.index',['type'=>'seller']) }}" class="btn btn-sm btn-outline-secondary px-3" style="font-size:12px">View All</a>
    </div>
    <div class="card-body p-0">
        @if($recentSellers->count())
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-2">Seller</th>
                        <th class="py-2">Email</th>
                        <th class="py-2">City</th>
                        <th class="py-2">Category</th>
                        <th class="py-2 text-center">Status</th>
                        <th class="py-2">Joined</th>
                        <th class="pe-4 py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSellers as $seller)
                    <tr>
                        <td class="ps-4 py-2">
                            <div class="d-flex align-items-center gap-2">
                                @if($seller->profile_photo)
                                <img src="{{ get_file($seller->profile_photo, 'user') }}"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($seller->name) }}&size=32&background=0ea5e9&color=fff'"
                                     class="rounded-circle" width="32" height="32" style="object-fit:cover;flex-shrink:0">
                                @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                     style="width:32px;height:32px;font-size:12px">
                                    {{ strtoupper(substr($seller->name, 0, 1)) }}
                                </div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $seller->name }}</div>
                                    <div class="text-muted" style="font-size:11px">{{ $seller->designation ?? $seller->title ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-2 text-muted">{{ $seller->email }}</td>
                        <td class="py-2">{{ $seller->city ?? '—' }}</td>
                        <td class="py-2 text-muted">{{ $seller->category?->title ?? '—' }}</td>
                        <td class="py-2 text-center">
                            <span class="badge {{ $seller->status ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $seller->status ? 'Verified' : 'Pending' }}
                            </span>
                        </td>
                        <td class="py-2 text-muted">{{ $seller->created_at?->format('M d, Y') }}</td>
                        <td class="pe-4 py-2">
                            <a href="{{ route('admin.profiles.edit', $seller->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted small">
            <i class="fas fa-user-slash fa-2x mb-3 d-block opacity-25"></i>No sellers yet.
        </div>
        @endif
    </div>
</div>

{{-- ── Bottom: Affiliate + Hierarchy ───────────────────────────── --}}
<div class="row g-4 mb-4">

    <div class="col-lg-6">
        <div class="section-card h-100">
            <div class="card-body p-4 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-0"><i class="fas fa-share-nodes me-2" style="font-size:.8rem;color:#10b981"></i>Recent Affiliate</h6>
                    <p class="text-muted small mb-0">Latest commission activity</p>
                </div>
                <a href="{{ route('admin.affiliate') }}" class="btn btn-sm btn-outline-secondary px-3" style="font-size:12px">View All</a>
            </div>
            <div class="card-body p-0">
                @if($recentCommissions->count())
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-2">Referrer</th>
                            <th class="py-2 text-center">Amount</th>
                            <th class="py-2 text-center">Status</th>
                            <th class="pe-4 py-2">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentCommissions as $c)
                        <tr>
                            <td class="ps-4 py-2 fw-semibold">{{ $c->referrer?->name ?? '—' }}</td>
                            <td class="py-2 text-center fw-bold">${{ number_format($c->amount, 2) }}</td>
                            <td class="py-2 text-center">
                                <span class="badge {{ $c->status==='paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ ucfirst($c->status) }}
                                </span>
                            </td>
                            <td class="pe-4 py-2 text-muted">{{ $c->created_at?->format('M d') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-5 text-muted small">
                    <i class="fas fa-share-nodes fa-2x mb-2 d-block opacity-25"></i>No commissions yet.
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="section-card h-100">
            <div class="card-body p-4 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-0"><i class="fas fa-sitemap me-2" style="font-size:.8rem;color:#8b5cf6"></i>Hierarchy Summary</h6>
                    <p class="text-muted small mb-0">Regional management overview</p>
                </div>
                <a href="{{ route('admin.hierarchy') }}" class="btn btn-sm btn-outline-secondary px-3" style="font-size:12px">Manage</a>
            </div>
            <div class="card-body p-4">
                @php
                $hier = [
                    'area_manager'     => ['label'=>'Area Managers',     'color'=>'#0ea5e9','icon'=>'fa-map-pin'],
                    'city_manager'     => ['label'=>'City Managers',     'color'=>'#8b5cf6','icon'=>'fa-city'],
                    'district_manager' => ['label'=>'District Managers', 'color'=>'#f59e0b','icon'=>'fa-map'],
                    'country_manager'  => ['label'=>'Country Managers',  'color'=>'#ef4444','icon'=>'fa-flag'],
                ];
                @endphp
                <div class="row g-3">
                    @foreach($hier as $role => $info)
                    @php $cnt = $staffRoleCounts[$role] ?? 0; @endphp
                    <div class="col-6">
                        <a href="{{ route('admin.hierarchy',['role'=>$role]) }}"
                           class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none"
                           style="border:1px solid {{ $info['color'] }}25;background:{{ $info['color'] }}08">
                            <div class="rounded-3 d-flex align-items-center justify-content-center"
                                 style="width:40px;height:40px;background:{{ $info['color'] }}18;flex-shrink:0">
                                <i class="fas {{ $info['icon'] }}" style="color:{{ $info['color'] }}"></i>
                            </div>
                            <div>
                                <div class="fw-black" style="color:{{ $info['color'] }};font-size:1.25rem;line-height:1">{{ $cnt }}</div>
                                <div class="text-muted" style="font-size:11px;font-weight:600">{{ $info['label'] }}</div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 p-3 rounded-3 text-center" style="background:#f8fafc;border:1px solid #e2e8f0">
                    <div class="text-muted small mb-1" style="font-size:11px">CEO / Founder</div>
                    <span class="badge bg-dark"><i class="fas fa-crown me-1"></i>Zonely HQ</span>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Quick Actions ─────────────────────────────────────────────── --}}
<div class="section-card mb-2">
    <div class="card-body p-4 border-bottom">
        <h6 class="fw-bold mb-0">Quick Actions</h6>
        <p class="text-muted small mb-0">Shortcuts to common tasks</p>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            @php
            $actions = [
                ['route'=>route('admin.profiles.index',['status'=>'unverified']), 'icon'=>'fa-user-check', 'color'=>'#f59e0b', 'label'=>'Verify Sellers',   'badge'=>$unverified.' pending'],
                ['route'=>route('admin.leads'),                                   'icon'=>'fa-bolt',       'color'=>'#ef4444', 'label'=>'Lead Dashboard',   'badge'=>$newLeads.' new'],
                ['route'=>route('admin.affiliate'),                               'icon'=>'fa-share-nodes','color'=>'#10b981', 'label'=>'Affiliate',         'badge'=>'$'.number_format($pendingComm,0).' pending'],
                ['route'=>route('admin.hierarchy'),                               'icon'=>'fa-sitemap',    'color'=>'#8b5cf6', 'label'=>'Hierarchy',         'badge'=>$staffCount.' staff'],
                ['route'=>route('admin.blogs.create'),                            'icon'=>'fa-pen',        'color'=>'#6366f1', 'label'=>'New Blog Post',     'badge'=>'Write'],
                ['route'=>route('admin.categories.index'),                        'icon'=>'fa-tags',       'color'=>'#14b8a6', 'label'=>'Categories',        'badge'=>$catCount.' cats'],
                ['route'=>route('admin.locations'),                               'icon'=>'fa-map-marked-alt','color'=>'#94a3b8','label'=>'Locations',       'badge'=>$cityCount.' cities'],
                ['route'=>route('admin.clear.cache'),                             'icon'=>'fa-broom',      'color'=>'#64748b', 'label'=>'Clear Cache',       'badge'=>'Run', 'confirm'=>true],
            ];
            @endphp
            @foreach($actions as $a)
            <div class="col-6 col-md-3">
                <a href="{{ $a['route'] }}"
                   class="d-flex flex-column align-items-center justify-content-center gap-2 p-3 rounded-3 text-decoration-none text-center"
                   style="border:1px solid {{ $a['color'] }}25;background:{{ $a['color'] }}07;transition:background .15s"
                   {!! isset($a['confirm']) ? "onclick=\"return confirm('Clear all cache?')\"" : '' !!}
                   onmouseover="this.style.background='{{ $a['color'] }}14'" onmouseout="this.style.background='{{ $a['color'] }}07'">
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width:40px;height:40px;background:{{ $a['color'] }}18">
                        <i class="fas {{ $a['icon'] }}" style="color:{{ $a['color'] }}"></i>
                    </div>
                    <div class="fw-bold text-dark small">{{ $a['label'] }}</div>
                    <span class="badge rounded-pill" style="background:{{ $a['color'] }}18;color:{{ $a['color'] }};border:1px solid {{ $a['color'] }}30;font-size:10px">
                        {{ $a['badge'] }}
                    </span>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const gridColor = 'rgba(0,0,0,.05)';

new Chart(document.getElementById('activityChart'), {
    type: 'line',
    data: {
        labels: @json($leadMonths),
        datasets: [
            {
                label: 'Leads',
                data: @json($leadCounts),
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239,68,68,.08)',
                tension: 0.4, fill: true, pointRadius: 4, pointHoverRadius: 6,
            },
            {
                label: 'New Users',
                data: @json($userCounts),
                borderColor: '#0ea5e9',
                backgroundColor: 'rgba(14,165,233,.08)',
                tension: 0.4, fill: true, pointRadius: 4, pointHoverRadius: 6,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top', labels: { usePointStyle: true, padding: 16, font: { size: 12 } } } },
        scales: {
            x: { grid: { color: gridColor } },
            y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: gridColor } }
        }
    }
});

new Chart(document.getElementById('leadStatusChart'), {
    type: 'doughnut',
    data: {
        labels: ['New', 'Pending', 'Won', 'Lost'],
        datasets: [{
            data: [{{ $leadStatusData['new'] }},{{ $leadStatusData['pending'] }},{{ $leadStatusData['won'] }},{{ $leadStatusData['lost'] }}],
            backgroundColor: ['#06b6d4','#f59e0b','#10b981','#ef4444'],
            borderWidth: 3,
            borderColor: '#fff',
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        cutout: '68%',
    }
});
</script>
@endsection
