@extends('layouts.admin2')
@section('title', 'Add Panel Manager')

@section('content')
<div class="mt-5 pt-4">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.managers.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-bold">Add Panel Manager</h4>
            <p class="text-muted small mb-0">Create account + assign role and module access.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.managers.store') }}">
        @csrf

        {{-- Role Type --}}
        <div class="section-card mb-4">
            <div class="card-header bg-dark text-white p-4">
                <h6 class="mb-0"><i class="fas fa-user-tie me-2"></i>Role Type</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="d-flex align-items-start gap-3 p-3 rounded-3 border cursor-pointer role-option"
                               for="role_manager" style="cursor:pointer">
                            <input type="radio" name="role" id="role_manager" value="manager"
                                   class="form-check-input mt-1" checked onchange="toggleRole()">
                            <div>
                                <div class="fw-bold"><i class="fas fa-user-shield text-primary me-1"></i> Manager</div>
                                <div class="text-muted small mt-1">Restricted access — you select which sections this manager can view and manage.</div>
                            </div>
                        </label>
                    </div>
                    @if(auth()->user()->type !== 'coo')
                    <div class="col-md-6">
                        <label class="d-flex align-items-start gap-3 p-3 rounded-3 border cursor-pointer role-option"
                               for="role_general" style="cursor:pointer">
                            <input type="radio" name="role" id="role_general" value="general_manager"
                                   class="form-check-input mt-1" onchange="toggleRole()">
                            <div>
                                <div class="fw-bold"><i class="fas fa-crown text-warning me-1"></i> General Manager</div>
                                <div class="text-muted small mt-1">Full panel access automatically — no module selection needed. Cannot manage admin accounts.</div>
                            </div>
                        </label>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row g-4">

            {{-- Account Info --}}
            <div class="col-md-6">
                <div class="section-card h-100">
                    <div class="card-header bg-dark text-white p-4">
                        <h6 class="mb-0"><i class="fas fa-user me-2"></i>Account Details</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required minlength="6" autocomplete="new-password">
                            <div class="form-text">Min 6 characters.</div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">City</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city') }}" placeholder="New York">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">State</label>
                                <input type="text" name="state" class="form-control" value="{{ old('state') }}" placeholder="NY">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Country</label>
                                <input type="text" name="country" class="form-control" value="{{ old('country') }}" placeholder="USA">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Zip Code</label>
                                <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code') }}" placeholder="10001">
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Notes (internal)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="e.g. Hired for blog management...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Module Access --}}
            <div class="col-md-6" id="moduleSection">
                <div class="section-card h-100">
                    <div class="card-header bg-dark text-white p-4">
                        <h6 class="mb-0"><i class="fas fa-lock-open me-2"></i>Module Access</h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">Tick which admin sections this manager can access.</p>
                        <div class="d-flex flex-column gap-2">
                            @foreach($modules as $key => $info)
                            <label class="d-flex align-items-center gap-3 p-3 rounded-3 border cursor-pointer module-row"
                                   style="cursor:pointer" for="mod_{{ $key }}">
                                <input type="checkbox" name="modules[]" value="{{ $key }}" id="mod_{{ $key }}"
                                       class="form-check-input mt-0" style="width:18px;height:18px"
                                       {{ in_array($key, old('modules', [])) ? 'checked' : '' }}>
                                <div class="flex-1">
                                    <div class="fw-bold small d-flex align-items-center gap-2">
                                        <i class="fas {{ $info['icon'] }} text-primary" style="width:16px;text-align:center"></i>
                                        {{ $info['label'] }}
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('modules')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- General Manager notice (hidden by default) --}}
            <div class="col-md-6" id="generalNotice" style="display:none">
                <div class="section-card h-100">
                    <div class="card-header bg-warning text-dark p-4">
                        <h6 class="mb-0"><i class="fas fa-crown me-2"></i>General Manager — Full Access</h6>
                    </div>
                    <div class="card-body p-4 d-flex align-items-center justify-content-center text-center">
                        <div>
                            <i class="fas fa-unlock-alt fa-3x text-warning mb-3 opacity-75"></i>
                            <p class="fw-bold mb-1">All admin sections unlocked automatically</p>
                            <p class="text-muted small mb-0">No module selection needed. Admin and COO accounts remain protected.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary px-5">
                <i class="fas fa-save me-1"></i> Create
            </button>
            <a href="{{ route('admin.managers.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>

</div>

<script>
function toggleRole() {
    const isGeneral = document.getElementById('role_general').checked;
    document.getElementById('moduleSection').style.display  = isGeneral ? 'none' : '';
    document.getElementById('generalNotice').style.display  = isGeneral ? '' : 'none';
}
</script>
@endsection
