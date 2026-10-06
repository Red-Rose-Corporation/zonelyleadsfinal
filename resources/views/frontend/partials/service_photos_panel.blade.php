{{-- Photos beside the included-points list inside an opened service card. Only rendered when the service has
     photos. Expects: $svc, $svcPhotos (collection, main photo first), $hasPrice, $ptLabel. --}}
@php
    $svpN      = $svcPhotos->count();
    $svpFirst  = $svcPhotos->first();
    $svpUrls   = $svcPhotos->map(fn ($p) => $p->url)->values();
    $svpBadge  = $hasPrice ? '$' . number_format($svc->price, 0) : ($svc->pricing_type === 'free' ? 'Free' : null);
@endphp
<div class="svp-gal" data-title="{{ $svc->title }}" data-photos="{{ $svpUrls->toJson() }}" data-cur="0">
    <button type="button" class="svp-main" onclick="svpOpen(this.closest('.svp-gal'))" aria-label="Open photos of {{ $svc->title }} larger">
        <img src="{{ $svpFirst->url }}" alt="{{ $svc->title }}" width="1200" height="900" loading="lazy" decoding="async"
             onerror="this.style.display='none'">
        @if($svpBadge)
        <span class="svp-bdg"><b>{{ $svpBadge }}</b>@if($hasPrice)<small>{{ strtolower($ptLabel) }}</small>@endif</span>
        @endif
        <span class="svp-zm"><i class="fas fa-magnifying-glass-plus"></i>{{ $svpN > 1 ? $svpN . ' photos' : 'Enlarge' }}</span>
    </button>
    @if($svpN > 1)
    <div class="svp-strip">
        @foreach($svcPhotos as $k => $ph)
        <button type="button" class="svp-tb{{ $k === 0 ? ' on' : '' }}" onclick="svpPick(this, {{ $k }})"
                aria-label="Show photo {{ $k + 1 }} of {{ $svpN }}" aria-pressed="{{ $k === 0 ? 'true' : 'false' }}">
            <img src="{{ $ph->thumb_url }}" alt="" width="56" height="42" loading="lazy" decoding="async"
                 onerror="if(this.dataset.f!=='1'){this.dataset.f='1';this.src='{{ $ph->url }}';}else{this.style.visibility='hidden';}">
        </button>
        @endforeach
    </div>
    @endif
</div>
