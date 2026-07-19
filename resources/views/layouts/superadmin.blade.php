<!DOCTYPE html>
<html lang="th">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'PSPP Super Administrator')</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Cropper --}}
    <link href="https://unpkg.com/cropperjs/dist/cropper.min.css" rel="stylesheet"/>

    @stack('styles')

</head>

<body class="bg-slate-100 text-slate-800">

<div
    x-data="{ sidebarOpen: false }"
    class="min-h-screen">

    {{-- ========================================================= --}}
    {{-- Survey Popup --}}
    {{-- ========================================================= --}}

    @include('partials.survey-popup')

    {{-- ========================================================= --}}
    {{-- Mobile Overlay --}}
    {{-- ========================================================= --}}

    <div
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-black/50 md:hidden"
        style="display:none;">
    </div>

    {{-- ========================================================= --}}
    {{-- Sidebar --}}
    {{-- ========================================================= --}}

    @include('partials.sidebar')

    {{-- ========================================================= --}}
    {{-- Main Area --}}
    {{-- ========================================================= --}}

    <div class="min-h-screen md:pl-72">

        {{-- ================= Mobile Topbar ================= --}}
        <header
            class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 shadow md:hidden">

            <div class="flex items-center gap-3">

                <button
                    @click="sidebarOpen = true"
                    class="rounded-lg p-2 hover:bg-slate-100">

                    ☰

                </button>

                <div>

                    <h1 class="font-bold text-slate-800">

                        🎓 PSPP Platform

                    </h1>

                    <p class="text-xs text-slate-500">

                        Super Administrator

                    </p>

                </div>

            </div>

            <div class="flex items-center gap-2">

                <a href="{{ route('home') }}"
                class="rounded-lg bg-slate-100 px-3 py-2 text-sm hover:bg-slate-200">

                    🏠

                </a>

                <form action="{{ route('logout') }}" method="POST">

                    @csrf

                    <button
                        class="rounded-lg bg-red-500 px-3 py-2 text-white hover:bg-red-600">

                        ⎋

                    </button>

                </form>

                <img
                    src="{{ auth()->user()->profile_image
                        ? asset('storage/'.auth()->user()->profile_image)
                        : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}"
                    class="h-10 w-10 rounded-full border object-cover">

            </div>

        </header>

        {{-- ================= Desktop Topbar ================= --}}
        <header
            class="hidden md:flex h-20 items-center justify-between border-b border-slate-200 bg-white px-6 shadow">

            <div>

                <h1 class="text-2xl font-bold text-slate-800">

                    🎓 PSPP Super Administrator

                </h1>

                <p class="mt-1 text-sm text-slate-500">

                    ยินดีต้อนรับ

                    <span class="font-semibold">

                        {{ auth()->user()->name }}

                    </span>

                </p>

            </div>

            <div class="flex items-center gap-4">

                <a
                    href="{{ route('home') }}"
                    class="rounded-lg bg-slate-100 px-4 py-2 text-sm hover:bg-slate-200">

                    🏠 หน้าแรก

                </a>

                <img
                    src="{{ auth()->user()->profile_image
                        ? asset('storage/'.auth()->user()->profile_image)
                        : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}"
                    class="h-11 w-11 rounded-full border object-cover">

                <div class="text-right">

                    <div class="font-semibold">

                        {{ auth()->user()->name }}

                    </div>

                    <div class="text-xs text-slate-500">

                        Super Administrator

                    </div>

                </div>

                <form
                    action="{{ route('logout') }}"
                    method="POST">

                    @csrf

                    <button
                        class="rounded-lg bg-red-500 px-4 py-2 text-white hover:bg-red-600">

                        Logout

                    </button>

                </form>

            </div>

        </header>

        {{-- ================= Flash Message ================= --}}
        <main class="p-4 md:p-6">

            @if(session('success'))

                <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">

                    ✅ {{ session('success') }}

                </div>

            @endif

            @if(session('error'))

                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">

                    ⚠️ {{ session('error') }}

                </div>

            @endif

            @yield('content')

        </main>

    </div>

</div>

{{-- Cropper --}}
<script src="https://unpkg.com/cropperjs"></script>

{{-- Alpine --}}
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

@stack('scripts')

</body>

</html>