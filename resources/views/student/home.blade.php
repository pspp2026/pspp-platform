@extends('layouts.app')

@section('content')

<div class="flex min-h-screen">

    {{-- 🔵 SIDEBAR --}}
    <aside class="w-64 bg-indigo-900 text-white p-5 space-y-4">

        <h2 class="text-xl font-bold mb-4">STUDENT PANEL</h2>

        <nav class="space-y-2 text-sm">

            <a href="/student/home" class="block px-3 py-2 rounded bg-indigo-700">
                🏠 หน้าแรก
            </a>

            <a href="#" class="block px-3 py-2 rounded hover:bg-indigo-700">
                📚 วิชาของฉัน
            </a>

            <a href="#" class="block px-3 py-2 rounded hover:bg-indigo-700">
                📝 งาน/การบ้าน
            </a>

            <a href="#" class="block px-3 py-2 rounded hover:bg-indigo-700">
                📊 คะแนน
            </a>

            <a href="#" class="block px-3 py-2 rounded hover:bg-indigo-700">
                📅 ตารางเรียน
            </a>

            <a href="#" class="block px-3 py-2 rounded hover:bg-indigo-700">
                👤 โปรไฟล์
            </a>

        </nav>

    </aside>


    {{-- 🟡 MAIN --}}
    <div class="flex-1 bg-gray-100">

        {{-- 🔷 TOPBAR --}}
        <div class="bg-white shadow px-6 py-4 flex justify-between items-center">

            <div>
                <h1 class="text-xl font-bold">Student Dashboard</h1>
                <p class="text-sm text-gray-500">
                    ยินดีต้อนรับ {{ auth()->user()->name }}
                </p>
            </div>

            <div class="flex items-center gap-4">

                {{-- 👤 PROFILE --}}
                <div class="flex items-center gap-2">
                    <img src="https://i.pravatar.cc/40"
                         class="w-10 h-10 rounded-full border">
                    <span class="text-sm">{{ auth()->user()->name }}</span>
                </div>

                {{-- 🔴 LOGOUT --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                        Logout
                    </button>
                </form>

            </div>

        </div>


        {{-- 🔶 CONTENT --}}
        <div class="p-6">

            {{-- 📊 CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">วิชาที่เรียน</p>
                    <h2 class="text-2xl font-bold">6 วิชา</h2>
                </div>

                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">งานที่ต้องส่ง</p>
                    <h2 class="text-2xl font-bold text-red-500">3 งาน</h2>
                </div>

                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">คะแนนเฉลี่ย</p>
                    <h2 class="text-2xl font-bold text-green-600">3.45</h2>
                </div>

                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">วิชาที่ผ่าน</p>
                    <h2 class="text-2xl font-bold">5/6</h2>
                </div>

            </div>


            {{-- 📋 งานล่าสุด --}}
            <div class="mt-8 bg-white rounded-xl shadow p-6">

                <h2 class="text-lg font-semibold mb-4">
                    📝 งานที่ต้องส่ง
                </h2>

                <table class="w-full text-sm">

                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="p-3">วิชา</th>
                            <th class="p-3">งาน</th>
                            <th class="p-3">กำหนดส่ง</th>
                            <th class="p-3">สถานะ</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3">คณิตศาสตร์</td>
                            <td class="p-3">แบบฝึกหัดบทที่ 2</td>
                            <td class="p-3">25 มี.ค.</td>
                            <td class="p-3 text-red-500">ยังไม่ส่ง</td>
                        </tr>

                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3">ภาษาไทย</td>
                            <td class="p-3">เขียนเรียงความ</td>
                            <td class="p-3">20 มี.ค.</td>
                            <td class="p-3 text-green-600">ส่งแล้ว</td>
                        </tr>
                    </tbody>

                </table>

            </div>


            {{-- 📅 ตารางเรียน --}}
            <div class="mt-8 bg-white rounded-xl shadow p-6">

                <h2 class="text-lg font-semibold mb-4">
                    📅 ตารางเรียนวันนี้
                </h2>

                <ul class="space-y-2 text-sm">
                    <li>08:30 - คณิตศาสตร์</li>
                    <li>10:00 - ภาษาอังกฤษ</li>
                    <li>13:00 - วิทยาศาสตร์</li>
                </ul>

            </div>

        </div>

    </div>

</div>

@endsection