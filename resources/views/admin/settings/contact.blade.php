@extends('layouts.admin2')
@section('title', 'Contact Settings')

@section('content')
<div class="mt-5 pt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold">Contact Settings</h4>
            <p class="text-muted small mb-0">Support contact details shown in seller emails, suspension notices, and the site footer.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="section-card">
                <div class="card-header bg-dark text-white p-4">
                    <h5 class="mb-0"><i class="fas fa-headset me-2"></i>Support Contact Details</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.settings.contact.update') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Team / Sender Name</label>
                            <input type="text" name="support_name" class="form-control @error('support_name') is-invalid @enderror"
                                   value="{{ old('support_name', $settings['support_name']) }}"
                                   placeholder="e.g. Zonely Admin Team">
                            <div class="form-text">Shown as the sign-off name in emails sent to sellers.</div>
                            @error('support_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Support Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                                <input type="email" name="support_email" class="form-control @error('support_email') is-invalid @enderror"
                                       value="{{ old('support_email', $settings['support_email']) }}"
                                       placeholder="support@zonely.com" required>
                            </div>
                            <div class="form-text">Sellers will contact you at this address when their account is suspended.</div>
                            @error('support_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">WhatsApp Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fab fa-whatsapp text-success"></i></span>
                                <input type="text" name="support_whatsapp" class="form-control @error('support_whatsapp') is-invalid @enderror"
                                       value="{{ old('support_whatsapp', $settings['support_whatsapp']) }}"
                                       placeholder="+1 234 567 8900">
                            </div>
                            <div class="form-text">Include country code. Example: +1 234 567 8900. Shown as a clickable button in seller notices and the site footer.</div>
                            @error('support_whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Preview --}}
                        @if($settings['support_email'] || $settings['support_whatsapp'])
                        <div class="alert alert-light border mb-4">
                            <p class="mb-2 small fw-semibold text-muted">CURRENTLY SHOWING ON SITE</p>
                            @if($settings['support_email'])
                            <div class="small mb-1"><i class="fas fa-envelope me-2 text-primary"></i>{{ $settings['support_email'] }}</div>
                            @endif
                            @if($settings['support_whatsapp'])
                            <div class="small"><i class="fab fa-whatsapp me-2 text-success"></i>{{ $settings['support_whatsapp'] }}</div>
                            @endif
                        </div>
                        @endif

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Save Settings
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- Info card --}}
            <div class="section-card mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Where these details appear</h6>
                    <ul class="list-unstyled mb-0 small text-muted">
                        <li class="mb-2"><i class="fas fa-envelope me-2 text-danger"></i><strong>Suspension email</strong> — sent automatically when you disable a seller account</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2 text-success"></i><strong>Reactivation email</strong> — sent automatically when you re-enable a seller account</li>
                        <li class="mb-2"><i class="fas fa-bell me-2 text-warning"></i><strong>Seller dashboard banner</strong> — shown to suspended sellers with click-to-contact buttons</li>
                        <li><i class="fas fa-globe me-2 text-info"></i><strong>Site footer</strong> — visible to all visitors on every page</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
