@extends('layouts.app')

@section('title', 'รายละเอียดการเช็กชื่อ')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">

                📋 รายละเอียดการเช็กชื่อ

            </h1>

            <p class="text-slate-500 mt-1">

                ผลการเข้าเรียนของนักเรียน

            </p>

        </div>

        <div class="flex gap-3">

            <a href="{{ route('teacher.attendances.index') }}"
               class="px-4 py-2 rounded-lg bg-slate-600 text-white hover:bg-slate-700">

                ← กลับ

            </a>

            <a href="{{ route('teacher.attendances.edit', $session->id) }}"
               class="px-4 py-2 rounded-lg bg-amber-500 text-white hover:bg-amber-600">

                ✏️ แก้ไข

            </a>

        </div>

    </div>

    {{-- Session Information --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">

        <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-5">

            <div>

                <div class="text-sm text-slate-500">

                    วิชา

                </div>

                <div class="font-semibold">

                    {{ $session->subject->subject_name }}

                </div>

            </div>

            <div>

                <div class="text-sm text-slate-500">

                    ห้องเรียน

                </div>

                <div class="font-semibold">

                    {{ $session->classroom->name }}

                </div>

            </div>

            <div>

                <div class="text-sm text-slate-500">

                    คาบเรียน

                </div>

                <div class="font-semibold">

                    {{ $session->period->name }}

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

    {{-- Summary --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">

        <div class="bg-green-100 rounded-xl p-5 text-center">

            <div class="text-3xl font-bold text-green-700">

                {{ $summary['present'] }}

            </div>

            <div class="text-green-700">

                มาเรียน

            </div>

        </div>

        <div class="bg-yellow-100 rounded-xl p-5 text-center">

            <div class="text-3xl font-bold text-yellow-700">

                {{ $summary['late'] }}

            </div>

            <div class="text-yellow-700">

                มาสาย

            </div>

        </div>

        <div class="bg-blue-100 rounded-xl p-5 text-center">

            <div class="text-3xl font-bold text-blue-700">

                {{ $summary['leave'] }}

            </div>

            <div class="text-blue-700">

                ลา

            </div>

        </div>

        <div class="bg-red-100 rounded-xl p-5 text-center">

            <div class="text-3xl font-bold text-red-700">

                {{ $summary['absent'] }}

            </div>

            <div class="text-red-700">

                ขาด

            </div>

        </div>

        <div class="bg-slate-100 rounded-xl p-5 text-center">

            <div class="text-3xl font-bold text-slate-700">

                {{ $summary['total'] }}

            </div>

            <div class="text-slate-700">

                ทั้งหมด

            </div>

        </div>

    </div>

    {{-- Student List --}}
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

                    ชื่อ - สกุล

                </th>

                <th class="px-4 py-3 text-center">

                    สถานะ

                </th>

                <th class="px-4 py-3">

                    หมายเหตุ

                </th>

            </tr>

            </thead>

            <tbody>

            @foreach($session->records as $index => $record)

                @php

                    $student = $record->student;

                @endphp

                <tr class="border-t hover:bg-slate-50">

                    <td class="px-4 py-3">

                        {{ $index + 1 }}

                    </td>

                    <td class="px-4 py-3">

                        {{ $student->student_code }}

                    </td>

                    <td class="px-4 py-3">

                        {{ $student->full_name }}

                    </td>
                                        <td class="px-4 py-3 text-center">

                        @switch($record->status)

                            @case('present')

                                <span class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">

                                    ✅ มาเรียน

                                </span>

                                @break

                            @case('late')

                                <span class="inline-flex px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm font-medium">

                                    🕒 มาสาย

                                </span>

                                @break

                            @case('leave')

                                <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-medium">

                                    📝 ลา

                                </span>

                                @break

                            @case('absent')

                                <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-medium">

                                    ❌ ขาด

                                </span>

                                @break

                            @default

                                <span class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-sm">

                                    -

                                </span>

                        @endswitch

                    </td>

                    <td class="px-4 py-3">

                        {{ $record->remark ?: '-' }}

                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

    {{-- Footer --}}
    <div class="flex justify-between items-center mt-6">

        <a href="{{ route('teacher.attendances.index') }}"
           class="px-5 py-2 rounded-lg bg-slate-500 text-white hover:bg-slate-600">

            ← กลับรายการ

        </a>

        @if(!$session->isCompleted())

            <a href="{{ route('teacher.attendances.edit', $session->id) }}"
               class="px-5 py-2 rounded-lg bg-amber-500 text-white hover:bg-amber-600">

                ✏️ แก้ไขการเช็กชื่อ

            </a>

        @endif

    </div>

</div>

@endsection