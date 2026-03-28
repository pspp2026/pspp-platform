@extends('layouts.app')

@section('content')

<div class="flex min-h-screen">

    {{-- 🔵 SIDEBAR --}}
    <aside class="w-64 bg-blue-900 text-white p-5 space-y-4">

    <h2 class="text-xl font-bold mb-4">TEACHER PANEL</h2>

    <nav class="space-y-2 text-sm">

        {{-- 🏠 หน้าแรก --}}
        <a href="/"
           class="block px-3 py-2 rounded hover:bg-blue-700">
            🏠 หน้าแรก
        </a>

        <a href="#" class="block px-3 py-2 rounded hover:bg-blue-700">
            📘 รายวิชาของฉัน
        </a>

        <a href="#" class="block px-3 py-2 rounded hover:bg-blue-700">
            👨‍🎓 นักเรียน
        </a>

        <a href="#" class="block px-3 py-2 rounded hover:bg-blue-700">
            📝 บันทึกคะแนน
        </a>

        <a href="#" class="block px-3 py-2 rounded hover:bg-blue-700">
            📅 ตารางสอน
        </a>

        <a href="#" class="block px-3 py-2 rounded hover:bg-blue-700">
            📊 รายงานผล
        </a>

        {{-- 👤 PROFILE --}}
        <a href="/teacher/profile"
           class="block px-3 py-2 rounded hover:bg-blue-700">
            👤 โปรไฟล์
        </a>

    </nav>

</aside>


    {{-- 🟡 MAIN --}}
    <div class="flex-1 bg-gray-100">

        {{-- 🔷 TOPBAR --}}
        <div class="bg-white shadow px-6 py-4 flex justify-between items-center">

            {{-- ซ้าย --}}
            <div>
                <h1 class="text-xl font-bold">Teacher Dashboard</h1>
                <p class="text-sm text-gray-500">
                    ยินดีต้อนรับ {{ auth()->user()->name }}
                </p>
            </div>

            {{-- ขวา --}}
            <div class="flex items-center gap-4">


                {{-- 👤 USER DROPDOWN --}}
                <div class="relative">

                    <button onclick="toggleDropdown()" 
                        class="flex items-center gap-2 focus:outline-none">

                        {{-- 🖼 รูปจำลอง --}}
                        <img  src="{{ auth()->user()->profile_image 
                                ? asset('storage/' . auth()->user()->profile_image) 
                                : asset('images/default-user.png') }}"
                            class="w-10 h-10 rounded-full">

                        <span class="text-sm font-medium">
                            {{ auth()->user()->name }}
                        </span>
                    </button>

                    {{-- 🔽 DROPDOWN --}}
                    <div id="dropdownMenu" 
                         class="hidden absolute right-0 mt-2 w-44 bg-white border rounded shadow">

                        <a href="#"
                           class="block px-4 py-2 text-sm hover:bg-gray-100">
                            👤 แก้ไขโปรไฟล์
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                                🔴 Logout
                            </button>
                        </form>

                    </div>

                </div>

            </div>

        </div>


        {{-- 🔶 CONTENT --}}
        <div class="p-6">

            {{-- 📊 CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">วิชาที่สอน</p>
                    <h2 class="text-2xl font-bold">5 วิชา</h2>
                </div>

                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">นักเรียนทั้งหมด</p>
                    <h2 class="text-2xl font-bold">120 คน</h2>
                </div>

                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">งานที่ต้องตรวจ</p>
                    <h2 class="text-2xl font-bold">8 งาน</h2>
                </div>

            </div>


            {{-- 📋 TABLE --}}
            <div class="mt-8 bg-white rounded-xl shadow p-6">

                <h2 class="text-lg font-semibold mb-4">
                    งานล่าสุด
                </h2>

                <table class="w-full text-sm">

                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="p-3">วิชา</th>
                            <th class="p-3">งาน</th>
                            <th class="p-3">สถานะ</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3">คณิตศาสตร์</td>
                            <td class="p-3">แบบฝึกหัดบทที่ 1</td>
                            <td class="p-3 text-green-600">ตรวจแล้ว</td>
                        </tr>

                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3">ภาษาไทย</td>
                            <td class="p-3">เขียนเรียงความ</td>
                            <td class="p-3 text-yellow-600">รอตรวจ</td>
                        </tr>
                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- 🔥 SCRIPT DROPDOWN --}}
<script>
function toggleDropdown() {
    const menu = document.getElementById('dropdownMenu');
    menu.classList.toggle('hidden');
}

// ปิดเมื่อคลิกข้างนอก
window.addEventListener('click', function(e) {
    if (!e.target.closest('.relative')) {
        document.getElementById('dropdownMenu').classList.add('hidden');
    }
});
</script>

@endsection