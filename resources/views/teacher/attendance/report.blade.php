@extends('layouts.app')

@section('title', 'รายงานการเข้าเรียน')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">

                📊 รายงานการเข้าเรียน

            </h1>

            <p class="text-slate-500 mt-1">

                สรุปผลการเช็กชื่อเข้าเรียนของแต่ละคาบ

            </p>

        </div>

        <a href="{{ route('teacher.attendances.index') }}"
           class="px-4 py-2 rounded-lg bg-slate-600 text-white hover:bg-slate-700">

            ← กลับ

        </a>

    </div>

    {{-- Summary Table --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="min-w-full">

            <thead class="bg-slate-100">

                <tr>

                    <th class="px-4 py-3 text-left">

                        วันที่

                    </th>

                    <th class="px-4 py-3 text-left">

                        วิชา

                    </th>

                    <th class="px-4 py-3 text-left">

                        ห้องเรียน

                    </th>

                    <th class="px-4 py-3 text-center">

                        คาบ

                    </th>

                    <th class="px-4 py-3 text-center">

                        สถานะ

                    </th>

                    <th class="px-4 py-3 text-center">

                        นักเรียน

                    </th>

                    <th class="px-4 py-3 text-center">

                        รายละเอียด

                    </th>

                </tr>

            </thead>

            <tbody>

            @forelse($sessions as $session)

                <tr class="border-t hover:bg-slate-50">

                    <td class="px-4 py-3">

                        {{ $session->attendance_date->format('d/m/Y') }}

                    </td>

                    <td class="px-4 py-3">

                        {{ $session->subject->subject_name }}

                    </td>

                    <td class="px-4 py-3">

                        {{ $session->classroom->name }}

                    </td>

                    <td class="px-4 py-3 text-center">

                        {{ $session->period->name }}

                    </td>

                    <td class="px-4 py-3 text-center">
                                                @if($session->status == 'completed')

                            <span class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">

                                ✅ เช็กชื่อแล้ว

                            </span>

                        @else

                            <span class="inline-flex px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm font-medium">

                                🕒 กำลังเช็กชื่อ

                            </span>

                        @endif

                    </td>

                    <td class="px-4 py-3 text-center">

                        {{ $session->attendance_count }}

                    </td>

                    <td class="px-4 py-3">

                        <div class="flex justify-center">

                            <a href="{{ route('teacher.attendances.show', $session->id) }}"
                               class="px-3 py-1 rounded bg-indigo-600 text-white hover:bg-indigo-700 text-sm">

                                📋 ดูรายงาน

                            </a>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7"
                        class="px-6 py-12 text-center">

                        <div class="text-5xl mb-4">

                            📊

                        </div>

                        <div class="text-lg font-semibold text-slate-700">

                            ยังไม่มีข้อมูลรายงาน

                        </div>

                        <div class="text-sm text-slate-500 mt-2">

                            เมื่อมีการเช็กชื่อเข้าเรียน รายงานจะแสดงที่หน้านี้

                        </div>

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    {{-- Footer --}}
    <div class="flex justify-end mt-6">

        <a href="{{ route('teacher.attendances.history') }}"
           class="px-5 py-2 rounded-lg bg-slate-700 text-white hover:bg-slate-800">

            📚 ดูประวัติการเช็กชื่อ

        </a>

    </div>

</div>

@endsection