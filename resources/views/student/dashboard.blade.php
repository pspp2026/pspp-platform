@extends('layouts.app')

@section('content')

<div class="flex min-h-screen">

    {{-- 🔵 SIDEBAR --}}
    <aside class="w-64 bg-indigo-900 text-white p-5 space-y-4">

        <h2 class="text-xl font-bold mb-4">🎓 STUDENT PANEL</h2>

        <nav class="space-y-2 text-sm">

            <a href="{{ route('student.dashboard') }}"
               class="block px-3 py-2 rounded bg-indigo-700">
                🏠 Dashboard
            </a>

            <a href="#lessons"
               class="block px-3 py-2 rounded hover:bg-indigo-700">
                📚 บทเรียน
            </a>

            <a href="#assignments"
               class="block px-3 py-2 rounded hover:bg-indigo-700">
                📝 การบ้าน
            </a>

            <a href="#scores"
               class="block px-3 py-2 rounded hover:bg-indigo-700">
                📊 คะแนน
            </a>

            <a href="#schedule"
               class="block px-3 py-2 rounded hover:bg-indigo-700">
                📅 ตารางเรียน
            </a>

            <a href="{{ route('student.profile') }}"
           class="block px-3 py-2 rounded hover:bg-indigo-700">
            👤 โปรไฟล์
             </a>

        </nav>

    </aside>


    {{-- 🟡 MAIN --}}
    <div class="flex-1 bg-gray-100">

                {{-- 🔷 TOPBAR --}}
            <div class="bg-white shadow px-6 py-4 flex justify-between items-center">

                {{-- ซ้าย --}}
                <div class="mb-6">
                    <h1 class="text-2xl font-bold">Student Dashboard</h1>
                    <p class="text-sm text-gray-500">
                        ยินดีต้อนรับ {{ auth()->user()->display_name }}
                    </p>
                </div>

                {{-- ขวา --}}
                <div class="flex items-center gap-3">

                    <img src="{{ auth()->user()->profile_image 
                                ? asset('storage/' . auth()->user()->profile_image) 
                                : 'https://i.pravatar.cc/40' }}"
                        class="w-10 h-10 rounded-full border object-cover">

                    <span class="text-sm font-medium">
                        {{ auth()->user()->name }}
                    </span>

                </div>

            </div>

        {{-- 🔶 CONTENT --}}
        <div class="p-6">

            {{-- 📊 PROGRESS --}}
            <div class="bg-white p-6 rounded-xl shadow mb-6">
                <h2 class="text-lg font-semibold mb-2">📊 ความคืบหน้า</h2>

                <div class="bg-gray-200 rounded-full h-6">
                    <div class="bg-green-500 h-6 rounded-full text-center text-white text-sm"
                         style="width: {{ $percent ?? 0 }}%">
                        {{ $percent ?? 0 }}%
                    </div>
                </div>
            </div>


            {{-- 📊 SUMMARY --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">

                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">บทเรียนทั้งหมด</p>
                    <h2 class="text-2xl font-bold">{{ count($lessons) }}</h2>
                </div>

                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">เรียนแล้ว</p>
                    <h2 class="text-2xl font-bold text-green-600">
                        {{ count($completedLessons ?? []) }}
                    </h2>
                </div>

                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">คงเหลือ</p>
                    <h2 class="text-2xl font-bold text-red-500">
                        {{ count($lessons) - count($completedLessons ?? []) }}
                    </h2>
                </div>

                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">สถานะ</p>
                    <h2 class="text-lg font-bold">
                        {{ ($percent ?? 0) == 100 ? 'เรียนครบ 🎉' : 'กำลังเรียน' }}
                    </h2>
                </div>

            </div>


            {{-- 📚 LESSONS --}}
            <div id="lessons" class="bg-white rounded-xl shadow p-6">

                <h2 class="text-lg font-semibold mb-4">
                    📚 รายการบทเรียน
                </h2>

            {{-- ========================= --}}
            {{-- รายวิชาที่ลงทะเบียน --}}
            {{-- ========================= --}}

            <div class="mt-8 bg-white rounded-xl shadow p-6">

                <h2 class="text-lg font-semibold mb-4">
                    📚 รายวิชาที่ลงทะเบียน
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                    @forelse($subjects as $subject)

                        @php
                            $schedule = $schedules->firstWhere('subject_id', $subject->id);
                        @endphp

                        <div class="border rounded-xl p-4 hover:shadow">

                            <h3 class="font-bold text-indigo-700">
                                {{ $subject->subject_name }}
                            </h3>

                            <p class="text-sm text-gray-600 mt-2">
                                👨‍🏫
                                {{ $schedule?->teacher?->display_name ?? $schedule?->teacher?->name ?? '-' }}
                            </p>

                        </div>

                    @empty

                        <p class="text-gray-500">
                            ยังไม่มีรายวิชา
                        </p>

                    @endforelse

                </div>

            </div>

            {{-- ========================= --}}
            {{-- ครูผู้สอน --}}
            {{-- ========================= --}}

            <div class="mt-8 bg-white rounded-xl shadow p-6">

                <h2 class="text-lg font-semibold mb-4">
                    👨‍🏫 ครูผู้สอน
                </h2>

                @forelse($teachers as $teacherSchedules)

                    @php

                        $teacher = $teacherSchedules->first()->teacher;

                    @endphp

                    <div class="border rounded-xl p-4 mb-4">

                        <h3 class="font-bold text-indigo-700">

                            {{ $teacher->display_name ?? $teacher->name }}

                        </h3>

                        <ul class="list-disc ml-6 mt-2">

                            @foreach($teacherSchedules as $schedule)

                                <li>

                                    {{ $schedule->subject->subject_name }}

                                </li>

                            @endforeach

                        </ul>

                    </div>

                @empty

                    <p class="text-gray-500">

                        ยังไม่มีครูผู้สอน

                    </p>

                @endforelse

            </div>

            {{-- =============================== --}}
            {{-- ตารางเรียน --}}
            {{-- =============================== --}}

            <div id="schedule" class="bg-white rounded-xl shadow p-6 mb-8">

                <h2 class="text-xl font-bold mb-5">

                    📅 ตารางเรียน

                </h2>

                @php

                    $days = [
                        1=>'จันทร์',
                        2=>'อังคาร',
                        3=>'พุธ',
                        4=>'พฤหัสบดี',
                        5=>'ศุกร์'
                    ];

                @endphp

                <div class="overflow-x-auto">

                    <table class="min-w-full border">

                        <thead>

                        <tr class="bg-indigo-600 text-white">

                            <th class="border p-3">

                                วัน / คาบ

                            </th>

                            @foreach(range(1,7) as $period)

                                <th class="border p-3">

                                    คาบ {{ $period }}

                                </th>

                            @endforeach

                        </tr>

                        </thead>

                        <tbody>

                        @foreach($days as $dayNo=>$dayName)

                            <tr>

                                <td class="border bg-gray-100 font-semibold text-center">

                                    {{ $dayName }}

                                </td>

                                @foreach(range(1,7) as $period)

                                    @php

                                        $item = $timetable[$dayNo][$period] ?? null;

                                    @endphp

                                    <td class="border p-2 align-top">

                                        @if($item)

                                            <div class="font-semibold text-indigo-700">

                                                {{ $item->subject->subject_name }}

                                            </div>

                                            <div class="text-xs text-gray-600">

                                                👨‍🏫

                                                {{ $item->teacher->display_name ?? $item->teacher->name }}

                                            </div>

                                        @endif

                                    </td>

                                @endforeach

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

                @foreach($lessons as $lesson)
                    <div class="flex justify-between items-center border-b py-3">

                        <div>
                            {{ $lesson->title }}
                        </div>

                        <div>
                            @if(isset($completedLessons) && in_array($lesson->id, $completedLessons))
                                <span class="text-green-600 font-semibold">
                                    ✔ เรียนแล้ว
                                </span>
                            @else
                                <button onclick="markRead({{ $lesson->id }})"
                                        class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700 text-sm">
                                    📘 เรียนบทนี้
                                </button>
                            @endif
                        </div>

                    </div>
                @endforeach

            </div>

        </div>

    </div>

</div>


{{-- ⚡ SCRIPT --}}
<script>
function markRead(lessonId) {
    fetch(`/lesson/${lessonId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>

@endsection