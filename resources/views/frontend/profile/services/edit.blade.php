@extends('frontend.layouts.__prof_app')
@section('title', 'Edit Service')
@section('page-title', 'Edit Service')

@section('content')
<div class="pb-10 max-w-2xl mx-auto">

    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('user.services.index') }}"
           class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:text-teal-700 hover:border-teal-300 transition">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Edit Service</h1>
            <p class="text-xs text-gray-500 mt-0.5">Changes appear live on your public page</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-2xl flex items-center gap-2">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-5 p-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-2xl">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    {{-- ============ Photos (optional). Separate forms, so they live outside the service form below ============ --}}
    @php
        $photos   = $service->relationLoaded('photos') ? $service->photos : collect();
        $maxPhotos = \App\Models\Service::MAX_PHOTOS;
        $slotsLeft = max(0, $maxPhotos - $photos->count());
    @endphp
    <div id="photos" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-4" style="scroll-margin-top:16px">
        <div class="flex items-start justify-between gap-3 mb-1">
            <div>
                <label class="block text-sm font-bold text-slate-700">
                    <i class="fa-solid fa-camera text-teal-700 mr-1.5"></i>Photos <span class="text-slate-400 font-normal">(optional)</span>
                </label>
                <p class="text-xs text-slate-400 mt-1">Show visitors what this service looks like. The first photo is the small thumbnail on your page. No photo? Your page works exactly as before.</p>
            </div>
            <span class="shrink-0 text-xs font-bold px-2.5 py-1 rounded-lg {{ $photos->count() ? 'bg-teal-50 text-teal-700' : 'bg-slate-100 text-slate-500' }}">{{ $photos->count() }}/{{ $maxPhotos }}</span>
        </div>

        @if(session('photo_success'))
        <div class="mt-3 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-xl flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> {{ session('photo_success') }}
        </div>
        @endif
        @if(session('photo_error'))
        <div class="mt-3 p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl flex items-start gap-2">
            <i class="fa-solid fa-circle-exclamation mt-0.5"></i> <span>{{ session('photo_error') }}</span>
        </div>
        @endif

        @if($photos->count())
        <div class="grid grid-cols-3 gap-3 mt-4">
            @foreach($photos as $idx => $ph)
            <div>
                <div class="relative rounded-xl overflow-hidden bg-slate-100 border border-slate-200" style="aspect-ratio:4/3">
                    <img src="{{ $ph->thumb_url }}" alt="Photo {{ $idx + 1 }}" loading="lazy" class="w-full h-full object-cover"
                         onerror="if(this.dataset.f!=='1'){this.dataset.f='1';this.src='{{ $ph->url }}';}else{this.style.visibility='hidden';}">
                    @if($idx === 0)
                    <span class="absolute top-1.5 left-1.5 text-[10px] font-bold bg-teal-700 text-white px-2 py-0.5 rounded-md">Main</span>
                    @endif
                </div>
                <div class="flex items-center gap-1.5 mt-1.5">
                    @if($idx > 0)
                    <form action="{{ route('user.services.photos.main', [$service->id, $ph->id]) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full text-[11px] font-semibold text-slate-600 bg-slate-100 hover:bg-teal-700 hover:text-white rounded-lg py-1.5 transition">Make main</button>
                    </form>
                    @else
                    <span class="flex-1 text-[11px] text-slate-400 text-center py-1.5">Thumbnail</span>
                    @endif
                    <form action="{{ route('user.services.photos.destroy', [$service->id, $ph->id]) }}" method="POST" onsubmit="return confirm('Remove this photo?')">
                        @csrf @method('DELETE')
                        <button type="submit" aria-label="Remove photo {{ $idx + 1 }}" class="w-8 h-7 text-xs text-slate-500 bg-slate-100 hover:bg-red-500 hover:text-white rounded-lg transition"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        @if($slotsLeft > 0)
        <div id="svcPhotoZone" data-url="{{ route('user.services.photos.store', $service->id) }}" data-slots="{{ $slotsLeft }}"
             class="mt-4 border-2 border-dashed border-slate-200 hover:border-teal-400 rounded-2xl p-5 text-center transition">
            <input id="svcPhotoInput" type="file" accept="image/jpeg,image/png,image/webp" multiple class="sr-only">
            <label for="svcPhotoInput" class="cursor-pointer block">
                <span class="w-11 h-11 mx-auto mb-2 bg-teal-50 text-teal-700 rounded-xl flex items-center justify-center"><i class="fa-solid fa-image"></i></span>
                <span class="block text-sm font-bold text-slate-700">Add photos</span>
                <span class="block text-xs text-slate-400 mt-0.5">Tap to choose, or drop them here &middot; up to {{ $slotsLeft }} more</span>
            </label>
            <p class="text-[11px] text-slate-400 mt-2">JPG, PNG or WebP &middot; at least {{ \App\Services\ServicePhotoManager::MIN_WIDTH }}&times;{{ \App\Services\ServicePhotoManager::MIN_HEIGHT }} px. Big phone photos are shrunk for you automatically.</p>
        </div>
        <div id="svcPhotoQueue" class="mt-3 space-y-2" aria-live="polite"></div>

        <noscript>
            <form action="{{ route('user.services.photos.store', $service->id) }}" method="POST" enctype="multipart/form-data" class="mt-3 flex gap-2">
                @csrf
                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" required class="text-sm">
                <button type="submit" class="px-4 py-2 bg-teal-700 text-white text-sm font-bold rounded-xl">Upload</button>
            </form>
        </noscript>

        @if(isset($gallery) && $gallery->count())
        <details class="mt-3">
            <summary class="text-xs font-semibold text-teal-700 cursor-pointer">Or choose from your photo gallery ({{ $gallery->count() }})</summary>
            <div class="flex flex-wrap gap-2 mt-2">
                @foreach($gallery as $g)
                <form action="{{ route('user.services.photos.gallery', $service->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="gallery_id" value="{{ $g->id }}">
                    <button type="submit" class="block rounded-lg overflow-hidden border border-slate-200 hover:border-teal-500 transition" style="width:84px;height:63px" title="Use this photo">
                        <img src="{{ $g->image_url }}" alt="" loading="lazy" class="w-full h-full object-cover">
                    </button>
                </form>
                @endforeach
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Tap a photo to use it here. It stays in your gallery too.</p>
        </details>
        @endif
        @else
        <p class="mt-4 text-xs text-slate-400">You have the maximum of {{ $maxPhotos }} photos. Remove one to add a different one.</p>
        @endif
    </div>

    <form action="{{ route('user.services.update', $service->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- Title --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <label class="block text-sm font-bold text-slate-700 mb-2">
                Service Title <span class="text-red-500">*</span>
            </label>
            <input type="text" name="title" value="{{ old('title', $service->title) }}" required
                class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-600 focus:ring-2 focus:ring-teal-50 transition">
        </div>

        {{-- Price + Pricing Type --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <label class="block text-sm font-bold text-slate-700 mb-3">Pricing</label>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">Price ($)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">$</span>
                        <input type="number" name="price" value="{{ old('price', $service->price) }}" min="0" step="0.01"
                            class="w-full pl-7 pr-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-600 focus:ring-2 focus:ring-teal-50 transition">
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Leave blank → shows "Contact us"</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">Pricing Type</label>
                    <select name="pricing_type"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-600 focus:ring-2 focus:ring-teal-50 transition bg-white">
                        @php $currentPt = old('pricing_type', $service->pricing_type ?? 'starting_at'); @endphp
                        <option value="starting_at" {{ $currentPt=='starting_at' ? 'selected' : '' }}>starting at</option>
                        <option value="per_month"   {{ $currentPt=='per_month'   ? 'selected' : '' }}>per month</option>
                        <option value="per_hour"    {{ $currentPt=='per_hour'    ? 'selected' : '' }}>per hour</option>
                        <option value="flat_rate"   {{ $currentPt=='flat_rate'   ? 'selected' : '' }}>flat rate</option>
                        <option value="free"        {{ $currentPt=='free'        ? 'selected' : '' }}>free</option>
                        <option value="contact"     {{ $currentPt=='contact'     ? 'selected' : '' }}>Negotiable</option>
                    </select>
                </div>
            </div>

            {{-- Live preview --}}
            <div class="mt-4 flex items-center justify-between px-4 py-3 bg-slate-50 rounded-xl border border-slate-100">
                <span class="text-sm font-semibold text-slate-500" id="previewTitle">{{ $service->title }}</span>
                <div class="text-right">
                    <div class="text-xl font-black text-teal-800" id="previewPrice">
                        {{ $service->price ? '$'.number_format($service->price, 0) : '—' }}
                    </div>
                    <div class="text-xs text-teal-600 font-semibold" id="previewType">
                        {{ ['starting_at'=>'starting at','per_month'=>'per month','per_hour'=>'per hour','flat_rate'=>'flat rate','free'=>'free','contact'=>'Negotiable'][$service->pricing_type ?? 'starting_at'] ?? 'starting at' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Feature Bullet Points --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <label class="block text-sm font-bold text-slate-700 mb-1">
                Feature Bullet Points <span class="text-slate-400 font-normal">(optional)</span>
            </label>
            <p class="text-xs text-slate-400 mb-3">
                One feature per line — displayed as <span class="text-emerald-600 font-semibold">✓ checkmarks</span> on your public page
            </p>
            <textarea name="features" rows="5"
                placeholder="Federal & New York State Return&#10;Itemized deductions & credits&#10;EITC & Child Tax Credit optimization"
                class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-600 focus:ring-2 focus:ring-teal-50 transition resize-none font-mono">{{ old('features', $service->features) }}</textarea>

            {{-- Live feature preview --}}
            @php $existingFeatures = array_filter(array_map('trim', explode("\n", $service->features ?? ''))); @endphp
            @if($existingFeatures)
            <div class="mt-3 p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-1.5">
                @foreach($existingFeatures as $f)
                <div class="flex items-center gap-2 text-xs text-slate-600">
                    <i class="fa-solid fa-check text-emerald-500 text-[10px]"></i> {{ $f }}
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Description --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <label class="block text-sm font-bold text-slate-700 mb-2">
                Description <span class="text-slate-400 font-normal">(optional)</span>
            </label>
            <textarea name="description" rows="3"
                class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-600 focus:ring-2 focus:ring-teal-50 transition resize-none">{{ old('description', $service->description) }}</textarea>
        </div>

        {{-- Visibility toggle --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-slate-700">Show on public page</p>
                <p class="text-xs text-slate-400 mt-0.5">Inactive services are hidden from visitors</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $service->is_active) ? 'checked' : '' }} class="sr-only peer">
                <div class="w-11 h-6 bg-slate-200 peer-checked:bg-teal-700 rounded-full transition-all
                            after:content-[''] after:absolute after:top-0.5 after:left-0.5
                            after:bg-white after:rounded-full after:h-5 after:w-5
                            after:transition-all peer-checked:after:translate-x-5"></div>
            </label>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" form="deleteServiceForm"
                onclick="return confirm('Delete this service?')"
                class="flex items-center gap-2 px-5 py-3 bg-red-50 hover:bg-red-500 hover:text-white text-red-500 font-bold rounded-2xl text-sm transition">
                <i class="fa-solid fa-trash text-xs"></i> Delete
            </button>
            <button type="submit"
                class="px-8 py-3 bg-teal-700 hover:bg-teal-800 text-white font-bold rounded-2xl text-sm transition">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Save Changes
            </button>
        </div>

    </form>

    <form id="deleteServiceForm" action="{{ route('user.services.destroy', $service->id) }}" method="POST">
        @csrf @method('DELETE')
    </form>
</div>

<script>
const ptLabels = {starting_at:'starting at',per_month:'per month',per_hour:'per hour',flat_rate:'flat rate',free:'free',contact:'Negotiable'};
function updatePreview() {
    const title = document.querySelector('[name=title]').value || 'Your service name';
    const price = document.querySelector('[name=price]').value;
    const pt    = document.querySelector('[name=pricing_type]').value;
    document.getElementById('previewTitle').textContent = title;
    document.getElementById('previewPrice').textContent = price ? '$' + parseFloat(price).toLocaleString() : '—';
    document.getElementById('previewType').textContent  = ptLabels[pt] || 'starting at';
}
document.querySelector('[name=title]').addEventListener('input', updatePreview);
document.querySelector('[name=price]').addEventListener('input', updatePreview);
document.querySelector('[name=pricing_type]').addEventListener('change', updatePreview);
</script>
@if($slotsLeft > 0)
<script>
// Photo uploader: checks the photo, shrinks big ones in the browser (much faster on mobile data), uploads
// with a progress bar, then reloads to show the result. The server re-checks everything.
(function () {
    var zone  = document.getElementById('svcPhotoZone');
    if (!zone) return;
    var input = document.getElementById('svcPhotoInput');
    var queue = document.getElementById('svcPhotoQueue');
    var url   = zone.dataset.url;
    var slots = parseInt(zone.dataset.slots, 10) || 0;
    var token = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
    var MIN_W = {{ \App\Services\ServicePhotoManager::MIN_WIDTH }}, MIN_H = {{ \App\Services\ServicePhotoManager::MIN_HEIGHT }};
    var MAX_SIDE = 2000, SKIP_BELOW = 1.5 * 1024 * 1024;
    var busy = false, anySuccess = false;

    function row(file) {
        var el = document.createElement('div');
        el.className = 'flex items-center gap-3 p-2.5 bg-slate-50 border border-slate-100 rounded-xl';
        var img = document.createElement('img');
        img.className = 'w-14 h-10 object-cover rounded-lg bg-slate-200 shrink-0';
        img.alt = '';
        try { img.src = URL.createObjectURL(file); } catch (e) {}
        var box = document.createElement('div');
        box.className = 'min-w-0 flex-1';
        var name = document.createElement('p');
        name.className = 'text-xs font-semibold text-slate-700 truncate';
        name.textContent = file.name || 'Photo';
        var status = document.createElement('p');
        status.className = 'text-[11px] text-slate-400 mt-0.5';
        status.textContent = 'Checking...';
        var track = document.createElement('div');
        track.className = 'h-1.5 bg-slate-200 rounded-full mt-1.5 overflow-hidden';
        var bar = document.createElement('div');
        bar.className = 'h-full bg-teal-600 rounded-full transition-all';
        bar.style.width = '0%';
        track.appendChild(bar);
        box.appendChild(name); box.appendChild(status); box.appendChild(track);
        el.appendChild(img); el.appendChild(box);
        queue.appendChild(el);
        return {
            status: function (t, bad) { status.textContent = t; status.className = 'text-[11px] mt-0.5 ' + (bad ? 'text-red-600 font-semibold' : 'text-slate-400'); },
            progress: function (n) { bar.style.width = n + '%'; },
            fail: function (t) { status.textContent = t; status.className = 'text-[11px] mt-0.5 text-red-600 font-semibold'; track.style.display = 'none'; },
            done: function () { bar.style.width = '100%'; status.textContent = 'Uploaded'; status.className = 'text-[11px] mt-0.5 text-emerald-600 font-semibold'; }
        };
    }

    function decode(file) {
        return new Promise(function (resolve, reject) {
            var u = URL.createObjectURL(file), im = new Image();
            im.onload = function () { URL.revokeObjectURL(u); resolve(im); };
            im.onerror = function () { URL.revokeObjectURL(u); reject(new Error('decode')); };
            im.src = u;
        });
    }

    // Returns the file to upload: a resized JPEG when that is smaller, otherwise the original.
    function shrink(file, im) {
        var w = im.naturalWidth, h = im.naturalHeight, scale = Math.min(1, MAX_SIDE / Math.max(w, h));
        if (scale === 1 && file.size <= SKIP_BELOW) return Promise.resolve(file);
        try {
            var c = document.createElement('canvas');
            c.width = Math.round(w * scale); c.height = Math.round(h * scale);
            var ctx = c.getContext('2d');
            ctx.fillStyle = '#fff'; ctx.fillRect(0, 0, c.width, c.height);
            ctx.drawImage(im, 0, 0, c.width, c.height);
            return new Promise(function (resolve) {
                c.toBlob(function (b) {
                    if (b && b.size < file.size) resolve(new File([b], (file.name || 'photo').replace(/\.\w+$/, '') + '.jpg', { type: 'image/jpeg' }));
                    else resolve(file);
                }, 'image/jpeg', 0.88);
            });
        } catch (e) { return Promise.resolve(file); }
    }

    function send(file, r) {
        return new Promise(function (resolve) {
            var x = new XMLHttpRequest();
            x.open('POST', url);
            x.setRequestHeader('X-CSRF-TOKEN', token);
            x.setRequestHeader('Accept', 'application/json');
            x.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            x.upload.onprogress = function (e) { if (e.lengthComputable) r.progress(Math.round(e.loaded / e.total * 100)); };
            x.onload = function () {
                var j = {};
                try { j = JSON.parse(x.responseText); } catch (e) {}
                if (x.status >= 200 && x.status < 300 && j.ok) return resolve({ ok: true });
                if (x.status === 419 || x.status === 401) return resolve({ ok: false, message: 'Your session expired. Please reload the page and try again.' });
                resolve({ ok: false, message: j.message || 'Could not upload this photo. Please try again.' });
            };
            x.onerror = function () { resolve({ ok: false, message: 'No connection. Please check your internet and try again.' }); };
            var f = new FormData();
            f.append('photo', file);
            x.send(f);
        });
    }

    function one(file) {
        var r = row(file);
        if (!/^image\/(jpeg|png|webp)$/i.test(file.type)) {
            r.fail('Please use a JPG, PNG or WebP photo. If it came from an iPhone, share it as JPG.');
            return Promise.resolve();
        }
        return decode(file).then(function (im) {
            if (im.naturalWidth < MIN_W || im.naturalHeight < MIN_H) {
                r.fail('Too small: ' + im.naturalWidth + '\u00d7' + im.naturalHeight + ' px. Please use a photo at least ' + MIN_W + '\u00d7' + MIN_H + ' px so it does not look blurry.');
                return null;
            }
            r.status('Preparing...');
            return shrink(file, im).then(function (out) {
                r.status('Uploading' + (out !== file ? ' (resized)' : '') + '...');
                return send(out, r).then(function (res) {
                    if (res.ok) { anySuccess = true; r.done(); } else { r.fail(res.message); }
                });
            });
        }, function () {
            r.fail('This photo could not be read. Please choose a JPG, PNG or WebP photo.');
        });
    }

    function run(list) {
        if (busy) return;
        var files = Array.prototype.slice.call(list || []);
        if (!files.length) return;
        queue.innerHTML = '';
        if (files.length > slots) {
            var note = document.createElement('p');
            note.className = 'text-xs text-amber-700 bg-amber-50 border border-amber-100 rounded-xl px-3 py-2';
            note.textContent = 'Only ' + slots + ' more photo' + (slots > 1 ? 's fit' : ' fits') + ' on this service, so the first ' + slots + ' will be used.';
            queue.appendChild(note);
            files = files.slice(0, slots);
        }
        busy = true; anySuccess = false;
        zone.style.opacity = '.6';
        files.reduce(function (p, f) { return p.then(function () { return one(f); }); }, Promise.resolve()).then(function () {
            busy = false; zone.style.opacity = '';
            input.value = '';
            if (anySuccess) setTimeout(function () { window.location.href = window.location.pathname + '#photos'; window.location.reload(); }, 700);
        });
    }

    input.addEventListener('change', function () { run(input.files); });
    ['dragenter', 'dragover'].forEach(function (ev) { zone.addEventListener(ev, function (e) { e.preventDefault(); zone.classList.add('border-teal-400', 'bg-teal-50/40'); }); });
    ['dragleave', 'drop'].forEach(function (ev) { zone.addEventListener(ev, function (e) { e.preventDefault(); zone.classList.remove('border-teal-400', 'bg-teal-50/40'); }); });
    zone.addEventListener('drop', function (e) { run(e.dataTransfer && e.dataTransfer.files); });
})();
</script>
@endif
@endsection
