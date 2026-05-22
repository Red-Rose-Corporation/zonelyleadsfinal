@extends('frontend.layouts.__prof_app')
@section('title', 'My Services')
@section('page-title', 'Services & Pricing')

@section('content')
<div class="pb-10 max-w-3xl mx-auto">

    @if(session('success'))
    <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-2xl flex items-center gap-2">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    <div class="flex items-center justify-between mb-6 gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('seller.onboarding') }}"
               class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:text-teal-700 hover:border-teal-300 transition shrink-0">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Services & Pricing</h1>
                <p class="text-xs text-gray-500 mt-0.5">Shown in the Pricing section of your public page</p>
            </div>
        </div>
        <a href="{{ route('user.services.create') }}"
           class="shrink-0 flex items-center gap-2 bg-teal-700 hover:bg-teal-800 text-white font-bold px-4 py-2.5 rounded-2xl text-sm transition">
            <i class="fa-solid fa-plus"></i> Add
        </a>
    </div>

    <div class="space-y-3">
        @forelse($services as $service)
        @php
            $ptMap = ['starting_at'=>'starting at','per_month'=>'per month','per_hour'=>'per hour','flat_rate'=>'flat rate','free'=>'free','contact'=>'Negotiable'];
            $ptLabel = $ptMap[$service->pricing_type ?? 'starting_at'] ?? 'starting at';
            $features = array_filter(array_map('trim', explode("\n", $service->features ?? '')));
        @endphp
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-briefcase text-teal-700 text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-slate-900 text-sm truncate">{{ $service->title }}</p>
                        @if($features)
                        <p class="text-xs text-slate-400 mt-0.5">{{ count($features) }} feature{{ count($features) > 1 ? 's' : '' }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-4 shrink-0">
                    <div class="text-right">
                        @if($service->price)
                        <p class="text-xl font-black text-teal-800">${{ $service->price }}</p>
                        <p class="text-xs text-slate-400">{{ $ptLabel }}</p>
                        @else
                        <p class="text-sm font-bold text-slate-400">{{ $service->pricing_type === 'contact' ? 'Negotiable' : 'Contact' }}</p>
                        @endif
                    </div>
                    <span class="text-[10px] px-2 py-1 rounded-lg font-bold {{ $service->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-50 text-red-500' }}">
                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <div class="flex gap-2">
                        <a href="{{ route('user.services.edit', $service->id) }}"
                           class="w-9 h-9 bg-slate-100 hover:bg-teal-700 hover:text-white text-slate-600 rounded-xl flex items-center justify-center transition">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
                        <form action="{{ route('user.services.destroy', $service->id) }}" method="POST"
                              onsubmit="return confirm('Delete this service?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-9 h-9 bg-slate-100 hover:bg-red-500 hover:text-white text-slate-600 rounded-xl flex items-center justify-center transition">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @if($features)
            <div class="px-5 pb-4 border-t border-slate-50 pt-3 flex flex-wrap gap-x-6 gap-y-1">
                @foreach($features as $f)
                <span class="text-xs text-slate-500 flex items-center gap-1.5">
                    <i class="fa-solid fa-check text-emerald-500 text-[10px]"></i> {{ $f }}
                </span>
                @endforeach
            </div>
            @endif
        </div>
        @empty
        <div class="text-center py-16 bg-white rounded-3xl border border-dashed border-slate-200">
            <i class="fa-solid fa-list-check text-4xl text-slate-200 mb-3"></i>
            <p class="font-bold text-slate-400">No services yet</p>
            <p class="text-sm text-slate-400 mt-1 mb-4">Add your first service to show pricing on your page</p>
            <a href="{{ route('user.services.create') }}"
               class="inline-flex items-center gap-2 bg-teal-700 hover:bg-teal-800 text-white font-bold px-5 py-3 rounded-2xl text-sm transition">
                <i class="fa-solid fa-plus"></i> Add First Service
            </a>
        </div>
        @endforelse
    </div>

    {{ $services->links() }}

</div>
@endsection
