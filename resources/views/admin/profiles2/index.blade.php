@extends('layouts.admin2')

@section('title')
    {{ ucfirst($type ?? ($status ?? 'All')) }} Profiles
@endsection

@section('content')
    <div class="mt-5 pt-4">

        {{-- Seller Period Stats --}}
        <div class="kpi-grid mb-4" style="grid-template-columns:repeat(auto-fit,minmax(150px,1fr))">
            @foreach([
                ['val'=>$sellerStats['today'],   'label'=>'Joined Today',    'icon'=>'fa-user-plus',    'color'=>'#0ea5e9'],
                ['val'=>$sellerStats['week'],     'label'=>'This Week',       'icon'=>'fa-calendar-week','color'=>'#8b5cf6'],
                ['val'=>$sellerStats['month'],    'label'=>'This Month',      'icon'=>'fa-calendar-alt', 'color'=>'#10b981'],
                ['val'=>$sellerStats['year'],     'label'=>'This Year',       'icon'=>'fa-calendar',     'color'=>'#f59e0b'],
                ['val'=>$sellerStats['total'],    'label'=>'All Sellers',     'icon'=>'fa-users',        'color'=>'#06b6d4'],
                ['val'=>$sellerStats['disabled'], 'label'=>'Disabled',        'icon'=>'fa-user-slash',   'color'=>'#ef4444'],
            ] as $s)
            <div class="kpi-card" style="border-left-color:{{ $s['color'] }}">
                <h3>{{ $s['val'] }}</h3>
                <p><i class="fas {{ $s['icon'] }}"></i> {{ $s['label'] }}</p>
            </div>
            @endforeach
        </div>

        <!-- Page Header -->
        <div class="section-card">
            <div class="card-header bg-primary text-white p-4">
                <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        {{ ucfirst($type ?? ($status ?? 'All')) }} User Profiles
                        <span class="badge bg-white text-primary ms-2" style="font-size:12px">{{ $users->total() }}</span>
                    </h5>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <form method="GET" action="{{ route('admin.profiles.index') }}" class="d-flex gap-2">
                            @if($type)<input type="hidden" name="type" value="{{ $type }}">@endif
                            @if($status)<input type="hidden" name="status" value="{{ $status }}">@endif
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                   placeholder="Search name, email, phone..."
                                   class="form-control form-control-sm" style="min-width:220px;background:rgba(255,255,255,.15);border-color:rgba(255,255,255,.3);color:#fff"
                                   oninput="this.style.color='#fff'" onfocus="this.style.background='rgba(255,255,255,.25)'"
                                   onblur="this.style.background='rgba(255,255,255,.15)'">
                            <button class="btn btn-sm btn-light" type="submit"><i class="fas fa-search"></i></button>
                            @if(!empty($search))
                            <a href="{{ route('admin.profiles.index', array_filter(['type'=>$type,'status'=>$status])) }}"
                               class="btn btn-sm btn-outline-light"><i class="fas fa-times"></i></a>
                            @endif
                        </form>
                        <div class="btn-group">
                            <a href="{{ route('admin.profiles.index') }}"
                                class="btn btn-sm {{ !$type && !$status ? 'btn-light' : 'btn-outline-light' }}">All</a>
                            <a href="{{ route('admin.profiles.index', ['type' => 'seller']) }}"
                                class="btn btn-sm {{ $type == 'seller' ? 'btn-light' : 'btn-outline-light' }}">Sellers</a>
                            <a href="{{ route('admin.profiles.index', ['type' => 'user']) }}"
                                class="btn btn-sm {{ $type == 'user' ? 'btn-light' : 'btn-outline-light' }}">Buyers</a>
                            <a href="{{ route('admin.profiles.index', ['status' => 'verified']) }}"
                                class="btn btn-sm {{ ($status ?? '') == 'verified' ? 'btn-light' : 'btn-outline-light' }}">Verified</a>
                            <a href="{{ route('admin.profiles.index', ['status' => 'unverified']) }}"
                                class="btn btn-sm {{ ($status ?? '') == 'unverified' ? 'btn-light' : 'btn-outline-light' }}">Disabled</a>
                        </div>
                    </div>
                </div>
                @if(!empty($search))
                <div class="mt-2 text-white-50 small"><i class="fas fa-filter me-1"></i>Showing results for "{{ $search }}" — {{ $users->total() }} found</div>
                @endif
            </div>

            <div class="card-body p-4">

                @if ($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Email</th>
                                    <th>City</th>
                                    <th>Status</th>
                                    <th>Joined</th>
                                    <th width="10">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                    <tr>
                                        <td class="text-muted small">{{ ($users->currentPage()-1)*$users->perPage()+$loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($user->profile_photo)
                                                <img src="{{ str_starts_with($user->profile_photo, 'http') ? $user->profile_photo : asset($user->profile_photo) }}"
                                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=36&background=0ea5e9&color=fff'"
                                                     class="rounded-circle" width="36" height="36" style="object-fit:cover">
                                                @else
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                     style="width:36px;height:36px;font-size:13px;font-weight:700;flex-shrink:0">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $user->name }}</strong>
                                                    @if($user->designation ?? $user->title ?? false)
                                                    <div class="text-muted small">{{ $user->designation ?? $user->title }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $user->type === 'seller' ? 'bg-primary' : ($user->type === 'admin' ? 'bg-dark' : 'bg-secondary') }}">
                                                {{ ucfirst($user->type === 'user' ? 'buyer' : $user->type) }}
                                            </span>
                                        </td>
                                        <td class="small text-muted">{{ $user->email }}</td>
                                        <td class="small">{{ $user->city ?? '—' }}</td>
                                        <td>
                                            <span class="badge {{ $user->status ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ $user->status ? 'Active' : 'Disabled' }}
                                            </span>
                                        </td>
                                        <td class="small text-muted">{{ $user->created_at?->format('M d, Y') }}</td>
                                        {{-- <td>
                                        @if ($user->type === 'admin')
                                            <span class="text-muted">N/A</span>
                                        @else
                                            @if ($user->status)
                                                <a href="{{ route('admin.profiles.edit', $user->id) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('admin.profiles.edit', $user->id) }}"
                                                   class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            @endif

                                            <form action="{{ route('admin.profiles.destroy', $user->id) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td> --}}
                                        <td>
                                            @if ($user->type === 'admin')
                                                <span class="text-muted">N/A</span>
                                            @else
                                                <div class="d-flex align-items-center gap-1">
                                                    {{-- Quick verify (only for pending) --}}
                                                    @if(!$user->status)
                                                    <form method="POST" action="{{ route('admin.profiles.verify', $user->id) }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                                title="Verify now" onclick="return confirm('Verify {{ addslashes($user->name) }}?')">
                                                            <i class="fas fa-check me-1"></i> Verify
                                                        </button>
                                                    </form>
                                                    @endif

                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-light" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.profiles.edit', $user->id) }}">
                                                                    <i class="fas fa-edit me-2 text-primary"></i> Edit
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <form action="{{ route('admin.profiles.destroy', $user->id) }}"
                                                                    method="POST" onsubmit="return confirm('Delete user: ' + @json($user->name) + '?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger">
                                                                        <i class="fas fa-trash me-2"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($users->hasPages())
                    <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <span class="text-muted small">
                            Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }} users
                        </span>
                        {{ $users->links() }}
                    </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No profiles found.</p>
                    </div>
                @endif

            </div>
        </div>

    </div>
@endsection
