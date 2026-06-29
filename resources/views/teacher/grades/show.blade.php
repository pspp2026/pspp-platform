@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                🎓 ผลการเรียน
            </h1>

            <p class="text-gray-500 mt-1">
                สรุปผลการเรียนของนักเรียน
            </p>

        </div>

        <a href="{{ route('teacher.grades.index') }}"
           class="px-4 py-2 rounded-lg bg-gray-600 text-white hover:bg-gray-700">

            ← กลับ

        </a>

    </div>

    {{-- Success --}}
    @if(session('success'))

        <div class="mb-5 rounded-lg border border-green-300 bg-green-100 px-4 py-3 text-green-800">

            {{ session('success') }}

        </div>

    @endif

    {{-- Subject Info --}}
    <div class="bg-white rounded-xl shadow mb-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 p-6">

            <div>

                <div class="text-sm text-gray-500">
                    วิชา
                </div>

                <div class="font-semibold text-lg">
                    {{ $schedule->subject->subject_name }}
                </div>

                <div class="text-sm text-gray-500">
                    {{ $schedule->subject->subject_code }}
                </div>

            </div>

            <div>

                <div class="text-sm text-gray-500">
                    ห้องเรียน
                </div>

                <div class="font-semibold text-lg">
                    {{ $schedule->classroom->name }}
                </div>

            </div>

            <div>

                <div class="text-sm text-gray-500">
                    ภาคเรียน
                </div>

                <div class="font-semibold text-lg">

                    {{ $schedule->academicTerm->semester }}
                    /
                    {{ $schedule->academicTerm->academic_year }}

                </div>

            </div>

            <div>

                <div class="text-sm text-gray-500">
                    วัน / คาบ
                </div>

                <div class="font-semibold text-lg">

                    {{ $schedule->day_of_week }}

                    ({{ $schedule->period->name }})

                </div>

            </div>

        </div>

    </div>

    {{-- Calculate --}}
    <div class="mb-5 flex justify-end">

        <form method="POST"
              action="{{ route('teacher.grades.calculate',$schedule) }}">

            @csrf

            <button
                type="submit"
                class="px-5 py-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold">

                🎓 คำนวณผลการเรียน

            </button>

        </form>

    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">

        <table class="min-w-full divide-y divide-gray-200">

            <thead class="bg-gray-100">

            <tr>

                <th class="px-3 py-3 text-center">
                    #
                </th>

                <th class="px-3 py-3 text-left">
                    รหัส
                </th>

                <th class="px-3 py-3 text-left">
                    ชื่อนักเรียน
                </th>

                <th class="px-3 py-3 text-center">
                    คะแนนรวม
                </th>

                <th class="px-3 py-3 text-center">
                    เกรด
                </th>

                <th class="px-3 py-3 text-center">
                    GPA
                </th>

                <th class="px-3 py-3 text-center">
                    สถานะ
                </th>

            </tr>

            </thead>

            <tbody class="divide-y divide-gray-200">

            @forelse($scores as $index => $score)

                <tr class="hover:bg-gray-50">

                    <td class="px-3 py-2 text-center">

                        {{ $index + 1 }}

                    </td>

                    <td class="px-3 py-2">

                        {{ $score->student->student_code }}

                    </td>

                    <td class="px-3 py-2">

                        {{ $score->student->full_name }}

                    </td>

                    <td class="px-3 py-2 text-center font-semibold">

                        {{ number_format($score->total_score,2) }}

                    </td>

                    <td class="px-3 py-2 text-center">

                        @if($score->grade)

                            <span class="font-bold text-lg">

                                {{ $score->grade->grade }}

                            </span>

                        @else

                            -

                        @endif

                    </td>

                    <td class="px-3 py-2 text-center">

                        @if($score->grade)

                            {{ number_format($score->grade->grade_point,2) }}

                        @else

                            -

                        @endif

                    </td>

                    <td class="px-3 py-2 text-center">

                        @if($score->grade)

                            @if($score->grade->passed)

                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">

                                    ผ่าน

                                </span>

                            @else

                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700">

                                    ไม่ผ่าน

                                </span>

                            @endif

                        @else

                            <span class="text-gray-400">

                                ยังไม่คำนวณ

                            </span>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7"
                        class="py-10 text-center text-gray-500">

                        ยังไม่มีข้อมูลคะแนน

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection