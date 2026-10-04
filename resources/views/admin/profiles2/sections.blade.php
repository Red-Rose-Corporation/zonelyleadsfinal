@extends('layouts.admin2')
@section('title', 'Profile Sections — ' . $user->name)


@section('content')
@php
    $pricingTypes = [
        'starting_at' => 'Starting at',
        'per_month'   => 'Per month',
        'per_hour'    => 'Per hour',
        'flat_rate'   => 'Flat rate',
        'free'        => 'Free',
        'contact'     => 'Negotiable (no price shown)',
    ];
    $priceLabel = function ($svc) use ($pricingTypes) {
        if ($svc->pricing_type === 'free') return 'Free';
        if ($svc->pricing_type === 'contact' || !$svc->price) return 'Negotiable';
        return '$' . number_format((float) $svc->price, 0) . ' · ' . ($pricingTypes[$svc->pricing_type] ?? 'Starting at');
    };
@endphp
<div class="mt-5 pt-4">

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h4 class="mb-0 fw-bold">Profile Sections</h4>
            <p class="text-muted small mb-0">{{ $user->name }} &mdash; edit what appears on this seller's public profile, no login needed.</p>
        </div>
        <div class="d-flex gap-2">
            @if($user->slug)
            <a href="{{ route('frontend.service.show', $user->slug) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-up-right-from-square me-1"></i> View public page
            </a>
            @endif
            <a href="{{ route('admin.profiles.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Edit User
            </a>
        </div>
    </div>

    <div class="d-flex gap-2 flex-wrap mb-4">
        <a href="#services" class="btn btn-sm btn-light border"><i class="fas fa-briefcase me-1"></i> Services &amp; Pricing ({{ $services->count() }})</a>
        <a href="#faqs" class="btn btn-sm btn-light border"><i class="fas fa-circle-question me-1"></i> FAQs ({{ $faqs->count() }})</a>
        @foreach($itemSections as $k => $sec)
        <a href="#items-{{ $k }}" class="btn btn-sm btn-light border"><i class="fas {{ $sec['def']['icon'] }} me-1"></i> {{ $sec['def']['title'] }} ({{ $sec['items']->count() }})</a>
        @endforeach
        <a href="#photo" class="btn btn-sm btn-light border"><i class="fas fa-image me-1"></i> Profile Photo</a>
    </div>

    {{-- Validation errors and flash messages are rendered by the admin layout. --}}

    {{-- ===================================================== SERVICES & PRICING --}}
    <div class="section-card mb-4" id="services">
        <div class="card-header bg-dark text-white p-3">
            <h6 class="mb-0"><i class="fas fa-briefcase me-2"></i>Services &amp; Pricing</h6>
        </div>
        <div class="card-body p-4">
            <p class="text-muted small">Shown on the public profile under "Services &amp; Pricing". Only <strong>visible</strong> services appear. Enter real prices provided by the professional; use "Negotiable" when no price is agreed.</p>

            @if(!$user->category_id)
            <div class="alert alert-warning small">
                <i class="fas fa-triangle-exclamation me-1"></i>
                This seller has no Category yet. Set one in <a href="{{ route('admin.profiles.edit', $user->id) }}">Edit User &rarr; Business / Seller Info</a> before adding services.
            </div>
            @endif

            @forelse($services as $svc)
            <div class="border rounded-3 mb-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-3">
                    <div>
                        <div class="fw-semibold">{{ $svc->title }}</div>
                        <div class="small text-muted">{{ $priceLabel($svc) }}</div>
                    </div>
                    <span class="badge {{ $svc->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $svc->is_active ? 'Visible' : 'Hidden' }}</span>
                </div>

                <details class="border-top" {{ old('_form') === 'service-'.$svc->id ? 'open' : '' }}>
                    <summary class="px-3 py-2 small fw-semibold text-primary" style="cursor:pointer">Edit this service</summary>
                    @php $o = old('_form') === 'service-'.$svc->id; @endphp
                    <form method="POST" action="{{ route('admin.profiles.sections.services.update', [$user->id, $svc->id]) }}" class="p-3 pt-2">
                        @csrf @method('PUT')
                        <input type="hidden" name="_form" value="service-{{ $svc->id }}">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Service name</label>
                                <input type="text" name="title" class="form-control" required maxlength="255"
                                       value="{{ $o ? old('title') : $svc->title }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Price ($)</label>
                                <input type="number" name="price" class="form-control" min="0" step="0.01"
                                       value="{{ $o ? old('price') : $svc->price }}" placeholder="e.g. 250">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pricing type</label>
                                <select name="pricing_type" class="form-select">
                                    @foreach($pricingTypes as $val => $lbl)
                                    <option value="{{ $val }}" {{ ($o ? old('pricing_type') : $svc->pricing_type) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Included points <span class="text-muted fw-normal">(one per line, shown as check marks)</span></label>
                                <textarea name="features" rows="4" class="form-control" maxlength="3000">{{ $o ? old('features') : $svc->features }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Description <span class="text-muted fw-normal">(optional)</span></label>
                                <textarea name="description" rows="3" class="form-control" maxlength="2000">{{ $o ? old('description') : $svc->description }}</textarea>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="svcActive{{ $svc->id }}"
                                           {{ ($o ? old('is_active') : $svc->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="svcActive{{ $svc->id }}">Visible on public profile</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm mt-3"><i class="fas fa-save me-1"></i> Save service</button>
                    </form>
                </details>

                <div class="d-flex gap-2 p-3 pt-0 border-top bg-light bg-opacity-50">
                    <form method="POST" action="{{ route('admin.profiles.sections.services.toggle', [$user->id, $svc->id]) }}" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                            <i class="fas {{ $svc->is_active ? 'fa-eye-slash' : 'fa-eye' }} me-1"></i>{{ $svc->is_active ? 'Hide' : 'Show' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.profiles.sections.services.destroy', [$user->id, $svc->id]) }}" class="mt-2"
                          onsubmit="return confirm('Delete this service? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1"></i>Delete</button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-muted mb-3">No services yet. This seller's public page shows no pricing until you add one.</p>
            @endforelse

            @php $a = old('_form') === 'service-new'; @endphp
            <details class="border rounded-3" {{ $a ? 'open' : '' }}>
                <summary class="px-3 py-2 fw-semibold text-success" style="cursor:pointer"><i class="fas fa-plus me-1"></i> Add a service</summary>
                <form method="POST" action="{{ route('admin.profiles.sections.services.store', $user->id) }}" class="p-3 pt-2">
                    @csrf
                    <input type="hidden" name="_form" value="service-new">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Service name</label>
                            <input type="text" name="title" class="form-control" required maxlength="255"
                                   value="{{ $a ? old('title') : '' }}" placeholder="e.g. Initial consultation">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Price ($)</label>
                            <input type="number" name="price" class="form-control" min="0" step="0.01"
                                   value="{{ $a ? old('price') : '' }}" placeholder="e.g. 250">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pricing type</label>
                            <select name="pricing_type" class="form-select">
                                @foreach($pricingTypes as $val => $lbl)
                                <option value="{{ $val }}" {{ ($a ? old('pricing_type', 'starting_at') : 'starting_at') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Included points <span class="text-muted fw-normal">(one per line, shown as check marks)</span></label>
                            <textarea name="features" rows="4" class="form-control" maxlength="3000">{{ $a ? old('features') : '' }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description <span class="text-muted fw-normal">(optional)</span></label>
                            <textarea name="description" rows="3" class="form-control" maxlength="2000">{{ $a ? old('description') : '' }}</textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="svcActiveNew"
                                       {{ ($a ? old('is_active') : true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="svcActiveNew">Visible on public profile</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm mt-3" {{ $user->category_id ? '' : 'disabled' }}>
                        <i class="fas fa-plus me-1"></i> Add service
                    </button>
                </form>
            </details>
        </div>
    </div>

    {{-- ============================================================== FAQs --}}
    <div class="section-card mb-4" id="faqs">
        <div class="card-header bg-primary text-white p-3">
            <h6 class="mb-0"><i class="fas fa-circle-question me-2"></i>FAQs</h6>
        </div>
        <div class="card-body p-4">
            <p class="text-muted small">Questions and answers shown on the public profile (and in its search-result markup). Write accurate answers only.</p>

            @forelse($faqs as $faq)
            @php $o = old('_form') === 'faq-'.$faq->id; @endphp
            <div class="border rounded-3 mb-3">
                <div class="p-3">
                    <div class="fw-semibold">{{ $faq->question }}</div>
                    <div class="small text-muted">{{ \Illuminate\Support\Str::limit($faq->answer, 140) }}</div>
                </div>
                <details class="border-top" {{ $o ? 'open' : '' }}>
                    <summary class="px-3 py-2 small fw-semibold text-primary" style="cursor:pointer">Edit this question</summary>
                    <form method="POST" action="{{ route('admin.profiles.sections.faqs.update', [$user->id, $faq->id]) }}" class="p-3 pt-2">
                        @csrf @method('PUT')
                        <input type="hidden" name="_form" value="faq-{{ $faq->id }}">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Question</label>
                            <input type="text" name="question" class="form-control" required maxlength="500"
                                   value="{{ $o ? old('question') : $faq->question }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Answer</label>
                            <textarea name="answer" rows="4" class="form-control" required maxlength="2000">{{ $o ? old('answer') : $faq->answer }}</textarea>
                        </div>
                        <div class="mb-3" style="max-width:160px">
                            <label class="form-label fw-semibold">Order</label>
                            <input type="number" name="sort_order" class="form-control" min="0"
                                   value="{{ $o ? old('sort_order') : $faq->sort_order }}">
                        </div>
                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save me-1"></i> Save question</button>
                    </form>
                </details>
                <div class="p-3 pt-0 border-top bg-light bg-opacity-50">
                    <form method="POST" action="{{ route('admin.profiles.sections.faqs.destroy', [$user->id, $faq->id]) }}" class="mt-2"
                          onsubmit="return confirm('Delete this question? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1"></i>Delete</button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-muted mb-3">No FAQs yet.</p>
            @endforelse

            @php $f = old('_form') === 'faq-new'; @endphp
            <details class="border rounded-3" {{ $f ? 'open' : '' }}>
                <summary class="px-3 py-2 fw-semibold text-success" style="cursor:pointer"><i class="fas fa-plus me-1"></i> Add a question</summary>
                <form method="POST" action="{{ route('admin.profiles.sections.faqs.store', $user->id) }}" class="p-3 pt-2">
                    @csrf
                    <input type="hidden" name="_form" value="faq-new">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Question</label>
                        <input type="text" name="question" class="form-control" required maxlength="500" value="{{ $f ? old('question') : '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Answer</label>
                        <textarea name="answer" rows="4" class="form-control" required maxlength="2000">{{ $f ? old('answer') : '' }}</textarea>
                    </div>
                    <div class="mb-3" style="max-width:160px">
                        <label class="form-label fw-semibold">Order</label>
                        <input type="number" name="sort_order" class="form-control" min="0" value="{{ $f ? old('sort_order', 0) : 0 }}">
                    </div>
                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-plus me-1"></i> Add question</button>
                </form>
            </details>
        </div>
    </div>

    {{-- ============================ Experience, education, certifications, memberships, languages, contacts --}}
    @foreach($itemSections as $k => $sec)
        @include('admin.profiles2._item_section', ['user' => $user, 'key' => $k, 'def' => $sec['def'], 'items' => $sec['items']])
    @endforeach

    {{-- ============================================================ PHOTO --}}
    <div class="section-card mb-5" id="photo">
        <div class="card-header bg-secondary text-white p-3">
            <h6 class="mb-0"><i class="fas fa-image me-2"></i>Profile Photo</h6>
        </div>
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-4 flex-wrap">
                @if($user->profile_photo)
                <img src="{{ get_file($user->profile_photo, 'user') }}"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=120&background=0ea5e9&color=fff'"
                     class="rounded-circle" width="96" height="96" style="object-fit:cover" alt="Current profile photo">
                @else
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                     style="width:96px;height:96px;font-size:34px">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                @endif

                <div style="flex:1 1 220px;min-width:0">
                    <form method="POST" action="{{ route('admin.profiles.sections.photo', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        <label class="form-label fw-semibold">Upload a new photo</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <input type="file" name="profile_photo" class="form-control" style="max-width:340px" accept="image/png,image/jpeg,image/webp,image/gif" required>
                            <button type="submit" class="btn btn-success"><i class="fas fa-upload me-1"></i> Upload</button>
                        </div>
                        <div class="form-text">JPG, PNG, WebP or GIF, up to 10&nbsp;MB. It is resized automatically. Use a photo the professional has approved.</div>
                    </form>
                    @if($user->profile_photo)
                    <form method="POST" action="{{ route('admin.profiles.sections.photo.destroy', $user->id) }}" class="mt-2"
                          onsubmit="return confirm('Remove this profile photo?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1"></i> Remove photo</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
