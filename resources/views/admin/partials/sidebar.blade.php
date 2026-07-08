<aside
    class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-slate-950 text-slate-200 shadow-2xl
           transform transition-transform duration-300 ease-in-out
           -translate-x-full md:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    {{-- Logo --}}
    <div class="flex items-center justify-between border-b border-slate-800 px-5 py-5">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg">
                <span class="text-xl font-bold text-white">P</span>
            </div>

            <div>
                <h2 class="text-lg font-bold text-white">PSPP Platform</h2>
                <p class="text-xs text-slate-400">School Management System</p>
            </div>
        </a>

        <button type="button"
                @click="sidebarOpen = false"
                class="rounded-lg p-2 text-slate-300 hover:bg-slate-800 md:hidden"
                aria-label="ปิดเมนู">
            ✕
        </button>
    </div>

    {{-- User Card --}}
    <div class="px-4 py-4">
        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 text-lg font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div class="min-w-0 flex-1">
                    <h3 class="truncate font-semibold text-white">
                        {{ auth()->user()->name }}
                    </h3>

                    <p class="truncate text-xs text-slate-400">
                        {{ optional(auth()->user()->school)->school_name ?? 'ผู้ดูแลระบบ' }}
                    </p>

                    <div class="mt-1 flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                        <span class="text-[11px] text-green-400">Online</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 space-y-7 overflow-y-auto px-4 pb-6">

        <div>
            <p class="mb-3 text-[11px] uppercase tracking-[0.25em] text-slate-500">Dashboard</p>

            <a href="{{ route('admin.dashboard') }}"
               @click="sidebarOpen = false"
               class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200
               {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-purple-800 hover:translate-x-1' }}">
                <span class="text-lg">🏠</span>
                <span class="font-medium">ภาพรวม</span>
            </a>
        </div>

        <div>
            <p class="mb-3 text-[11px] uppercase tracking-[0.25em] text-slate-500">Academic</p>

            <div class="space-y-2">
                <a href="{{ route('admin.subjects.index') }}"
                   @click="sidebarOpen = false"
                   class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200
                   {{ request()->routeIs('admin.subjects.*') ? 'bg-blue-600 text-white shadow' : 'hover:bg-purple-800 hover:translate-x-1' }}">
                    <span class="text-lg">📚</span>
                    <span>รายวิชา</span>
                </a>

                <a href="{{ route('admin.classrooms.index') }}"
                   @click="sidebarOpen = false"
                   class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200
                   {{ request()->routeIs('admin.classrooms.*') ? 'bg-blue-600 text-white shadow' : 'hover:bg-purple-800 hover:translate-x-1' }}">
                    <span class="text-lg">🏫</span>
                    <span>ห้องเรียน</span>
                </a>

                <a href="{{ route('admin.schedules.index') }}"
                   @click="sidebarOpen = false"
                   class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200
                   {{ request()->routeIs('admin.schedules.*') ? 'bg-blue-600 text-white shadow' : 'hover:bg-purple-800 hover:translate-x-1' }}">
                    <span class="text-lg">📅</span>
                    <span>ตารางสอน</span>
                </a>

                <a href="{{ route('admin.students.index') }}"
                   @click="sidebarOpen = false"
                   class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200
                   {{ request()->routeIs('admin.students.*') ? 'bg-blue-600 text-white shadow' : 'hover:bg-purple-800 hover:translate-x-1' }}">
                    <span class="text-lg">🎓</span>
                    <span>รายชื่อนักเรียน</span>
                </a>

                <a href="{{ route('admin.enrollments.import') }}"
                   @click="sidebarOpen = false"
                   class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200
                   {{ request()->routeIs('admin.enrollments.import') ? 'bg-blue-600 text-white shadow' : 'hover:bg-purple-800 hover:translate-x-1' }}">
                    <span class="text-lg">📥</span>
                    <span>Import นักเรียน</span>
                </a>

                <a href="{{ route('admin.enrollments.index') }}"
                   @click="sidebarOpen = false"
                   class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200
                   {{ request()->routeIs('admin.enrollments.*') ? 'bg-blue-600 text-white shadow' : 'hover:bg-purple-800 hover:translate-x-1' }}">
                    <span class="text-lg">🎓</span>
                    <span>ลงทะเบียนเรียน</span>
                </a>
            </div>
        </div>

        <div>
            <p class="mb-3 text-[11px] uppercase tracking-[0.25em] text-slate-500">Reports</p>

            <div class="space-y-2">
                <a href="/reports/academic" @click="sidebarOpen = false" class="group flex items-center gap-3 rounded-xl px-4 py-3 transition hover:bg-purple-800 hover:translate-x-1">
                    <span class="text-lg">📊</span><span>รายงานวิชาการ</span>
                </a>
                <a href="/reports/personnel" @click="sidebarOpen = false" class="group flex items-center gap-3 rounded-xl px-4 py-3 transition hover:bg-purple-800 hover:translate-x-1">
                    <span class="text-lg">👨‍🏫</span><span>รายงานบุคลากร</span>
                </a>
                <a href="/reports/finance" @click="sidebarOpen = false" class="group flex items-center gap-3 rounded-xl px-4 py-3 transition hover:bg-purple-800 hover:translate-x-1">
                    <span class="text-lg">💰</span><span>รายงานงบประมาณ</span>
                </a>
                <a href="/reports/general" @click="sidebarOpen = false" class="group flex items-center gap-3 rounded-xl px-4 py-3 transition hover:bg-purple-800 hover:translate-x-1">
                    <span class="text-lg">🏢</span><span>รายงานทั่วไป</span>
                </a>
                <a href="/reports/qa" @click="sidebarOpen = false" class="group flex items-center gap-3 rounded-xl px-4 py-3 transition hover:bg-pink-800 hover:translate-x-1">
                    <span class="text-lg">🏅</span><span>รายงานประกันคุณภาพ</span>
                </a>
            </div>
        </div>

        <div>
            <p class="mb-3 text-[11px] uppercase tracking-[0.25em] text-slate-500">Tools</p>

            <div class="space-y-2">
                <a href="/calendar" @click="sidebarOpen = false" class="group flex items-center gap-3 rounded-xl px-4 py-3 transition hover:bg-green-800 hover:translate-x-1">
                    <span class="text-lg">🗓️</span><span>ปฏิทิน</span>
                </a>

                <a href="{{ route('guides.student-import') }}"
                   @click="sidebarOpen = false"
                   class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200
                   {{ request()->routeIs('guides.*') ? 'bg-blue-600 text-white shadow' : 'hover:bg-purple-800 hover:translate-x-1' }}">
                    <span class="text-lg">📘</span>
                    <span>คู่มือ Import นักเรียน</span>
                </a>
            </div>
        </div>
    </nav>

    {{-- Footer --}}
    <div class="border-t border-slate-800 bg-slate-950 p-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                    class="w-full rounded-xl bg-gradient-to-r from-red-600 to-red-700 py-3 font-semibold text-white shadow transition hover:from-red-700 hover:to-red-800">
                🚪 ออกจากระบบ
            </button>
        </form>

        <div class="mt-4 text-center">
            <div class="text-sm font-semibold text-slate-300">PSPP Platform</div>
            <div class="mt-1 text-xs text-slate-500">Phrae Sangha Provincial Platform</div>
            <div class="mt-2 text-[11px] text-slate-600">Version 1.0 • © {{ date('Y') }}</div>
        </div>
    </div>
</aside>