@extends('layouts.teacher')

@section('teacher-content')

    {{-- 📊 CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white p-5 rounded-xl shadow">
            <p class="text-gray-500 text-sm">วิชาที่สอน</p>
            <h2 class="text-2xl font-bold">{{ $subjectCount }} วิชา</h2>
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
    <div class="mt-8 bg-white rounded-xl shadow">

    <div class="px-6 py-4 border-b">

        <div class="flex justify-between">

            <h2 class="text-lg font-semibold">

                📅 ตารางสอนวันนี้

            </h2>

            <a href="{{ route('teacher.timetable') }}"
               class="text-blue-600 hover:underline">

                ดูทั้งหมด →

            </a>

        </div>

    </div>

    <table class="w-full text-sm">

        <thead class="bg-blue-50">

        <tr>

            <th class="p-3 text-left">เวลา</th>
            <th class="p-3 text-left">ห้อง</th>
            <th class="p-3 text-left">วิชา</th>
            <th class="p-3 text-center">จัดการ</th>

        </tr>

        </thead>

        <tbody>

        @forelse($todaySchedules as $schedule)

            <tr class="border-t hover:bg-gray-50">

                <td class="p-3">

                    {{ $schedule->period->start_time }}
                    -

                    {{ $schedule->period->end_time }}

                </td>

                <td class="p-3">

                    {{ $schedule->classroom->name }}

                </td>

                <td class="p-3">

                    {{ $schedule->subject->subject_name }}

                </td>

                <td class="p-3 text-center">

                    <button
                        class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">

                        ✅ เช็กชื่อ

                    </button>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="4"
                    class="text-center text-gray-500 p-6">

                    วันนี้ไม่มีตารางสอน

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

    {{-- 📋 งานล่าสุด --}}
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

@endsection