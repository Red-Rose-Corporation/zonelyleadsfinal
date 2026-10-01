@php
    $meta_title       = 'Blog – Local Trends & Expert Guides | Zonely';
    $meta_description = 'Tips, guides, and news for finding and hiring the best local professionals near you.';
    $meta_keywords    = $meta_keywords ?? '';
@endphp
@extends('frontend.layouts._app')
@section('title', 'Blog')
@section('og_title', $meta_title)
@section('og_description', $meta_description)
@section('og_image', asset('frontend/img/zonely_logo.png'))

@section('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Blog",
  "name": "Zonely Blog",
  "url": "{{ route('frontend.blog') }}",
  "publisher": { "@type": "Organization", "name": "Zonely", "logo": { "@type": "ImageObject", "url": "{{ asset('frontend/img/zonely_logo.png') }}" } }
}
</script>
@endsection

@section('content')
<main class="mt-16 sm:mt-20 max-w-7xl mx-auto px-4 sm:px-6 pb-12">

    {{-- Header --}}
    <header class="pt-10 pb-10 sm:pt-14 sm:pb-14">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-slate-100 pb-8 sm:pb-12">
            <div class="max-w-2xl">
                <span class="text-teal-700 text-[11px] font-black uppercase tracking-widest mb-3 block">Neighborhood Pulse</span>
                <h1 class="font-serif text-3xl sm:text-5xl md:text-6xl leading-tight text-slate-900">
                    Local <span class="italic text-slate-400">Trends</span> &amp; Expert Guides.
                </h1>
            </div>
            <div class="flex flex-wrap gap-2">
                <span class="text-xs font-bold text-slate-400 self-center mr-1">Browse by:</span>
                <button class="px-4 py-2 rounded-full border border-slate-200 text-xs font-bold hover:bg-teal-700 hover:text-white transition">City Guides</button>
                <button class="px-4 py-2 rounded-full border border-slate-200 text-xs font-bold hover:bg-teal-700 hover:text-white transition">State Laws</button>
                <button class="px-4 py-2 rounded-full border border-slate-200 text-xs font-bold hover:bg-teal-700 hover:text-white transition">Market News</button>
            </div>
        </div>
    </header>

    {{-- Featured + sidebar --}}
    @php $blogs = $blogs ?? collect(); $featuredBlog = $featuredBlog ?? null; @endphp

    @if($blogs->isEmpty() && !$featuredBlog)
    {{-- Empty state --}}
    <div class="flex flex-col items-center justify-center py-24 text-center">
        <div class="w-20 h-20 bg-slate-100 rounded-3xl flex items-center justify-center mb-6">
            <i class="fa-regular fa-newspaper text-3xl text-slate-300"></i>
        </div>
        <h2 class="font-serif text-2xl text-slate-700 mb-2">No articles yet</h2>
        <p class="text-slate-400 text-sm max-w-xs">Check back soon — expert guides and local tips are on the way.</p>
    </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 mb-14">

        {{-- Featured --}}
        @if($featuredBlog)
        <article class="lg:col-span-8 group cursor-pointer">
            <a href="{{ route('frontend.blog') }}/{{ $featuredBlog->slug ?? '' }}" class="block" style="min-height:unset;">
                <div class="rounded-3xl overflow-hidden aspect-video mb-6 bg-gradient-to-br from-teal-800 to-teal-600 relative">
                    @if($featuredBlog->image_path)
                    <img src="{{ get_file($featuredBlog->image_path, 'blog') }}"
                         onerror="this.style.opacity='0'"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                         alt="{{ $featuredBlog->name }}" loading="eager">
                    @endif
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-20">
                        <i class="fa-regular fa-newspaper text-white text-8xl"></i>
                    </div>
                    <div class="absolute bottom-5 left-5 bg-white/90 backdrop-blur px-4 py-2 rounded-xl shadow-lg">
                        <p class="text-[10px] font-black text-teal-700 uppercase tracking-widest">Top Rated Guide</p>
                    </div>
                </div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">
                    {{ $featuredBlog->created_at?->format('M Y') }}
                </div>
                <h2 class="font-serif text-2xl sm:text-3xl md:text-4xl mb-3 group-hover:text-teal-700 transition leading-snug">
                    {{ $featuredBlog->name }}
                </h2>
                <p class="text-slate-500 text-sm sm:text-base leading-relaxed line-clamp-3">
                    {{ $featuredBlog->short_description ?? Str::limit(strip_tags($featuredBlog->description ?? ''), 200) }}
                </p>
            </a>
        </article>
        @endif

        {{-- Sidebar list --}}
        <div class="{{ $featuredBlog ? 'lg:col-span-4' : 'lg:col-span-12' }} space-y-5">
            <h4 class="text-xs font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 pb-3">More Articles</h4>
            @forelse($blogs as $blog)
            <article class="group flex gap-4 items-start">
                <div class="w-20 h-20 shrink-0 rounded-2xl overflow-hidden bg-gradient-to-br from-teal-700 to-teal-500 flex items-center justify-center">
                    @if($blog->image_path)
                    <img src="{{ get_file($blog->image_path, 'blog') }}"
                         onerror="this.style.opacity='0'"
                         class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-500"
                         alt="{{ $blog->name }}" loading="lazy">
                    @else
                    <i class="fa-regular fa-newspaper text-white/50 text-xl"></i>
                    @endif
                </div>
                <div class="min-w-0">
                    <a href="{{ route('frontend.blog') }}/{{ $blog->slug ?? '' }}"
                       class="font-serif text-base leading-snug group-hover:text-teal-700 transition line-clamp-3 block"
                       style="min-height:unset;">
                        {{ $blog->name }}
                    </a>
                    @if($blog->created_at ?? false)
                    <p class="text-[10px] text-slate-400 mt-1">{{ $blog->created_at->format('M d, Y') }}</p>
                    @endif
                </div>
            </article>
            @empty
            <p class="text-slate-400 text-sm py-4">No more articles.</p>
            @endforelse
        </div>
    </div>
    @endif

    {{-- All articles (paginated) --}}
    @php $allBlogs = $allBlogs ?? null; @endphp
    @if($allBlogs && $allBlogs->count())
    <section class="mb-14">
        <h4 class="text-xs font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 pb-3 mb-8">All Articles</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($allBlogs as $blog)
            <article class="group">
                <a href="{{ route('frontend.blog') }}/{{ $blog->slug ?? '' }}" class="block" style="min-height:unset;">
                    <div class="rounded-2xl overflow-hidden aspect-video mb-4 bg-gradient-to-br from-teal-700 to-teal-500 flex items-center justify-center">
                        @if($blog->image_path)
                        <img src="{{ get_file($blog->image_path, 'blog') }}"
                             onerror="this.style.opacity='0'"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                             alt="{{ $blog->name }}" loading="lazy">
                        @else
                        <i class="fa-regular fa-newspaper text-white/50 text-2xl"></i>
                        @endif
                    </div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                        {{ $blog->created_at?->format('M d, Y') }}
                    </div>
                    <h3 class="font-serif text-lg leading-snug group-hover:text-teal-700 transition line-clamp-2">
                        {{ $blog->name }}
                    </h3>
                </a>
            </article>
            @endforeach
        </div>
        <div class="mt-10">
            {{ $allBlogs->links('pagination::tailwind') }}
        </div>
    </section>
    @endif

    {{-- CTA --}}
    <section class="bg-teal-700 rounded-3xl p-8 sm:p-12 md:p-14 flex flex-col md:flex-row items-center justify-between gap-8">
        <div class="max-w-xl text-white text-center md:text-left">
            <h2 class="font-serif text-2xl sm:text-3xl md:text-4xl mb-3 leading-tight">
                Find a trusted <span class="italic text-teal-200">Local Expert</span> today.
            </h2>
            <p class="text-teal-100 text-sm sm:text-base opacity-90">
                Stop searching globally for local problems. Connect with verified professionals in your zip code.
            </p>
        </div>
        <a href="{{ route('frontend.home') }}"
           class="shrink-0 bg-white text-teal-800 font-black px-6 py-3.5 rounded-2xl hover:shadow-2xl hover:-translate-y-1 transition-all text-sm whitespace-nowrap"
           style="min-height:unset;">
            Search Experts Near Me →
        </a>
    </section>

</main>
@endsection
