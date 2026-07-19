<aside class="w-72 min-h-screen bg-slate-900 text-white shadow-lg">

    {{-- Header --}}
    <div class="px-6 py-5 border-b border-slate-700">

        <h2 class="text-xl font-bold">
            PSPP Platform
        </h2>

        <p class="text-sm text-slate-400 mt-1">
            {{ ucfirst(auth()->user()->role) }}
        </p>

    </div>

    {{-- Menu --}}
    <nav class="p-4 space-y-2">

        {{-- ============================= --}}
        {{-- SUPER ADMIN --}}
        {{-- ============================= --}}
        @if(auth()->user()->role == 'superadmin')

            <p class="px-3 py-2 text-xs uppercase tracking-widest text-slate-400">
                Dashboard
            </p>

            <a href="{{ route('superadmin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800">

                <span>🏠</span>

                <span>Dashboard</span>

            </a>

            <hr class="border-slate-700 my-3">

            <p class="px-3 py-2 text-xs uppercase tracking-widest text-slate-400">
                System Management
            </p>

            <a href="{{ route('superadmin.schools.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800">

                <span>🏫</span>

                <span>Schools</span>

            </a>

            <a href="#"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800">

                <span>👥</span>

                <span>Users</span>

            </a>

            <a href="#"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800">

                <span>👨‍🏫</span>

                <span>Teachers</span>

            </a>

            <a href="#"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800">

                <span>🎓</span>

                <span>Students</span>

            </a>

            <hr class="border-slate-700 my-3">

            <p class="px-3 py-2 text-xs uppercase tracking-widest text-slate-400">
                Reports
            </p>

            <a href="#"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800">

                <span>📊</span>

                <span>Reports</span>

            </a>
            
            
            <a href="#"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800">

                <span>📝</span>

                <span>Logs</span>

            </a>


            <hr class="border-slate-700 my-3">

            <p class="px-3 py-2 text-xs uppercase tracking-widest text-slate-400">
                System
            </p>

            <a href="#"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800">

                <span>⚙️</span>

                <span>Settings</span>

            </a>
            
            <a href="{{ route('superadmin.online-users') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800">

                <span>👨‍💻</span>

                <span>Online Users</span>

            </a>

            <a href="{{ route('superadmin.user-login-logs.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800">

                <span>🔐</span>

                <span>User Login Logs</span>

            </a>

        @endif

        {{-- ============================= --}}
        {{-- ADMIN --}}
        {{-- ============================= --}}
        @if(auth()->user()->role == 'admin')

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800">

                🏫 Dashboard

            </a>

        @endif

        {{-- ============================= --}}
        {{-- TEACHER --}}
        {{-- ============================= --}}
        @if(auth()->user()->role == 'teacher')

            <a href="{{ route('teacher.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800">

                👨‍🏫 Dashboard

            </a>

        @endif

        {{-- ============================= --}}
        {{-- STUDENT --}}
        {{-- ============================= --}}
        @if(auth()->user()->role == 'student')

            <a href="{{ route('student.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800">

                🎓 Dashboard

            </a>

        @endif

    </nav>

</aside>