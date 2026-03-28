@extends('layouts.app')

@section('content')

<div class="flex min-h-screen">

    {{-- 🔵 SIDEBAR --}}
    <aside class="w-64 bg-gray-900 text-white p-5 space-y-4">

        <h2 class="text-xl font-bold mb-4">STAFF PANEL</h2>

        <nav class="space-y-2 text-sm">

            <a href="#" class="block px-3 py-2 rounded hover:bg-gray-700">
                📚 งานวิชาการ
            </a>

            <a href="#" class="block px-3 py-2 rounded hover:bg-gray-700">
                👥 งานบุคคล
            </a>

            <a href="#" class="block px-3 py-2 rounded hover:bg-gray-700">
                💰 งานงบประมาณ
            </a>

            <a href="#" class="block px-3 py-2 rounded hover:bg-gray-700">
                🏢 งานบริหารทั่วไป
            </a>

            <a href="#" class="block px-3 py-2 rounded hover:bg-gray-700">
                📊 งานประกันคุณภาพ
            </a>

        </nav>

    </aside>


    {{-- 🟡 MAIN --}}
    <div class="flex-1 bg-gray-100">

        {{-- 🔷 TOPBAR --}}
        <div class="bg-white shadow px-6 py-4 flex justify-between items-center">

            <div>
                <h1 class="text-xl font-bold">Staff Dashboard</h1>
                <p class="text-sm text-gray-500">
                    ยินดีต้อนรับ {{ auth()->user()->name }}
                </p>
            </div>

            <div class="flex items-center gap-3">

                {{-- 🔙 กลับหน้าแรก --}}
                <a href="/"
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 text-sm">
                    ⬅ กลับหน้าแรก
                </a>

                {{-- 🔴 Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 text-sm">
                        Logout
                    </button>
                </form>

            </div>

        </div>


        {{-- 🔶 CONTENT --}}
        <div class="p-6">

            {{-- 📊 CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">งานวิชาการ</p>
                    <h2 class="text-2xl font-bold">12 รายการ</h2>
                </div>

                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">งานบุคคล</p>
                    <h2 class="text-2xl font-bold">8 รายการ</h2>
                </div>

                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">งานงบประมาณ</p>
                    <h2 class="text-2xl font-bold">5 รายการ</h2>
                </div>

            </div>


            {{-- 📋 TABLE --}}
            <div class="mt-8 bg-white rounded-xl shadow p-6">

                <h2 class="text-lg font-semibold mb-4">
                    รายการงานล่าสุด
                </h2>

                <table class="w-full text-sm">

                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="p-3">งาน</th>
                            <th class="p-3">รายละเอียด</th>
                            <th class="p-3">สถานะ</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3">งานวิชาการ</td>
                            <td class="p-3">อัปเดตหลักสูตร</td>
                            <td class="p-3 text-green-600">เสร็จแล้ว</td>
                        </tr>

                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3">งานบุคคล</td>
                            <td class="p-3">เพิ่มครูใหม่</td>
                            <td class="p-3 text-yellow-600">กำลังดำเนินการ</td>
                        </tr>
                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection