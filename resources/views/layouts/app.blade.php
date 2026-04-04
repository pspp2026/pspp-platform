<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>PSPP Admin</title>

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- 🔥 Cropper CSS --}}
    <link href="https://unpkg.com/cropperjs/dist/cropper.min.css" rel="stylesheet"/>

    {{-- 🔥 เปิดให้แต่ละหน้าเพิ่ม CSS --}}
    @stack('styles')
</head>

<body class="bg-gray-100">

    <!-- Navbar -->
<nav class="bg-purple-900 text-white px-6 py-4 flex justify-between items-center">

    {{-- 🔵 ซ้าย --}}
    <h2 class="text-xl font-bold">PSPP SYSTEM</h2>

    {{-- 🔴 ขวา --}}
    <div class="flex items-center gap-3">

        {{-- HOME --}}
        <a href="{{ route('home') }}"
           class="bg-white text-purple-800 px-3 py-1 rounded text-sm hover:bg-gray-200">
            🏠 HOME
        </a>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="bg-red-500 px-3 py-1 rounded hover:bg-red-600">
                Logout
            </button>
        </form>

    </div>

</nav>

    <!-- Content -->
    <div class="p-6">
        @yield('content')
    </div>

    {{-- 🔥 Cropper JS --}}
    <script src="https://unpkg.com/cropperjs"></script>

    {{-- 🔥 เปิดให้แต่ละหน้าเพิ่ม JS --}}
    @stack('scripts')

</body>
</html>