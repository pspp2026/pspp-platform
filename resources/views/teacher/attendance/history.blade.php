@extends('layouts.app')

@section('title', 'ประวัติการเช็กชื่อ')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-3xl font-bold text-slate-800">

                📚 ประวัติการเช็กชื่อ

            </h1>

            <p class="text-slate-500 mt-1">

                รายการการเช็กชื่อทั้งหมดของครูผู้สอน

            </p>

        </div>

        <a href="{{ route('teacher.attendances.index') }}"
           class="px-4 py-2 rounded-lg bg-slate-600 text-white hover:bg-slate-700">

            ← กลับ

        </a>

    </div>

    {{-- Success Message --}}
    @if(session('success'))

        <div class="mb-5 rounded-lg border border-green-300 bg-green-100 p-4 text-green-700">

            {{ session('success') }}

        </div>

    @endif

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

                        จำนวน

                    </th>

                    <th class="px-4 py-3 text-center">

                        จัดการ

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

                        <div class="flex justify-center gap-2">

                            <a href="{{ route('teacher.attendances.show', $session->id) }}"
                               class="px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-700 text-sm">

                                👁 ดู

                            </a>

                            <a href="{{ route('teacher.attendances.edit', $session->id) }}"
                               class="px-3 py-1 rounded bg-amber-500 text-white hover:bg-amber-600 text-sm">

                                ✏️ แก้ไข

                            </a>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7"
                        class="px-6 py-10 text-center text-slate-500">

                        <div class="text-5xl mb-3">

                            📋

                        </div>

                        <div class="text-lg font-medium">

                            ยังไม่มีประวัติการเช็กชื่อ

                        </div>

                        <div class="text-sm text-slate-400 mt-1">

                            เมื่อครูเช็กชื่อแล้ว รายการจะปรากฏที่นี่

                        </div>

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection