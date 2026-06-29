<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'PSPP Platform')</title>

    <meta name="description"
        content="PSPP Platform ระบบบริหารโรงเรียนพระปริยัติธรรม แผนกสามัญศึกษา กลุ่มจังหวัดแพร่">

    <meta name="keywords"
        content="PSPP, School Management System, Laravel, โรงเรียนพระปริยัติธรรม">

    <meta name="author" content="Wisdom Pier Co., Ltd.">

    <link rel="icon" href="{{ asset('favicon.ico') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800 antialiased">

    {{-- =========================
         Navigation
    ========================== --}}
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow">
        <div class="max-w-7xl mx-auto px-4">

            <div class="flex items-center justify-between h-16">

                <a href="{{ url('/') }}"
                    class="text-2xl font-bold text-blue-700">
                    PSPP Platform
                </a>

                <div class="hidden md:flex items-center space-x-6">

                    <a href="#about"
                        class="hover:text-blue-600">
                        เกี่ยวกับ
                    </a>

                    <a href="#modules"
                        class="hover:text-blue-600">
                        โมดูล
                    </a>

                    <a href="#schools"
                        class="hover:text-blue-600">
                        โรงเรียน
                    </a>

                    <a href="#contact"
                        class="hover:text-blue-600">
                        ติดต่อ
                    </a>

                    @guest

                        <a href="{{ route('login') }}"
                            class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                            Login
                        </a>

                        <a href="{{ route('register') }}"
                            class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">
                            Register
                        </a>

                    @else

                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                            Dashboard
                        </a>

                    @endguest

                </div>

            </div>

        </div>
    </nav>

    {{-- =========================
         Main Content
    ========================== --}}
    <main class="pt-16">

        @yield('content')

    </main>

    

    @stack('scripts')

</body>

</html>