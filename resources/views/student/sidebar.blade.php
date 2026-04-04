<aside class="w-64 bg-indigo-900 text-white p-5 space-y-4 min-h-screen">

    {{-- 🔷 TITLE --}}
    <h2 class="text-xl font-bold mb-6">🎓 STUDENT PANEL</h2>

    {{-- 🔷 MENU --}}
    <nav class="space-y-2 text-sm">

        {{-- Dashboard --}}
        <a href="{{ route('student.dashboard') }}"
           class="block px-3 py-2 rounded transition
           {{ request()->routeIs('student.dashboard') ? 'bg-indigo-700' : 'hover:bg-indigo-700' }}">
            🏠 Dashboard
        </a>

        {{-- Lessons --}}
        <a href="#lessons"
           class="block px-3 py-2 rounded hover:bg-indigo-700 transition">
            📚 บทเรียน
        </a>

        {{-- Assignments --}}
        <a href="#assignments"
           class="block px-3 py-2 rounded hover:bg-indigo-700 transition">
            📝 การบ้าน
        </a>

        {{-- Scores --}}
        <a href="#scores"
           class="block px-3 py-2 rounded hover:bg-indigo-700 transition">
            📊 คะแนน
        </a>

        {{-- Schedule --}}
        <a href="#schedule"
           class="block px-3 py-2 rounded hover:bg-indigo-700 transition">
            📅 ตารางเรียน
        </a>

        {{-- Divider --}}
        <div class="border-t border-indigo-700 my-3"></div>

        {{-- Profile --}}
        <a href="{{ route('student.profile') }}"
           class="block px-3 py-2 rounded transition
           {{ request()->routeIs('student.profile') ? 'bg-indigo-700' : 'hover:bg-indigo-700' }}">
            👤 โปรไฟล์
        </a>

    </nav>

</aside>