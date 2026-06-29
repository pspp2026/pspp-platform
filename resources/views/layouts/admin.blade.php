<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSPP Admin Panel</title>

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Cropper --}}
    <link href="https://unpkg.com/cropperjs/dist/cropper.min.css" rel="stylesheet"/>

    @stack('styles')
</head>

<body class="bg-slate-100">

    {{-- TOP NAVBAR --}}
    <nav class="bg-purple-900 text-white shadow-lg">

        <div class="flex justify-between items-center px-6 py-4">

            <div>
                <h1 class="text-xl font-bold">
                    PSPP PLATFORM
                </h1>

                <p class="text-xs text-purple-200">
                    School Management System
                </p>
            </div>

            <div class="flex items-center gap-3">

                <a href="{{ route('home') }}"
                   class="bg-white text-purple-800 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100">
                    🏠 HOME
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-sm">
                        Logout
                    </button>
                </form>

            </div>

        </div>

    </nav>

    {{-- MAIN LAYOUT --}}
    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        @include('admin.partials.sidebar')

        {{-- CONTENT --}}
        <main class="flex-1 p-6">

           <div class="flex min-h-screen bg-gray-100">

    <!-- 🟡 MAIN -->
    <div class="flex-1 flex flex-col">

        <!-- 🔷 TOPBAR -->
        <div class="bg-white shadow px-6 py-4 flex justify-between items-center">

            <div>
                <h1 class="text-xl font-bold">📊 Admin Dashboard</h1>
                <p class="text-sm text-gray-500">
                    ยินดีต้อนรับแอดมินโรงเรียน {{ auth()->user()->school->school_name }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <img 
                    src="{{ auth()->user()->profile_image 
                        ? asset('storage/' . auth()->user()->profile_image) 
                        : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                    class="w-10 h-10 rounded-full border">

                <span class="text-sm">{{ auth()->user()->name }}</span>
            </div>

        </div>

            {{-- FLASH MESSAGE --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            {{-- PAGE CONTENT --}}
            @yield('content')

        </main>

    </div>

    {{-- Cropper --}}
    <script src="https://unpkg.com/cropperjs"></script>

    @stack('scripts')

</body>
</html>