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
@endsection
