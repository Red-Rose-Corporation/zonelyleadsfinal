@extends('layouts.admin2')
@section('title', 'Platform Settings')

@section('content')
<div class="mt-5 pt-4">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div style="width:36px;height:36px;background:linear-gradient(135deg,#0f766e,#0d9488);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-sliders text-white" style="font-size:15px;"></i>
                </div>
                <h4 class="mb-0 fw-bold">Platform Settings</h4>
            </div>
            <p class="text-muted small mb-0 ms-1" style="padding-left:44px;">Manage contact details, social links, legal info, and branding shown across the site.</p>
        </div>
        <button type="submit" form="settingsForm" class="btn fw-bold px-4" style="background:linear-gradient(135deg,#0f766e,#0d9488);color:#fff;border-radius:10px;box-shadow:0 2px 8px rgba(15,118,110,.25);">
            <i class="fas fa-save me-2"></i>Save All Settings
        </button>
    </div>

    @if(session('success'))
    <div class="alert border-0 mb-4 d-flex align-items-center gap-3" style="background:#f0fdf9;border-left:4px solid #0d9488 !important;border-radius:10px;" role="alert">
        <div style="width:32px;height:32px;background:#0d9488;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-check text-white" style="font-size:13px;"></i>
        </div>
        <div class="flex-grow-1 small fw-semibold text-success mb-0">{{ session('success') }}</div>
        <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form id="settingsForm" method="POST" action="{{ route('admin.settings.contact.update') }}">
    @csrf
    <div class="row g-4">

        {{-- LEFT COLUMN --}}
        <div class="col-lg-8">

            {{-- CONTACT --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;overflow:hidden;">
                <div class="px-4 py-3 d-flex align-items-center gap-3" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                    <div style="width:34px;height:34px;background:linear-gradient(135deg,#1e293b,#334155);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-headset text-white" style="font-size:13px;"></i>
                    </div>
                    <div>
                        <p class="mb-0 fw-bold text-dark" style="font-size:14px;">Contact</p>
                        <p class="mb-0 text-muted" style="font-size:12px;">Used in seller emails, dashboard banners, and the site footer.</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Team / Sender Name</label>
                            <input type="text" name="support_name"
                                   class="form-control @error('support_name') is-invalid @enderror"
                                   value="{{ old('support_name', $settings['support_name']) }}"
                                   placeholder="Zonely Admin Team"
                                   style="border-radius:9px;">
                            <div class="form-text">Sign-off name used in automated emails.</div>
                            @error('support_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Support Email <span class="text-danger">*</span></label>
                            <div class="input-group" style="border-radius:9px;overflow:hidden;">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-envelope" style="color:#0d9488;"></i></span>
                                <input type="email" name="support_email"
                                       class="form-control border-start-0 @error('support_email') is-invalid @enderror"
                                       value="{{ old('support_email', $settings['support_email']) }}"
                                       placeholder="contact@zonelyleads.com" required>
                            </div>
                            @error('support_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">WhatsApp Number</label>
                            <div class="input-group" style="border-radius:9px;overflow:hidden;">
                                <span class="input-group-text bg-white border-end-0"><i class="fab fa-whatsapp" style="color:#22c55e;font-size:15px;"></i></span>
                                <input type="text" name="support_whatsapp"
                                       class="form-control border-start-0 @error('support_whatsapp') is-invalid @enderror"
                                       value="{{ old('support_whatsapp', $settings['support_whatsapp']) }}"
                                       placeholder="+1 234 567 8900">
                            </div>
                            <div class="form-text">Include country code. Shown as clickable button in emails and footer.</div>
                            @error('support_whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- SOCIAL MEDIA --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;overflow:hidden;">
                <div class="px-4 py-3 d-flex align-items-center gap-3" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                    <div style="width:34px;height:34px;background:linear-gradient(135deg,#1d4ed8,#3b82f6);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-share-nodes text-white" style="font-size:13px;"></i>
                    </div>
                    <div>
                        <p class="mb-0 fw-bold text-dark" style="font-size:14px;">Social Media</p>
                        <p class="mb-0 text-muted" style="font-size:12px;">Social icons in the site footer link to these URLs.</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Facebook URL</label>
                            <div class="input-group" style="border-radius:9px;overflow:hidden;">
                                <span class="input-group-text bg-white border-end-0"><i class="fab fa-facebook" style="color:#1877f2;font-size:15px;"></i></span>
                                <input type="url" name="social_facebook"
                                       class="form-control border-start-0 @error('social_facebook') is-invalid @enderror"
                                       value="{{ old('social_facebook', $settings['social_facebook']) }}"
                                       placeholder="https://facebook.com/yourpage">
                            </div>
                            @error('social_facebook')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">LinkedIn URL</label>
                            <div class="input-group" style="border-radius:9px;overflow:hidden;">
                                <span class="input-group-text bg-white border-end-0"><i class="fab fa-linkedin" style="color:#0a66c2;font-size:15px;"></i></span>
                                <input type="url" name="social_linkedin"
                                       class="form-control border-start-0 @error('social_linkedin') is-invalid @enderror"
                                       value="{{ old('social_linkedin', $settings['social_linkedin']) }}"
                                       placeholder="https://linkedin.com/company/yourpage">
                            </div>
                            @error('social_linkedin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- LEGAL --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;overflow:hidden;">
                <div class="px-4 py-3 d-flex align-items-center gap-3" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                    <div style="width:34px;height:34px;background:linear-gradient(135deg,#475569,#64748b);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-scale-balanced text-white" style="font-size:13px;"></i>
                    </div>
                    <div>
                        <p class="mb-0 fw-bold text-dark" style="font-size:14px;">Legal</p>
                        <p class="mb-0 text-muted" style="font-size:12px;">Sister site link shown in the footer legal section.</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Sister Site Name</label>
                            <input type="text" name="sister_site_name"
                                   class="form-control @error('sister_site_name') is-invalid @enderror"
                                   value="{{ old('sister_site_name', $settings['sister_site_name']) }}"
                                   placeholder="e.g. Migo Trucking"
                                   style="border-radius:9px;">
                            @error('sister_site_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Sister Site URL</label>
                            <div class="input-group" style="border-radius:9px;overflow:hidden;">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-link text-muted"></i></span>
                                <input type="url" name="sister_site_url"
                                       class="form-control border-start-0 @error('sister_site_url') is-invalid @enderror"
                                       value="{{ old('sister_site_url', $settings['sister_site_url']) }}"
                                       placeholder="https://migotrucking.com">
                            </div>
                            @error('sister_site_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- BRANDING --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;overflow:hidden;">
                <div class="px-4 py-3 d-flex align-items-center gap-3" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                    <div style="width:34px;height:34px;background:linear-gradient(135deg,#0891b2,#06b6d4);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-copyright text-white" style="font-size:13px;"></i>
                    </div>
                    <div>
                        <p class="mb-0 fw-bold text-dark" style="font-size:14px;">Branding</p>
                        <p class="mb-0 text-muted" style="font-size:12px;">Text shown at the bottom of every page.</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="col-md-10">
                        <label class="form-label fw-semibold small">Copyright Text</label>
                        <input type="text" name="copyright_text"
                               class="form-control @error('copyright_text') is-invalid @enderror"
                               value="{{ old('copyright_text', $settings['copyright_text']) }}"
                               placeholder="© {{ date('Y') }} Zonely. Empowering Local Experts."
                               style="border-radius:9px;">
                        <div class="form-text">Shown in the footer bottom bar. Use <code>&#123;&#123;year&#125;&#125;</code> to auto-insert the current year.</div>
                        @error('copyright_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="btn fw-bold px-5 py-2" style="background:linear-gradient(135deg,#0f766e,#0d9488);color:#fff;border-radius:10px;box-shadow:0 2px 8px rgba(15,118,110,.25);">
                <i class="fas fa-save me-2"></i>Save All Settings
            </button>

        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top:80px;border-radius:14px;overflow:hidden;">
                <div class="px-4 py-3 d-flex align-items-center gap-2" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                    <i class="fas fa-eye text-muted" style="font-size:13px;"></i>
                    <p class="mb-0 fw-bold text-dark" style="font-size:13px;">Where Each Setting Appears</p>
                </div>
                <div class="card-body p-0">

                    <div class="px-4 py-3" style="border-bottom:1px solid #f1f5f9;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div style="width:24px;height:24px;background:linear-gradient(135deg,#1e293b,#334155);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-headset text-white" style="font-size:9px;"></i>
                            </div>
                            <span class="fw-bold" style="font-size:12px;">Contact</span>
                        </div>
                        <ul class="list-unstyled mb-0" style="font-size:12px;color:#64748b;padding-left:32px;">
                            <li class="mb-1 d-flex align-items-center gap-2"><i class="fas fa-circle" style="font-size:4px;color:#94a3b8;"></i> Seller suspension email</li>
                            <li class="mb-1 d-flex align-items-center gap-2"><i class="fas fa-circle" style="font-size:4px;color:#94a3b8;"></i> Seller reactivation email</li>
                            <li class="mb-1 d-flex align-items-center gap-2"><i class="fas fa-circle" style="font-size:4px;color:#94a3b8;"></i> Suspended seller dashboard banner</li>
                            <li class="d-flex align-items-center gap-2"><i class="fas fa-circle" style="font-size:4px;color:#94a3b8;"></i> Site footer support section</li>
                        </ul>
                    </div>

                    <div class="px-4 py-3" style="border-bottom:1px solid #f1f5f9;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div style="width:24px;height:24px;background:linear-gradient(135deg,#1d4ed8,#3b82f6);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-share-nodes text-white" style="font-size:9px;"></i>
                            </div>
                            <span class="fw-bold" style="font-size:12px;">Social Media</span>
                        </div>
                        <ul class="list-unstyled mb-0" style="font-size:12px;color:#64748b;padding-left:32px;">
                            <li class="d-flex align-items-center gap-2"><i class="fas fa-circle" style="font-size:4px;color:#94a3b8;"></i> Footer social icons (Facebook, LinkedIn)</li>
                        </ul>
                    </div>

                    <div class="px-4 py-3" style="border-bottom:1px solid #f1f5f9;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div style="width:24px;height:24px;background:linear-gradient(135deg,#475569,#64748b);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-scale-balanced text-white" style="font-size:9px;"></i>
                            </div>
                            <span class="fw-bold" style="font-size:12px;">Legal</span>
                        </div>
                        <ul class="list-unstyled mb-0" style="font-size:12px;color:#64748b;padding-left:32px;">
                            <li class="d-flex align-items-center gap-2"><i class="fas fa-circle" style="font-size:4px;color:#94a3b8;"></i> Footer legal section — Sister Site link</li>
                        </ul>
                    </div>

                    <div class="px-4 py-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div style="width:24px;height:24px;background:linear-gradient(135deg,#0891b2,#06b6d4);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-copyright text-white" style="font-size:9px;"></i>
                            </div>
                            <span class="fw-bold" style="font-size:12px;">Branding</span>
                        </div>
                        <ul class="list-unstyled mb-0" style="font-size:12px;color:#64748b;padding-left:32px;">
                            <li class="d-flex align-items-center gap-2"><i class="fas fa-circle" style="font-size:4px;color:#94a3b8;"></i> Footer bottom bar copyright text</li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

    </div>
    </form>

</div>
@endsection
