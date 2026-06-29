@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                📝 บันทึกคะแนน
            </h1>
            <p class="text-gray-500 mt-1">
                เลือกรายวิชาที่ต้องการบันทึกคะแนน
            </p>
        </div>
    </div>

    {{-- Success --}}
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- ตาราง --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="min-w-full divide-y divide-gray-200">

            <thead class="bg-gray-100">
                <tr>

                    <th class="px-4 py-3 text-left text-sm font-semibold">
                        วิชา
                    </th>

                    <th class="px-4 py-3 text-left text-sm font-semibold">
                        ห้อง
                    </th>

                    <th class="px-4 py-3 text-left text-sm font-semibold">
                        ภาคเรียน
                    </th>

                    <th class="px-4 py-3 text-center text-sm font-semibold">
                        วัน / คาบ
                    </th>

                    <th class="px-4 py-3 text-center text-sm font-semibold">
                        จัดการ
                    </th>

                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 bg-white">

                @forelse($schedules as $schedule)

                    <tr class="hover:bg-gray-50">

                        {{-- วิชา --}}
                        <td class="px-4 py-3">
                            <div class="font-semibold text-gray-800">
                                {{ $schedule->subject->subject_name ?? '-' }}
                            </div>

                            <div class="text-xs text-gray-500">
                                {{ $schedule->subject->subject_code ?? '' }}
                            </div>
                        </td>

                        {{-- ห้อง --}}
                        <td class="px-4 py-3">
                            {{ $schedule->classroom->name ?? '-' }}
                        </td>

                        {{-- ภาคเรียน --}}
                        <td class="px-4 py-3">

                            @if($schedule->academicTerm)

                                {{ $schedule->academicTerm->semester }}
                                /
                                {{ $schedule->academicTerm->academic_year }}

                            @else

                                -

                            @endif

                        </td>

                        {{-- วัน / คาบ --}}
                        <td class="px-4 py-3 text-center">

                            <div>
                                {{ $schedule->day_of_week }}
                            </div>

                            <div class="text-xs text-gray-500">
                                {{ $schedule->period->name ?? '' }}
                            </div>

                        </td>

                        {{-- ปุ่ม --}}
                        <td class="px-4 py-3 text-center">

                            <a href="{{ route('teacher.scores.show', $schedule) }}"
                               class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">

                                📝 บันทึกคะแนน

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5"
                            class="px-6 py-10 text-center text-gray-500">

                            ยังไม่มีรายวิชาที่ได้รับมอบหมาย

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
@endsection