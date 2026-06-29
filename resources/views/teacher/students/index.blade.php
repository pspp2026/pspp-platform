@extends('layouts.teacher')

@section('page-title', 'รายชื่อนักเรียน')

@section('teacher-content')

<div class="max-w-7xl mx-auto">

    <div class="bg-white rounded-xl shadow">

        {{-- Header --}}
        <div class="border-b px-6 py-4 flex justify-between items-center">

            <div>

                <h2 class="text-2xl font-bold">
                    👨‍🎓 รายชื่อนักเรียน
                </h2>

                <p class="text-gray-500 mt-1">

                    ห้องเรียน

                    <span class="font-semibold">
                        {{ $schedule->classroom->name }}
                    </span>

                    |

                    วิชา

                    <span class="font-semibold">
                        {{ $schedule->subject->subject_name }}
                    </span>

                </p>

            </div>

            <div>

                <a href="{{ route('teacher.timetable') }}"
                   class="px-4 py-2 rounded-lg bg-gray-600 text-white hover:bg-gray-700">

                    ← กลับตารางสอน

                </a>

            </div>

        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-slate-100">

                    <tr>

                        <th class="border px-4 py-3 text-center w-16">
                            #
                        </th>

                        <th class="border px-4 py-3">
                            รหัสนักเรียน
                        </th>

                        <th class="border px-4 py-3">
                            ชื่อ-สกุล
                        </th>

                        <th class="border px-4 py-3">
                            วัด
                        </th>

                        <th class="border px-4 py-3 text-center">
                            ห้อง
                        </th>

                        <th class="border px-4 py-3 text-center">
                            การดำเนินการ
                        </th>

                    </tr>

                </thead>

                <tbody>

                @forelse($students as $index => $student)

                    <tr class="hover:bg-slate-50">

                        <td class="border px-4 py-3 text-center">

                            {{ $index + 1 }}

                        </td>

                        <td class="border px-4 py-3">

                            {{ $student->student_code }}

                        </td>

                        <td class="border px-4 py-3">

                            <div class="font-medium">

                                {{ $student->full_name }}

                            </div>

                            @if($student->user)

                                <div class="text-xs text-gray-500">

                                    {{ $student->user->email }}

                                </div>

                            @endif

                        </td>

                        <td class="border px-4 py-3">

                            {{ $student->temple->name ?? '-' }}

                        </td>

                        <td class="border px-4 py-3 text-center">

                            {{ $student->classroom->name ?? '-' }}

                        </td>

                        <td class="border px-4 py-3 text-center">

                            <div class="flex justify-center gap-2">
                                                                {{-- เช็กชื่อ --}}
                                <a href="{{ route('teacher.attendances.take', [
                                        'schedule_id' => $schedule->id,
                                        'date' => now()->toDateString()
                                    ]) }}"
                                   class="px-3 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 text-sm">

                                    📋 เช็กชื่อ

                                </a>

                                {{-- คะแนน --}}
                                <a href="#"
                                   class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">

                                    📝 คะแนน

                                </a>

                                {{-- ข้อมูลนักเรียน --}}
                                <a href="#"
                                   class="px-3 py-2 rounded-lg bg-slate-600 text-white hover:bg-slate-700 text-sm">

                                    👁 ดูข้อมูล

                                </a>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="border px-4 py-8 text-center text-gray-500">

                            ยังไม่มีนักเรียนในห้องนี้

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        {{-- Footer --}}
        <div class="border-t px-6 py-4 bg-slate-50">

            <div class="flex justify-between items-center">

                <div class="text-sm text-gray-600">

                    จำนวนนักเรียนทั้งหมด

                    <span class="font-bold">

                        {{ $students->count() }}

                    </span>

                    รูป

                </div>

                <a href="{{ route('teacher.timetable') }}"
                   class="px-4 py-2 rounded-lg bg-gray-600 text-white hover:bg-gray-700">

                    ← กลับตารางสอน

                </a>

            </div>

        </div>

    </div>

</div>

@endsection