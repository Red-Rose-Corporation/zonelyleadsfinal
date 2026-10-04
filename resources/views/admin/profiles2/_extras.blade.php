{{-- Gallery, working hours and phone visibility. Expects: $user, $gallery --}}
@php
    $tzList      = \App\Support\ProfileSectionDefs::timezones();
    $rtList      = \App\Support\ProfileSectionDefs::responseTimes();
    $sched       = is_array($user->schedule) ? $user->schedule : [];
    $oh          = $sched['office_hours'] ?? [];
    $showOh      = filter_var(old('show_office_hours', $sched['show_office_hours'] ?? false), FILTER_VALIDATE_BOOLEAN);
    $savedTz     = $oh['timezone'] ?? 'America/New_York';
    if (!isset($tzList[$savedTz])) { $tzList[$savedTz] = $savedTz; }
    $ohDays      = ['mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thu' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday'];
    $defaultOpen = ['mon' => true, 'tue' => true, 'wed' => true, 'thu' => true, 'fri' => true, 'sat' => false, 'sun' => false];
@endphp

{{-- ================================================================ GALLERY --}}
<div class="section-card mb-4" id="gallery">
    <div class="card-header bg-success text-white p-3">
        <h6 class="mb-0"><i class="fas fa-images me-2"></i>Gallery ({{ $gallery->count() }}/12)</h6>
    </div>
    <div class="card-body p-4">
        <p class="text-muted small">Photos shown in the gallery slider on the public profile. Removing a photo hides it from the profile; the image file itself stays in storage.</p>

        <div class="row g-3 mb-3">
            @forelse($gallery as $photo)
            <div class="col-md-4 col-sm-6 col-12">
                <div class="border rounded-3 h-100 overflow-hidden">
                    <img src="{{ $photo->image_url }}" alt="{{ $photo->caption ?: 'Gallery photo' }}" style="width:100%;height:150px;object-fit:cover;background:#f1f5f9" loading="lazy">
                    <div class="p-2">
                        <form method="POST" action="{{ route('admin.profiles.sections.gallery.caption', [$user->id, $photo->id]) }}">
                            @csrf @method('PUT')
                            <label class="form-label small fw-semibold mb-1" for="cap{{ $photo->id }}">Caption</label>
                            <div class="input-group input-group-sm">
                                <input type="text" id="cap{{ $photo->id }}" name="caption" class="form-control" maxlength="150" value="{{ $photo->caption }}">
                                <button type="submit" class="btn btn-outline-success"><i class="fas fa-save"></i></button>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('admin.profiles.sections.gallery.destroy', [$user->id, $photo->id]) }}" class="mt-2"
                              onsubmit="return confirm('Remove this photo from the gallery?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100"><i class="fas fa-trash me-1"></i>Remove</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12"><p class="text-muted mb-0">No gallery photos yet.</p></div>
            @endforelse
        </div>

        @if($gallery->count() < 12)
        <form method="POST" action="{{ route('admin.profiles.sections.gallery.store', $user->id) }}" enctype="multipart/form-data" class="border rounded-3 p-3">
            @csrf
            <div class="fw-semibold text-success mb-2"><i class="fas fa-plus me-1"></i> Add a photo</div>
            <div class="row g-2">
                <div class="col-md-5 col-12">
                    <input type="file" name="photo" class="form-control" accept="image/png,image/jpeg,image/webp,image/gif" required>
                </div>
                <div class="col-md-5 col-12">
                    <input type="text" name="caption" class="form-control" maxlength="150" placeholder="Caption (optional)">
                </div>
                <div class="col-md-2 col-12">
                    <button type="submit" class="btn btn-success w-100"><i class="fas fa-upload me-1"></i> Upload</button>
                </div>
            </div>
            <div class="form-text">JPG, PNG, WebP or GIF, up to 10&nbsp;MB. Resized automatically.</div>
        </form>
        @else
        <div class="alert alert-info small mb-0">The gallery is full (12 photos). Remove one to add another.</div>
        @endif
    </div>
</div>

{{-- ============================================================ WORKING HOURS --}}
<div class="section-card mb-4" id="hours">
    <div class="card-header text-white p-3" style="background:#0f766e">
        <h6 class="mb-0"><i class="fas fa-business-time me-2"></i>Working Hours &amp; Response Time</h6>
    </div>
    <div class="card-body p-4">
        <p class="text-muted small">Shown on the public profile with an Open/Closed badge. The seller's online-booking schedule is not changed by this form.</p>

        <form method="POST" action="{{ route('admin.profiles.sections.hours', $user->id) }}">
            @csrf @method('PUT')

            <div class="form-check form-switch mb-3">
                <input type="hidden" name="show_office_hours" value="0">
                <input class="form-check-input" type="checkbox" role="switch" name="show_office_hours" id="showOh" value="1" {{ $showOh ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="showOh">Show working hours on the public profile</label>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4 col-12">
                    <label class="form-label fw-semibold" for="ohTz">Timezone</label>
                    <select name="office_hours[timezone]" id="ohTz" class="form-select">
                        @foreach($tzList as $val => $lbl)
                        <option value="{{ $val }}" {{ old('office_hours.timezone', $savedTz) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-12">
                    <label class="form-label fw-semibold" for="ohRt">Response time</label>
                    <select name="office_hours[response_time]" id="ohRt" class="form-select">
                        <option value="">— Not shown —</option>
                        @foreach($rtList as $val => $lbl)
                        <option value="{{ $val }}" {{ old('office_hours.response_time', $oh['response_time'] ?? '') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-12">
                    <label class="form-label fw-semibold d-block">Emergency calls</label>
                    <div class="form-check form-switch mt-2">
                        <input type="hidden" name="office_hours[emergency_available]" value="0">
                        <input class="form-check-input" type="checkbox" role="switch" name="office_hours[emergency_available]" id="ohEmerg" value="1"
                               {{ filter_var(old('office_hours.emergency_available', $oh['emergency_available'] ?? false), FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }}>
                        <label class="form-check-label" for="ohEmerg">Accepts 24/7 emergency calls</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold" for="ohNote">Note <span class="text-muted fw-normal">(optional, up to 200 characters)</span></label>
                    <input type="text" name="office_hours[note]" id="ohNote" class="form-control" maxlength="200"
                           value="{{ old('office_hours.note', $oh['note'] ?? '') }}" placeholder="e.g. Appointments only on Saturdays">
                </div>
            </div>

            <div class="fw-semibold mb-2">Weekly hours</div>
            @foreach($ohDays as $dk => $dl)
            @php
                $dd     = $oh['days'][$dk] ?? null;
                $isOpen = filter_var(old("office_hours.days.$dk.open", $dd['open'] ?? $defaultOpen[$dk]), FILTER_VALIDATE_BOOLEAN);
                $saved  = $dd['slots'] ?? [['from' => '09:00', 'to' => '17:00']];
            @endphp
            <div class="border rounded-3 p-2 mb-2">
                <div class="row g-2 align-items-center">
                    <div class="col-md-2 col-12">
                        <div class="form-check form-switch">
                            <input type="hidden" name="office_hours[days][{{ $dk }}][open]" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" name="office_hours[days][{{ $dk }}][open]" id="ohOpen{{ $dk }}" value="1" {{ $isOpen ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="ohOpen{{ $dk }}">{{ $dl }}</label>
                        </div>
                    </div>
                    @for($s = 0; $s < 2; $s++)
                    <div class="col-md-5 col-12">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">{{ $s === 0 ? 'Open' : 'Split' }}</span>
                            <input type="time" class="form-control" aria-label="{{ $dl }} slot {{ $s + 1 }} from"
                                   name="office_hours[days][{{ $dk }}][slots][{{ $s }}][from]"
                                   value="{{ old("office_hours.days.$dk.slots.$s.from", $saved[$s]['from'] ?? '') }}">
                            <span class="input-group-text">to</span>
                            <input type="time" class="form-control" aria-label="{{ $dl }} slot {{ $s + 1 }} to"
                                   name="office_hours[days][{{ $dk }}][slots][{{ $s }}][to]"
                                   value="{{ old("office_hours.days.$dk.slots.$s.to", $saved[$s]['to'] ?? '') }}">
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
            @endforeach
            <div class="form-text mb-3">"Split" is an optional second block for the day (for example a lunch break). Leave it empty if not needed.</div>

            <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Save working hours</button>
        </form>
    </div>
</div>

{{-- ============================================================== VISIBILITY --}}
<div class="section-card mb-5" id="visibility">
    <div class="card-header bg-secondary text-white p-3">
        <h6 class="mb-0"><i class="fas fa-eye me-2"></i>Phone Visibility</h6>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.profiles.sections.visibility', $user->id) }}">
            @csrf @method('PUT')
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch" name="show_phone" id="showPhone" value="1" {{ $user->show_phone ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="showPhone">Show phone number on the profile</label>
            </div>
            <p class="text-muted small mb-3">This is the same switch the seller has in their own Contact settings. The public profile page does not use it at the moment (call buttons are disabled until call tracking is set up), so changing it has no visible effect today.</p>
            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save me-1"></i> Save</button>
        </form>
    </div>
</div>
