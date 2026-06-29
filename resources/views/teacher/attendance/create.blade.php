@extends('layouts.app')

@section('title', 'เช็กชื่อเข้าเรียน')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">

                📋 เช็กชื่อเข้าเรียน

            </h1>

            <p class="text-slate-500 mt-1">

                บันทึกการเข้าเรียนของนักเรียน

            </p>

        </div>

        <a href="{{ route('teacher.attendances.index') }}"
           class="px-4 py-2 rounded-lg bg-slate-600 text-white hover:bg-slate-700">

            ← กลับ

        </a>

    </div>

    {{-- Validation --}}
    @if ($errors->any())

        <div class="mb-5 rounded-lg border border-red-300 bg-red-100 p-4">

            <ul class="list-disc list-inside text-red-700">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Session Information --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">

            <div>

                <div class="text-sm text-slate-500">

                    วิชา

                </div>

                <div class="font-semibold">

                    {{ $schedule->subject->subject_name }}

                </div>

            </div>

            <div>

                <div class="text-sm text-slate-500">

                    ห้องเรียน

                </div>

                <div class="font-semibold">

                    {{ $schedule->classroom->name }}

                </div>

            </div>

            <div>

                <div class="text-sm text-slate-500">

                    คาบเรียน

                </div>

                <div class="font-semibold">

                    {{ $schedule->period->name }}

                </div>

            </div>

            <div>

                <div class="text-sm text-slate-500">

                    วันที่

                </div>

                <div class="font-semibold">

                    {{ $session->attendance_date->format('d/m/Y') }}

                </div>

            </div>

        </div>

    </div>


    <form method="POST"
          action="{{ route('teacher.attendances.store') }}">

        @csrf

        <input
            type="hidden"
            name="attendance_session_id"
            value="{{ $session->id }}">

        <div class="bg-white rounded-xl shadow overflow-hidden">

            <table class="min-w-full">

                <thead class="bg-slate-100">

                <tr>

                    <th class="px-4 py-3 text-left">

                        ลำดับ

                    </th>

                    <th class="px-4 py-3 text-left">

                        รหัส

                    </th>

                    <th class="px-4 py-3 text-left">

                        ชื่อนักเรียน

                    </th>

                    <th class="px-4 py-3 text-center">

                        มา

                    </th>

                    <th class="px-4 py-3 text-center">

                        สาย

                    </th>

                    <th class="px-4 py-3 text-center">

                        ลา

                    </th>

                    <th class="px-4 py-3 text-center">

                        ขาด

                    </th>

                    <th class="px-4 py-3">

                        หมายเหตุ

                    </th>

                </tr>

                </thead>

                <tbody>

                @foreach($students as $index => $enrollment)

                    @php

                        $student = $enrollment->student;

                    @endphp

                    <tr class="border-t hover:bg-slate-50">

                        <td class="px-4 py-3">

                            {{ $index + 1 }}

                        </td>

                        <td class="px-4 py-3">

                            {{ $student->student_code }}

                        </td>

                        <td class="px-4 py-3">

                            {{ $student->user->name }}

                        </td>
                                                <td class="px-4 py-3 text-center">

                            <input
                                type="radio"
                                name="students[{{ $student->id }}][status]"
                                value="present"
                                checked>

                        </td>

                        <td class="px-4 py-3 text-center">

                            <input
                                type="radio"
                                name="students[{{ $student->id }}][status]"
                                value="late">

                        </td>

                        <td class="px-4 py-3 text-center">

                            <input
                                type="radio"
                                name="students[{{ $student->id }}][status]"
                                value="leave">

                        </td>

                        <td class="px-4 py-3 text-center">

                            <input
                                type="radio"
                                name="students[{{ $student->id }}][status]"
                                value="absent">

                        </td>

                        <td class="px-4 py-3">

                            <input
                                type="text"
                                name="students[{{ $student->id }}][remark]"
                                class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500"
                                placeholder="หมายเหตุ">

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

        <div class="flex justify-end gap-3 mt-6">

            <a href="{{ route('teacher.attendances.index') }}"
               class="px-5 py-2 rounded-lg bg-slate-500 text-white hover:bg-slate-600">

                ยกเลิก

            </a>

            <button
                type="submit"
                class="px-6 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">

                💾 บันทึกการเช็กชื่อ

            </button>

        </div>

    </form>

</div>

@endsection