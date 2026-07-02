@extends('layouts.app')

@section('content')

<div class="flex min-h-screen bg-gray-100">

    {{-- ===================== SIDEBAR ===================== --}}
    <aside class="w-64 bg-blue-900 text-white flex flex-col">

        <div class="p-5 border-b border-blue-800">

            <h2 class="text-xl font-bold">
                👨‍🏫 TEACHER PANEL
            </h2>

            <p class="mt-2 text-sm text-blue-200">
                {{ auth()->user()->school?->school_name ?? 'ไม่พบข้อมูลโรงเรียน' }}
            </p>

        </div>

        <nav class="flex-1 p-4 space-y-2 text-sm">

            <a href="{{ route('teacher.dashboard') }}"
               class="block px-4 py-2 rounded hover:bg-blue-700">
                🏠 Dashboard
            </a>

            <a href="{{ route('teacher.timetable') }}"
               class="block px-4 py-2 rounded hover:bg-blue-700">
                📅 ตารางสอนของฉัน
            </a>

            <a href="{{ route('teacher.subjects') }}"
               class="block px-4 py-2 rounded hover:bg-blue-700">
                📚 รายวิชาของฉัน
            </a>

            <a href="{{ route('teacher.subjects.manage') }}"
               class="block px-4 py-2 rounded hover:bg-blue-700">
                📖 จัดการรายวิชาที่สอน
            </a>
           
            <a href="{{ route('teacher.attendances.index') }}"
               class="block px-4 py-2 rounded hover:bg-blue-700">
                ✅ เช็กชื่อผู้เข้าเรียน
            </a>

            <a href="{{ route('teacher.scores.index') }}"
                class="block px-3 py-2 rounded hover:bg-blue-700">
                  📝 บันทึกคะแนน
            </a>

            <a href="{{ route('teacher.grades.index') }}"
            class="block px-3 py-2 rounded hover:bg-blue-700">
                🎓 ผลการเรียน
            </a>

            <a href="#"
               class="block px-4 py-2 rounded hover:bg-blue-700">
                📊 รายงาน
            </a>

            <a href="/teacher/profile"
               class="block px-4 py-2 rounded hover:bg-blue-700">
                👤 โปรไฟล์
            </a>

        </nav>

    </aside>

    {{-- ===================== MAIN ===================== --}}
    <div class="flex-1 flex flex-col">

        {{-- ===================== TOPBAR ===================== --}}
        <header class="bg-white shadow">

            <div class="px-6 py-4 flex justify-between items-center">

                <div>
                    <h1 class="text-xl font-bold">
                        @yield('page-title', 'Teacher Dashboard')
                    </h1>

                    <p class="text-sm text-gray-500">
                        ยินดีต้อนรับ {{ auth()->user()->name }}
                    </p>
                </div>

                <div class="relative">

                    <button
                        onclick="toggleDropdown()"
                        class="flex items-center gap-3 focus:outline-none">

                        <img
                            src="{{ auth()->user()->profile_image
                                ? asset('storage/' . auth()->user()->profile_image)
                                : asset('images/default-user.png') }}"
                            class="w-10 h-10 rounded-full object-cover">

                        <span class="font-medium">
                            {{ auth()->user()->name }}
                        </span>

                    </button>

                    <div
                        id="dropdownMenu"
                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border">

                        <a href="/teacher/profile"
                           class="block px-4 py-2 hover:bg-gray-100">
                            👤 โปรไฟล์
                        </a>

                        <form method="POST"
                              action="{{ route('logout') }}">
                            @csrf

                            <button
                                class="w-full text-left px-4 py-2 hover:bg-gray-100">

                                🚪 Logout

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </header>

        {{-- ===================== CONTENT ===================== --}}
        <main class="flex-1 p-6">

            @yield('teacher-content')

        </main>

    </div>

</div>

{{-- ===================== SCRIPT ===================== --}}
<script>

function toggleDropdown() {

    document
        .getElementById('dropdownMenu')
        .classList
        .toggle('hidden');

}

window.addEventListener('click', function(e){

    if(!e.target.closest('.relative')){

        document
            .getElementById('dropdownMenu')
            .classList
            .add('hidden');

    }

});

</script>

@endsection