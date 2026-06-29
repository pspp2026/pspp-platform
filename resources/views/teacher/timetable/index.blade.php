@extends('layouts.teacher')

@section('page-title', 'ตารางสอนของฉัน')

@section('teacher-content')

<div class="max-w-7xl mx-auto">

    <div class="bg-white rounded-xl shadow">

        <div class="border-b px-6 py-4 flex items-center justify-between">

            <div>

                <h2 class="text-2xl font-bold">

                    📅 ตารางสอนของฉัน

                </h2>

                @if($term)

                    <p class="text-gray-500 mt-1">

                        ปีการศึกษา {{ $term->academic_year }}

                        ภาคเรียน {{ $term->semester }}

                    </p>

                @endif

            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full border">

                <thead class="bg-slate-100">

                <tr>

                    <th class="border p-3">

                        วัน

                    </th>

                    <th class="border p-3">

                        คาบ

                    </th>

                    <th class="border p-3">

                        เวลา

                    </th>

                    <th class="border p-3">

                        วิชา

                    </th>

                    <th class="border p-3">

                        ห้อง

                    </th>

                    <th class="border p-3 text-center">

                        จัดการ

                    </th>

                </tr>

                </thead>

                <tbody>

                @forelse($schedules as $schedule)

                    <tr class="hover:bg-slate-50">

                        <td class="border p-3">

                            {{ $days[$schedule->day_of_week] }}

                        </td>

                        <td class="border p-3">

                            {{ $schedule->period->name }}

                        </td>

                        <td class="border p-3">

                            {{ substr($schedule->period->start_time,0,5) }}

                            -

                            {{ substr($schedule->period->end_time,0,5) }}

                        </td>

                        <td class="border p-3">

                            <div class="font-semibold">

                                {{ $schedule->subject->subject_name }}

                            </div>

                        </td>

                        <td class="border p-3">

                            {{ $schedule->classroom->name }}

                        </td>

                        <td class="border p-3">

                            <div class="flex flex-wrap gap-2">
                                                            {{-- รายชื่อนักเรียน --}}
                            <a href="{{ route('teacher.students.index', $schedule->id) }}"
                               class="px-3 py-1 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">

                                👥 นักเรียน

                            </a>

                            {{-- เช็กชื่อ --}}
                            <form method="GET"
                                  action="{{ route('teacher.attendances.take') }}"
                                  class="inline">

                                <input
                                    type="hidden"
                                    name="schedule_id"
                                    value="{{ $schedule->id }}">

                                <input
                                    type="hidden"
                                    name="date"
                                    value="{{ $today }}">

                                <button
                                    type="submit"
                                    class="px-3 py-1 rounded bg-emerald-600 text-white text-sm hover:bg-emerald-700">

                                    📋 เช็กชื่อ

                                </button>

                            </form>

                            {{-- คะแนน (เตรียมไว้สำหรับ Module ถัดไป) --}}
                            {{--
                            <a href="{{ route('teacher.scores.index', $schedule->id) }}"
                               class="px-3 py-1 rounded bg-purple-600 text-white text-sm hover:bg-purple-700">

                                📝 คะแนน

                            </a>
                            --}}

                        </div>

                    </td>

                </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="text-center py-10 text-slate-500">

                            <div class="text-5xl mb-4">

                                📅

                            </div>

                            <div class="text-lg font-semibold">

                                ยังไม่มีตารางสอน

                            </div>

                            <div class="text-sm text-slate-400 mt-2">

                                เมื่อผู้ดูแลระบบจัดตารางสอนแล้ว ตารางจะแสดงที่หน้านี้

                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection