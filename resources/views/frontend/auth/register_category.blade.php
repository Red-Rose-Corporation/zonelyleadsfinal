@extends('frontend.layouts._app')
@section('title', 'Select Business Type — Zonely')
{{-- Signup step, login-only page; keep it out of the index. --}}
@section('robots', 'noindex, follow')

@section('content')
<div class="min-h-screen bg-slate-50 flex items-center justify-center py-12 px-4 pt-24 pb-16">
    <div class="max-w-5xl w-full">

        {{-- Progress Steps --}}
        <div class="flex items-center justify-center gap-2 mb-8">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-black">
                    <i class="fa-solid fa-check text-xs"></i>
                </div>
                <span class="text-xs font-bold text-green-600 hidden sm:inline">Account Created</span>
            </div>
            <div class="w-8 h-px bg-teal-300 mx-1"></div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-teal-700 rounded-full flex items-center justify-center text-white text-xs font-black">2</div>
                <span class="text-xs font-bold text-teal-700 hidden sm:inline">Business Type</span>
            </div>
            <div class="w-8 h-px bg-slate-200 mx-1"></div>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-slate-200 rounded-full flex items-center justify-center text-slate-400 text-xs font-black">3</div>
                <span class="text-xs font-bold text-slate-400 hidden sm:inline">Profile Setup</span>
            </div>
        </div>

        {{-- Success flash --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-2xl px-5 py-3 mb-6 flex items-center gap-3 max-w-xl mx-auto">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <p class="text-sm text-green-700 font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Header --}}
        <div class="text-center mb-10">
            <h1 class="text-3xl font-black text-slate-900">What type of business do you run?</h1>
            <p class="text-slate-500 mt-2 text-sm">Choose your category so we can customize your profile and lead forms.</p>
        </div>

        {{-- Category Grid --}}
        <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="categoryGrid">
            @foreach($categories as $cat)
            <div class="category-card border-2 border-transparent bg-white rounded-3xl p-6 cursor-pointer shadow-sm hover:shadow-md transition-all"
                 data-id="{{ $cat->id }}"
                 onclick="selectCategory(this)">
                <div class="cat-icon w-12 h-12 bg-teal-50 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fa-solid fa-briefcase text-teal-700 text-lg"></i>
                </div>
                <h4 class="font-bold text-slate-800 text-base leading-snug mb-3">{{ $cat->title }}</h4>
                @if($cat->children->count())
                    <ul class="space-y-1">
                        @foreach($cat->children as $child)
                            <li class="text-xs text-slate-500 flex items-center gap-1.5">
                                <span class="w-1 h-1 rounded-full bg-teal-400 shrink-0"></span>
                                {{ $child->title }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            @endforeach

            {{-- Other --}}
            <div class="category-card border-2 border-dashed border-slate-200 bg-white rounded-3xl p-6 cursor-pointer shadow-sm hover:shadow-md transition-all"
                 data-id="other"
                 onclick="selectCategory(this)">
                <div class="cat-icon w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fa-solid fa-ellipsis text-slate-400 text-lg"></i>
                </div>
                <h4 class="font-bold text-slate-600 text-base mb-2">Other</h4>
                <p class="text-xs text-slate-400">My business type is not listed above</p>
            </div>
        </div>

        {{-- Hidden form --}}
        <form method="POST" action="{{ route('user.save.category') }}" id="categoryForm">
            @csrf
            <input type="hidden" name="category_id" id="selectedCategoryId">
        </form>

        {{-- Continue button --}}
        <div class="mt-10 flex flex-col items-center gap-3">
            <button onclick="proceed()"
                    class="bg-teal-700 hover:bg-teal-800 text-white px-12 py-4 rounded-2xl text-base font-bold flex items-center gap-2 shadow-lg transition">
                Continue to Profile Setup
                <i class="fa-solid fa-arrow-right"></i>
            </button>
            <p class="text-xs text-slate-400">Step 2 of 3 · Takes less than 2 minutes</p>
        </div>

        {{-- Skip --}}
        <p class="text-center text-xs text-slate-400 mt-4">
            <a href="{{ route('profile.edit') }}" class="hover:text-teal-700 underline">Skip for now → Set up later in profile</a>
        </p>

    </div>
</div>

<style>
    .category-card { transition: all 0.2s ease; }
    .category-card.selected { border-color: #0F766E; background-color: #F0FDFA; }
    .category-card.selected .cat-icon { background-color: #CCFBF1; }
    .category-card.selected .cat-icon i { color: #0F766E; }
</style>
@endsection

@section('scripts')
<script>
    let selectedId = null;

    function selectCategory(el) {
        document.querySelectorAll('.category-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        selectedId = el.dataset.id;
    }

    function proceed() {
        if (!selectedId) {
            alert('Please select a business category to continue.');
            return;
        }
        document.getElementById('selectedCategoryId').value = selectedId;
        document.getElementById('categoryForm').submit();
    }
</script>
@endsection
