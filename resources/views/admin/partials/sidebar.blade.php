<aside class="w-72 bg-slate-950 text-slate-200 min-h-screen shadow-2xl hidden md:flex flex-col">

    {{-- ========================= --}}
    {{-- Logo --}}
    {{-- ========================= --}}
    <div class="px-6 py-6 border-b border-slate-800">

        <div class="flex items-center gap-4">

            <div
                class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">

                <span class="text-white text-2xl font-bold">
                    P
                </span>

            </div>

            <div>

                <h2 class="text-xl font-bold text-white">
                    PSPP Platform
                </h2>

                <p class="text-xs text-slate-400">
                    School Management System
                </p>

            </div>

        </div>

    </div>


    {{-- ========================= --}}
    {{-- User Card --}}
    {{-- ========================= --}}
    <div class="px-4 py-5">

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4">

            <div class="flex items-center gap-3">

                <div
                    class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">

                    {{ strtoupper(substr(auth()->user()->name,0,1)) }}

                </div>

                <div class="flex-1">

                    <h3 class="font-semibold text-white">

                        {{ auth()->user()->name }}

                    </h3>

                    <p class="text-xs text-slate-400">

                        {{ optional(auth()->user()->school)->school }}

                    </p>

                    <div class="flex items-center gap-2 mt-1">

                        <span class="w-2 h-2 rounded-full bg-green-500"></span>

                        <span class="text-[11px] text-green-400">

                            Online

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================= --}}
    {{-- Navigation --}}
    {{-- ========================= --}}
    <nav class="flex-1 overflow-y-auto px-4 pb-6 space-y-8">

        {{-- ========================= --}}
        {{-- Dashboard --}}
        {{-- ========================= --}}
        <div>

            <p class="text-[11px] uppercase tracking-[0.25em] text-slate-500 mb-3">

                Dashboard

            </p>

            <a href="/"
                class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                {{ request()->is('/') ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-purple-800 hover:translate-x-1' }}">

                <span class="text-lg">
                    🏠
                </span>

                <span class="font-medium">
                    หน้าแรก
                </span>

            </a>

        </div>


        {{-- ========================= --}}
        {{-- Academic --}}
        {{-- ========================= --}}
        <div>

            <p class="text-[11px] uppercase tracking-[0.25em] text-slate-500 mb-3">

                Academic

            </p>

            <div class="space-y-2">

                <a href="{{ route('admin.subjects.index') }}"
                    class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                    {{ request()->routeIs('admin.subjects.*') ? 'bg-blue-600 text-white shadow' : 'hover:bg-purple-800 hover:translate-x-1' }}">

                    <span class="text-lg">📚</span>

                    <span>รายวิชา</span>

                </a>

                <a href="{{ route('admin.classrooms.index') }}"
                    class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                    {{ request()->routeIs('admin.classrooms.*') ? 'bg-blue-600 text-white shadow' : 'hover:bg-purple-800 hover:translate-x-1' }}">

                    <span class="text-lg">🏫</span>

                    <span>ห้องเรียน</span>

                </a>

                <a href="{{ route('admin.schedules.index') }}"
                    class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                    {{ request()->routeIs('admin.schedules.*') ? 'bg-blue-600 text-white shadow' : 'hover:bg-purple-800 hover:translate-x-1' }}">

                    <span class="text-lg">📅</span>

                    <span>ตารางสอน</span>

                </a>

                <a href="{{ route('admin.enrollments.import') }}"
                    class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                    {{ request()->routeIs('admin.enrollments.import') ? 'bg-blue-600 text-white shadow' : 'hover:bg-purple-800 hover:translate-x-1' }}">

                    <span class="text-lg">📥</span>

                    <span>Import นักเรียน</span>

                </a>

                <a href="{{ route('admin.enrollments.index') }}"
                    class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                    {{ request()->routeIs('admin.enrollments.*') ? 'bg-blue-600 text-white shadow' : 'hover:bg-purple-800 hover:translate-x-1' }}">

                    <span class="text-lg">🎓</span>

                    <span>ลงทะเบียนเรียน</span>

                </a>

            </div>

        </div>
                {{-- ========================= --}}
        {{-- Reports --}}
        {{-- ========================= --}}
        <div>

            <p class="text-[11px] uppercase tracking-[0.25em] text-slate-500 mb-3">

                Reports

            </p>

            <div class="space-y-2">

                <a href="/reports/academic"
                    class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:bg-purple-800 hover:translate-x-1">

                    <span class="text-lg">📊</span>

                    <span>รายงานวิชาการ</span>

                </a>

                <a href="/reports/personnel"
                    class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:bg-purple-800 hover:translate-x-1">

                    <span class="text-lg">👨‍🏫</span>

                    <span>รายงานบุคลากร</span>

                </a>

                <a href="/reports/finance"
                    class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:bg-purple-800 hover:translate-x-1">

                    <span class="text-lg">💰</span>

                    <span>รายงานงบประมาณ</span>

                </a>

                <a href="/reports/general"
                    class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:bg-purple-800 hover:translate-x-1">

                    <span class="text-lg">🏢</span>

                    <span>รายงานทั่วไป</span>

                </a>

                <a href="/reports/qa"
                    class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:bg-pink-800 hover:translate-x-1">

                    <span class="text-lg">🏅</span>

                    <span>รายงานประกันคุณภาพ</span>

                </a>

            </div>

        </div>


        {{-- ========================= --}}
        {{-- Tools --}}
        {{-- ========================= --}}
        <div>

            <p class="text-[11px] uppercase tracking-[0.25em] text-slate-500 mb-3">

                Tools

            </p>

            <div class="space-y-2">

                <a href="/calendar"
                    class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:bg-green-800 hover:translate-x-1">

                    <span class="text-lg">🗓️</span>

                    <span>ปฏิทิน</span>

                </a>

                <a href="{{ route('guides.student-import') }}"
                    class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                    {{ request()->routeIs('guides.*') ? 'bg-blue-600 text-white shadow' : 'hover:bg-purple-800 hover:translate-x-1' }}">

                    <span class="text-lg">📘</span>

                    <span>คู่มือ Import นักเรียน</span>

                </a>

            </div>

        </div>

    </nav>


    {{-- ========================= --}}
    {{-- Footer --}}
    {{-- ========================= --}}
    <div class="border-t border-slate-800 p-5 bg-slate-950">

        <div class="bg-slate-900 rounded-2xl border border-slate-800 p-4">

            <div class="flex items-center gap-3">

                <div
                    class="w-10 h-10 rounded-full bg-gradient-to-r from-red-500 to-red-600 flex items-center justify-center">

                    <span class="text-white">
                        👤
                    </span>

                </div>

                <div>

                    <div class="text-sm font-semibold text-white">

                        {{ auth()->user()->name }}

                    </div>

                    <div class="text-xs text-slate-400">

                        Administrator

                    </div>

                </div>

            </div>

            <form method="POST"
                action="{{ route('logout') }}"
                class="mt-4">

                @csrf

                <button
                    class="w-full py-3 rounded-xl bg-gradient-to-r from-red-600 to-red-700 hover:from-blue-700 hover:to-red-800 transition font-semibold text-white shadow">

                    🚪 ออกจากระบบ

                </button>

            </form>

        </div>

        <div class="mt-5 text-center">

            <div class="text-sm font-semibold text-slate-300">

                PSPP Platform

            </div>

            <div class="text-xs text-slate-500 mt-1">

                Phrae Sangha Provincial Platform

            </div>

            <div class="text-[11px] text-slate-600 mt-2">

                Version 1.0 • © {{ date('Y') }}

            </div>

        </div>

    </div>

</aside>