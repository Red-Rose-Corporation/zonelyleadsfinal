@extends('frontend.layouts._app')
@section('title', 'Leave a Review for ' . ($review->seller->name ?? 'Professional'))
@section('content')
<div class="min-h-screen bg-slate-50 pt-20 pb-16 px-4">
    <div class="max-w-xl mx-auto py-6">

        {{-- Header --}}
        <div class="text-center mb-8">
            @if($review->seller->profile_photo)
            <img src="{{ asset($review->seller->profile_photo) }}"
                 alt="{{ $review->seller->name }}"
                 class="w-20 h-20 rounded-2xl object-cover mx-auto mb-4 shadow"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
            <div class="hidden w-20 h-20 rounded-2xl bg-teal-700 text-white mx-auto mb-4 items-center justify-center font-bold text-2xl shadow">
                {{ strtoupper(substr($review->seller->name ?? 'P', 0, 2)) }}
            </div>
            @else
            <div class="w-20 h-20 rounded-2xl bg-teal-700 text-white mx-auto mb-4 flex items-center justify-center font-bold text-2xl shadow">
                {{ strtoupper(substr($review->seller->name ?? 'P', 0, 2)) }}
            </div>
            @endif
            <h1 class="text-xl font-bold text-slate-900">{{ $review->seller->name ?? 'Professional' }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $review->seller->title ?? $review->seller->designation ?? '' }}</p>
            <p class="text-xs text-teal-700 font-semibold mt-3">
                <i class="fa-solid fa-star text-amber-400"></i>
                You've been invited to leave a review
            </p>
        </div>

        @if(session('success'))
        <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-2xl flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('review.store', $review->review_token) }}" method="POST" id="reviewForm">
            @csrf

            {{-- Name + Email --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 mb-4">
                <h2 class="font-bold text-slate-900 mb-4">Your Details</h2>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Your Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="reviewer_name" required
                               value="{{ old('reviewer_name', auth()->user()?->name ?? $review->reviewer_name) }}"
                               placeholder="Your full name"
                               class="w-full px-4 py-3 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:border-teal-600 focus:ring-2 focus:ring-teal-50 transition">
                        @error('reviewer_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Email <span class="text-slate-400 text-xs font-normal">(optional — for updates)</span>
                        </label>
                        <input type="email" name="reviewer_email"
                               value="{{ old('reviewer_email', auth()->user()?->email ?? $review->reviewer_email) }}"
                               placeholder="your@email.com"
                               class="w-full px-4 py-3 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:border-teal-600 focus:ring-2 focus:ring-teal-50 transition">
                    </div>
                </div>
            </div>

            {{-- Star Rating --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 mb-4">
                <h2 class="font-bold text-slate-900 mb-1">Your Rating <span class="text-red-500">*</span></h2>
                <p class="text-xs text-slate-400 mb-5">How was your overall experience?</p>

                <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating') }}">

                <div class="flex items-center justify-center gap-3 mb-4" id="starRow">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button" onclick="setRating({{ $i }})"
                        class="star-btn text-4xl text-slate-200 hover:text-amber-400 transition-all hover:scale-110"
                        data-star="{{ $i }}">
                        <i class="fa-solid fa-star"></i>
                    </button>
                    @endfor
                </div>

                <p class="text-center text-sm font-bold text-slate-400 h-5" id="ratingLabel"></p>
                @error('rating')<p class="text-red-500 text-xs text-center mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Review Text --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 mb-4">
                <h2 class="font-bold text-slate-900 mb-1">Your Review <span class="text-red-500">*</span></h2>
                <p class="text-xs text-slate-400 mb-4">Share your experience to help others find the right professional</p>
                <textarea name="review" id="reviewText" rows="5" required minlength="5" maxlength="1000"
                    placeholder="Describe your experience — was the professional helpful, punctual, skilled? What would you tell a friend?"
                    class="w-full px-4 py-3 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:border-teal-600 focus:ring-2 focus:ring-teal-50 transition resize-none">{{ old('review') }}</textarea>
                <div class="flex justify-between mt-1.5">
                    <p class="text-xs text-red-500 hidden" id="reviewError">Please write at least 5 characters.</p>
                    <p class="text-xs text-slate-400 ml-auto"><span id="charCount">0</span>/1000</p>
                </div>
                @error('review')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Tags --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 mb-6">
                <h2 class="font-bold text-slate-900 mb-1">Highlights
                    <span class="text-xs font-normal text-slate-400">(optional)</span>
                </h2>
                <p class="text-xs text-slate-400 mb-4">Pick what stood out</p>
                <div class="flex flex-wrap gap-2">
                    @foreach(['Punctual','Professional','Great communication','Good value','Highly skilled','Friendly','Fast service','Would recommend'] as $tag)
                    <button type="button" onclick="toggleTag(this, '{{ $tag }}')"
                        class="tag-btn px-3 py-1.5 rounded-xl text-xs font-semibold border border-slate-200 text-slate-500 bg-white hover:border-teal-300 hover:text-teal-700 transition"
                        data-tag="{{ $tag }}">
                        {{ $tag }}
                    </button>
                    @endforeach
                </div>
                <input type="hidden" name="tags" id="tagsInput">
            </div>

            @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-2xl">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <button type="submit" id="submitBtn" onclick="return validateReview()"
                class="w-full bg-slate-300 text-slate-500 font-bold py-4 rounded-2xl text-sm transition cursor-not-allowed"
                disabled>
                <i class="fa-solid fa-paper-plane mr-2"></i> Submit Review
            </button>

            <p class="text-center text-xs text-slate-400 mt-4">
                Your review helps {{ $review->seller->name ?? 'this professional' }} grow their business and helps others make informed decisions.
            </p>
        </form>

    </div>
</div>

<script>
let selectedRating = {{ old('rating', 0) }};
const selectedTags = new Set();
const labels = ['','Terrible','Poor','Average','Good','Excellent'];

if (selectedRating > 0) setRating(selectedRating);

function setRating(n) {
    selectedRating = n;
    document.getElementById('ratingInput').value = n;
    document.querySelectorAll('.star-btn').forEach((btn, i) => {
        const star = btn.querySelector('i');
        if (i < n) {
            btn.classList.remove('text-slate-200');
            btn.classList.add('text-amber-400');
        } else {
            btn.classList.remove('text-amber-400');
            btn.classList.add('text-slate-200');
        }
    });
    document.getElementById('ratingLabel').textContent = labels[n];
    document.getElementById('ratingLabel').className = 'text-center text-sm font-bold h-5 ' +
        (n >= 4 ? 'text-emerald-500' : n === 3 ? 'text-amber-500' : 'text-red-500');
    updateSubmit();
}

function toggleTag(btn, tag) {
    if (selectedTags.has(tag)) {
        selectedTags.delete(tag);
        btn.classList.remove('border-teal-600','text-teal-700','bg-teal-50');
        btn.classList.add('border-slate-200','text-slate-500','bg-white');
    } else {
        selectedTags.add(tag);
        btn.classList.remove('border-slate-200','text-slate-500','bg-white');
        btn.classList.add('border-teal-600','text-teal-700','bg-teal-50');
    }
    document.getElementById('tagsInput').value = [...selectedTags].join(',');
}

document.getElementById('reviewText').addEventListener('input', function() {
    document.getElementById('charCount').textContent = this.value.length;
    updateSubmit();
});

function updateSubmit() {
    const ready = selectedRating > 0 && document.getElementById('reviewText').value.trim().length >= 5
        && document.querySelector('[name=reviewer_name]').value.trim().length >= 2;
    const btn = document.getElementById('submitBtn');
    btn.disabled = !ready;
    btn.className = `w-full font-bold py-4 rounded-2xl text-sm transition ${
        ready ? 'bg-teal-700 hover:bg-teal-800 text-white cursor-pointer' : 'bg-slate-300 text-slate-500 cursor-not-allowed'
    }`;
}

document.querySelector('[name=reviewer_name]').addEventListener('input', updateSubmit);
updateSubmit();

function validateReview() {
    if (!selectedRating) { alert('Please select a star rating.'); return false; }
    if (document.getElementById('reviewText').value.trim().length < 5) {
        document.getElementById('reviewError').classList.remove('hidden');
        document.getElementById('reviewText').focus();
        return false;
    }
    document.getElementById('reviewError').classList.add('hidden');
    return true;
}
</script>
@endsection
