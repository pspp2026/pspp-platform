<!DOCTYPE html>
<html lang="th">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PSPP Admin</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Cropper CSS --}}
    <link href="https://unpkg.com/cropperjs/dist/cropper.min.css" rel="stylesheet"/>

    {{-- CSS ของแต่ละหน้า --}}
    @stack('styles')
    
</head>

<body class="bg-gray-100">

    {{-- ========================================================= --}}
    {{-- Survey Popup --}}
    {{-- ========================================================= --}}

    @include('partials.survey-popup')

    {{-- ========================================================= --}}
    {{-- Navbar --}}
    {{-- ========================================================= --}}

    <nav class="bg-purple-900 text-white px-4 md:px-6 h-16 flex items-center justify-between shadow-md sticky top-0 z-50">

        {{-- Logo --}}
      
        <div class="flex items-center gap-3">

            <button
                id="mobile-menu-btn"
                class="md:hidden text-2xl">

                ☰

            </button>

            <h2 class="font-bold text-lg md:text-2xl">

                PSPP SYSTEM

            </h2>

        </div>

        {{-- Right Menu --}}
        <div class="flex items-center gap-3">

            <a href="{{ route('home') }}"
               class="bg-white text-purple-800 px-3 py-1 rounded text-sm hover:bg-gray-200">

                🏠 HOME

            </a>

            <form method="POST" action="{{ route('logout') }}">

                @csrf

                <button
                    class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded">

                    Logout

                </button>

            </form>

        </div>

    </nav>

    {{-- ========================================================= --}}
    {{-- Main Content --}}
    {{-- ========================================================= --}}

    <div class="p-6">

        @yield('content')

    </div>

    {{-- ========================================================= --}}
    {{-- Cropper JS --}}
    {{-- ========================================================= --}}

    <script src="https://unpkg.com/cropperjs"></script>

    {{-- JS ของแต่ละหน้า --}}
    @stack('scripts')

</body>

</html>