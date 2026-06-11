@extends('frontend.layouts.__prof_app')
@section('title', 'Work Gallery')
@section('page-title', 'Work Gallery')

@section('content')
<div class="pb-10 max-w-2xl mx-auto">

    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('seller.onboarding') }}"
           class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:text-teal-700 hover:border-teal-300 transition">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Work Gallery</h1>
            <p class="text-xs text-gray-500 mt-0.5">Show your best work — up to 12 photos displayed on your public profile</p>
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

    {{-- Upload card --}}
    @if($user->gallery->count() < 12)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-5">
        <p class="text-sm font-bold text-slate-700 mb-3">Add a Photo <span class="text-slate-400 font-normal text-xs">({{ $user->gallery->count() }}/12 uploaded)</span></p>
        <form method="POST" action="{{ route('seller.gallery.store') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf

            {{-- Drop zone --}}
            <label for="photoUpload"
                   class="flex flex-col items-center justify-center w-full h-44 border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer bg-slate-50 hover:bg-teal-50 hover:border-teal-300 transition group">
                <div id="dropPreview" class="flex flex-col items-center gap-2 text-slate-400 group-hover:text-teal-600 transition">
                    <i class="fa-solid fa-cloud-arrow-up text-3xl"></i>
                    <span class="text-sm font-semibold">Click to upload or drag & drop</span>
                    <span class="text-xs">JPG, PNG, WebP · max 10MB · auto-resized to 1200×900</span>
                </div>
                <img id="previewImg" src="" class="hidden w-full h-full object-cover rounded-2xl">
                <input id="photoUpload" type="file" name="photo" accept="image/*" class="hidden" onchange="previewGallery(this)">
            </label>

            <input type="text" name="caption" maxlength="150"
                   placeholder="Caption (optional) — e.g. Bathroom renovation, Brooklyn NY"
                   class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-teal-600 focus:ring-2 focus:ring-teal-50 transition">

            <div class="flex justify-end">
                <button type="submit" id="uploadBtn" disabled
                    class="px-6 py-2.5 bg-teal-700 text-white font-bold text-sm rounded-xl opacity-40 cursor-not-allowed transition"
                    style="transition:all .2s">
                    <i class="fa-solid fa-upload mr-1.5"></i> Upload Photo
                </button>
            </div>
        </form>
    </div>
    @else
    <div class="mb-5 p-4 bg-amber-50 border border-amber-200 text-amber-700 text-sm rounded-2xl flex items-center gap-2">
        <i class="fa-solid fa-triangle-exclamation"></i> You've reached the 12-photo limit. Remove a photo to upload a new one.
    </div>
    @endif

    {{-- Gallery grid --}}
    @if($user->gallery->count())
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
        <p class="text-sm font-bold text-slate-700 mb-4">Your Photos</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @foreach($user->gallery as $photo)
            <div class="group relative rounded-2xl overflow-hidden border border-slate-100 shadow-sm bg-slate-50">
                <img src="{{ $photo->image_url }}" alt="{{ $photo->caption }}"
                     class="w-full aspect-[4/3] object-cover">

                {{-- Hover overlay --}}
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex flex-col items-center justify-center gap-2 p-3">
                    <form method="POST" action="{{ route('seller.gallery.destroy', $photo->id) }}"
                          onsubmit="return confirm('Remove this photo?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="flex items-center gap-1.5 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-bold rounded-xl transition">
                            <i class="fa-solid fa-trash-can"></i> Remove
                        </button>
                    </form>
                </div>

                {{-- Caption --}}
                @if($photo->caption)
                <div class="px-2 py-1.5 bg-white border-t border-slate-100">
                    <p class="text-xs text-slate-500 truncate">{{ $photo->caption }}</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="text-center py-12 text-slate-400">
        <i class="fa-regular fa-images text-4xl mb-3 block"></i>
        <p class="text-sm font-medium">No photos yet — upload your best work above</p>
    </div>
    @endif

</div>

<script>
function previewGallery(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('dropPreview').classList.add('hidden');
        const img = document.getElementById('previewImg');
        img.src = e.target.result;
        img.classList.remove('hidden');
        const btn = document.getElementById('uploadBtn');
        btn.disabled = false;
        btn.classList.remove('opacity-40', 'cursor-not-allowed');
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endsection
