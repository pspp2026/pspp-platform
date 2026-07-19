{{-- Sidebar --}}
<aside
    class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-slate-900 text-white shadow-2xl
           transform transition-transform duration-300 ease-in-out
           md:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    {{-- Header --}}
    <div class="flex items-center justify-between border-b border-slate-700 px-6 py-5">

        <div>
            <h2 class="text-xl font-bold">
                🎓 PSPP Platform
            </h2>

            <p class="mt-1 text-sm text-slate-400">
                Super Administrator
            </p>
        </div>

        {{-- ปุ่มปิดบนมือถือ --}}
        <button
            @click="sidebarOpen = false"
            class="rounded-lg p-2 hover:bg-slate-800 md:hidden">

            ✕

        </button>

    </div>

    {{-- User --}}
    <div class="border-b border-slate-800 px-6 py-5">

        <div class="flex items-center gap-3">

            <img
                src="{{ auth()->user()->profile_image
                    ? asset('storage/' . auth()->user()->profile_image)
                    : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                class="h-12 w-12 rounded-full border-2 border-slate-600 object-cover">

            <div>

                <div class="font-semibold">

                    {{ auth()->user()->name }}

                </div>

                <div class="text-xs text-slate-400">

                    {{ auth()->user()->email }}

                </div>

            </div>

        </div>

    </div>

    {{-- Menu --}}
    <nav class="flex-1 overflow-y-auto px-4 py-5 space-y-6">

        {{-- Dashboard --}}
        <div>

            <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-widest text-slate-500">
                Dashboard
            </p>

            <a href="{{ route('superadmin.dashboard') }}"
               class="flex items-center gap-3 rounded-xl px-4 py-3 transition
               {{ request()->routeIs('superadmin.dashboard')
                    ? 'bg-indigo-600 text-white shadow'
                    : 'hover:bg-slate-800' }}">

                <span class="text-lg">🏠</span>

                <span>Dashboard</span>

            </a>

        </div>

        {{-- System Management --}}
        <div>

            <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-widest text-slate-500">
                System Management
            </p>

            <div class="space-y-1">

                <a href="{{ route('superadmin.schools.index') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 transition hover:bg-slate-800">

                    🏫 <span>Schools</span>

                </a>

                <a href="#"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 transition hover:bg-slate-800">

                    👥 <span>Users</span>

                </a>

                <a href="#"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 transition hover:bg-slate-800">

                    👨‍🏫 <span>Teachers</span>

                </a>

                <a href="#"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 transition hover:bg-slate-800">

                    🎓 <span>Students</span>

                </a>

            </div>

        </div>

        {{-- Reports --}}
        <div>

            <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-widest text-slate-500">
                Reports
            </p>

            <div class="space-y-1">

                <a href="#"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 transition hover:bg-slate-800">

                    📊 <span>Reports</span>

                </a>

                <a href="#"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 transition hover:bg-slate-800">

                    📝 <span>Logs</span>

                </a>

            </div>

        </div>

        {{-- System --}}
        <div>

            <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-widest text-slate-500">
                System
            </p>

            <div class="space-y-1">

                <a href="#"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 transition hover:bg-slate-800">

                    ⚙️ <span>Settings</span>

                </a>

                <a href="{{ route('superadmin.online-users') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                   {{ request()->routeIs('superadmin.online-users')
                        ? 'bg-indigo-600'
                        : 'hover:bg-slate-800' }}">

                    👨‍💻 <span>Online Users</span>

                </a>

                <a href="{{ route('superadmin.user-login-logs.index') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 transition
                   {{ request()->routeIs('superadmin.user-login-logs.*')
                        ? 'bg-indigo-600'
                        : 'hover:bg-slate-800' }}">

                    🔐 <span>User Login Logs</span>

                </a>

            </div>

        </div>

    </nav>

    {{-- Footer --}}
    <div class="border-t border-slate-800 px-6 py-4">

        <div class="text-center text-xs text-slate-500">

            PSPP Platform v1.0

        </div>

    </div>

</aside>