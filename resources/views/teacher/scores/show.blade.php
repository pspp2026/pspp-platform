@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                📝 บันทึกคะแนนนักเรียน
            </h1>

            <p class="text-gray-500 mt-1">
                บันทึกคะแนนรายบุคคล
            </p>

        </div>

        <a href="{{ route('teacher.scores.index') }}"
           class="px-4 py-2 rounded-lg bg-gray-600 text-white hover:bg-gray-700">

            ← กลับ

        </a>

    </div>

    {{-- ข้อมูลรายวิชา --}}
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

    {{-- Success --}}
    @if(session('success'))

        <div class="mb-4 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3">

            {{ session('success') }}

        </div>

    @endif

    {{-- Form --}}
    <form method="POST"
          action="{{ route('teacher.scores.update', $schedule) }}">

        @csrf
        @method('PUT')

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
                            เก็บ
                        </th>

                        <th class="px-3 py-3 text-center">
                            กลางภาค
                        </th>

                        <th class="px-3 py-3 text-center">
                            ปลายภาค
                        </th>

                        <th class="px-3 py-3 text-center">
                            เวลาเรียน
                        </th>

                        <th class="px-3 py-3 text-center">
                            คุณลักษณะ
                        </th>

                        <th class="px-3 py-3 text-center">
                            โบนัส
                        </th>

                        <th class="px-3 py-3 text-center">
                            หัก
                        </th>

                        <th class="px-3 py-3 text-center">
                            รวม
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-200">
                                    @forelse($students as $index => $student)

                    @php
                        $score = $scores[$student->id] ?? null;
                    @endphp

                    <tr class="hover:bg-gray-50">

                        {{-- ลำดับ --}}
                        <td class="px-3 py-2 text-center">
                            {{ $index + 1 }}
                        </td>

                        {{-- รหัสนักเรียน --}}
                        <td class="px-3 py-2">
                            {{ $student->student_code }}
                        </td>

                        {{-- ชื่อ --}}
                        <td class="px-3 py-2 whitespace-nowrap">
                            {{ $student->full_name }}
                        </td>

                        <input type="hidden"
                               name="students[]"
                               value="{{ $student->id }}">

                        {{-- คะแนนเก็บ --}}
                        <td class="px-2 py-2">
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                name="work_score[{{ $student->id }}]"
                                value="{{ old('work_score.'.$student->id, $score->work_score ?? 0) }}"
                                class="w-20 border rounded px-2 py-1 text-center">
                        </td>

                        {{-- กลางภาค --}}
                        <td class="px-2 py-2">
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                name="midterm_score[{{ $student->id }}]"
                                value="{{ old('midterm_score.'.$student->id, $score->midterm_score ?? 0) }}"
                                class="w-20 border rounded px-2 py-1 text-center">
                        </td>

                        {{-- ปลายภาค --}}
                        <td class="px-2 py-2">
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                name="final_score[{{ $student->id }}]"
                                value="{{ old('final_score.'.$student->id, $score->final_score ?? 0) }}"
                                class="w-20 border rounded px-2 py-1 text-center">
                        </td>

                        {{-- เวลาเรียน --}}
                        <td class="px-2 py-2">
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                name="attendance_score[{{ $student->id }}]"
                                value="{{ old('attendance_score.'.$student->id, $score->attendance_score ?? 0) }}"
                                class="w-20 border rounded px-2 py-1 text-center">
                        </td>

                        {{-- คุณลักษณะ --}}
                        <td class="px-2 py-2">
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                name="behavior_score[{{ $student->id }}]"
                                value="{{ old('behavior_score.'.$student->id, $score->behavior_score ?? 0) }}"
                                class="w-20 border rounded px-2 py-1 text-center">
                        </td>

                        {{-- โบนัส --}}
                        <td class="px-2 py-2">
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                name="extra_score[{{ $student->id }}]"
                                value="{{ old('extra_score.'.$student->id, $score->extra_score ?? 0) }}"
                                class="w-20 border rounded px-2 py-1 text-center">
                        </td>

                        {{-- หัก --}}
                        <td class="px-2 py-2">
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                name="deduction_score[{{ $student->id }}]"
                                value="{{ old('deduction_score.'.$student->id, $score->deduction_score ?? 0) }}"
                                class="w-20 border rounded px-2 py-1 text-center">
                        </td>

                        {{-- รวม --}}
                        <td class="px-3 py-2 text-center font-bold">

                            {{ number_format($score->total_score ?? 0,2) }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="11"
                            class="text-center py-10 text-gray-500">

                            ยังไม่มีนักเรียนในห้องนี้

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        {{-- ปุ่มบันทึก --}}
        <div class="mt-6 flex justify-end">

            <button
                type="submit"
                class="px-6 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold">

                💾 บันทึกคะแนนทั้งหมด

            </button>

        </div>

    </form>

</div>

@endsection