@extends('frontend.layouts._app')
@section('title', 'Join Zonely')
{{-- Signup form has no search value; keep it out of the index, still follow its links. --}}
@section('robots', 'noindex, follow')

@section('content')
<div class="min-h-screen bg-slate-50 flex items-center justify-center px-4 pt-20 pb-16">
    <div class="w-full max-w-sm">

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 sm:p-10">

            {{-- Logo + heading --}}
            <div class="text-center mb-8">
                <a href="{{ route('frontend.home') }}" class="inline-block mb-5">
                    <img src="{{ asset('frontend/img/zonely_logo.png') }}" class="w-12 h-12 mx-auto" alt="Zonely">
                </a>
                <h1 class="text-2xl font-black text-slate-900">Join Zonely</h1>
                <p class="text-sm text-slate-500 mt-1">Choose how you'll use the platform</p>
            </div>

            <div class="space-y-3">

                {{-- Seller option --}}
                <a href="{{ route('user.register', 'seller') }}"
                   class="flex items-center gap-4 p-5 rounded-2xl border-2 border-teal-100 bg-teal-50 hover:border-teal-600 hover:bg-teal-100 transition group">
                    <div class="w-12 h-12 rounded-xl bg-teal-700 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-briefcase text-white text-base"></i>
                    </div>
                    <div class="flex-1 text-left">
                        <p class="font-black text-slate-900 text-sm">I'm a Service Provider</p>
                        <p class="text-xs text-slate-500 mt-0.5">Get leads, grow your business</p>
                    </div>
                    <i class="fa-solid fa-arrow-right text-slate-300 group-hover:text-teal-700 transition text-sm shrink-0"></i>
                </a>

                {{-- Buyer option --}}
                <a href="{{ route('user.register', 'user') }}"
                   class="flex items-center gap-4 p-5 rounded-2xl border-2 border-slate-100 bg-white hover:border-emerald-400 hover:bg-emerald-50 transition group">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-magnifying-glass text-white text-base"></i>
                    </div>
                    <div class="flex-1 text-left">
                        <p class="font-black text-slate-900 text-sm">I'm Looking for Help</p>
                        <p class="text-xs text-slate-500 mt-0.5">Find trusted local experts</p>
                    </div>
                    <i class="fa-solid fa-arrow-right text-slate-300 group-hover:text-emerald-600 transition text-sm shrink-0"></i>
                </a>

            </div>

            <p class="text-center text-sm text-slate-500 mt-6">
                Already have an account?
                <a href="{{ route('user.login') }}" class="text-teal-700 font-bold hover:underline">Sign in</a>
            </p>

        </div>

    </div>
</div>
@endsection
