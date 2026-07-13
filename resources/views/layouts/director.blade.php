<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'PSPP Admin Panel')</title>
 
    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Cropper --}}
    <link href="https://unpkg.com/cropperjs/dist/cropper.min.css" rel="stylesheet"/>

    @stack('styles')
</head>

<body class="bg-slate-100 text-slate-800">

<div x-data="{ sidebarOpen: false }" class="min-h-screen">

    {{-- พื้นหลังเมื่อเปิดเมนูบนมือถือ --}}
    <div x-show="sidebarOpen"
         x-transition.opacity
         @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-black/50 md:hidden"
         style="display: none;">
    </div>

    
    {{-- Sidebar --}}
@include('director.partials.sidebar')

    {{-- พื้นที่เนื้อหา: เว้นซ้ายเฉพาะหน้าจอ md ขึ้นไป --}}
    <div class="min-h-screen md:pl-72">

        {{-- Topbar มือถือ --}}
        <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 shadow-sm md:hidden">
            <div class="flex items-center gap-3">
                <button type="button"
                        @click="sidebarOpen = true"
                        class="rounded-lg p-2 text-slate-700 hover:bg-slate-100"
                        aria-label="เปิดเมนู">
                    ☰
                </button>

                <div>
                    <p class="font-bold text-slate-800">🎓 PSPP Platform</p>
                    <p class="text-xs text-slate-500">Admin Panel</p>
                </div>
            </div>

            <img src="{{ auth()->user()->profile_image
                    ? asset('storage/' . auth()->user()->profile_image)
                    : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                 alt="รูปโปรไฟล์"
                 class="h-9 w-9 rounded-full border border-slate-200 object-cover">
        </header>

        {{-- Topbar คอมพิวเตอร์ --}}
        <header class="hidden h-20 items-center justify-between border-b border-slate-200 bg-white px-6 shadow-sm md:flex">
            <div>
                <h1 class="text-xl font-bold text-slate-800">📊 PSPP Director Dashboard</h1>
                <p class="mt-1 text-sm text-slate-500">
                    ยินดีต้อนรับผู้อำนวยการโรงเรียน
                    {{ auth()->user()->school?->school_name ?? '-' }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}"
                   class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">
                    🏠 หน้าแรก
                </a>

                <img src="{{ auth()->user()->profile_image
                        ? asset('storage/' . auth()->user()->profile_image)
                        : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                     alt="รูปโปรไฟล์"
                     class="h-10 w-10 rounded-full border border-slate-200 object-cover">

                <span class="max-w-40 truncate text-sm font-medium text-slate-700">
                    {{ auth()->user()->name }}
                </span>
            </div>
        </header>

        <main class="p-4 md:p-6">

            {{-- Flash message --}}
            @if (session('success'))
                <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
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

{{-- Alpine.js: ควบคุมปุ่มเปิด/ปิด Sidebar --}}
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

@stack('scripts')

</body>
</html>