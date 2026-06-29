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
                เลือกคาบเรียนเพื่อเช็กชื่อนักเรียน
            </p>
        </div>

        <a href="{{ route('teacher.dashboard') }}"
           class="px-4 py-2 rounded-lg bg-slate-600 text-white hover:bg-slate-700">
            ← กลับ Dashboard
        </a>
    </div>

    {{-- Success --}}
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-100 border border-green-300 p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Empty --}}
    @if($sessions->count() == 0)

        <div class="bg-white rounded-xl shadow p-10 text-center">

            <div class="text-6xl mb-3">
                📚
            </div>

            <h2 class="text-2xl font-semibold text-slate-700">
                ยังไม่มีคาบเรียน
            </h2>

            <p class="text-slate-500 mt-2">
                กรุณารอผู้ดูแลระบบจัดตารางสอน
            </p>

        </div>

    @else

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="min-w-full">

            <thead class="bg-slate-100">

            <tr>

                <th class="px-5 py-3 text-left">
                    วันที่
                </th>

                <th class="px-5 py-3 text-left">
                    คาบ
                </th>

                <th class="px-5 py-3 text-left">
                    วิชา
                </th>

                <th class="px-5 py-3 text-left">
                    ห้อง
                </th>

                <th class="px-5 py-3 text-left">
                    สถานะ
                </th>

                <th class="px-5 py-3 text-center">
                    จัดการ
                </th>

            </tr>

            </thead>

            <tbody>

            @foreach($sessions as $session)

                <tr class="border-t hover:bg-slate-50">

                    <td class="px-5 py-4">

                        {{ \Carbon\Carbon::parse($session->attendance_date)->format('d/m/Y') }}

                    </td>

                    <td class="px-5 py-4">

                        {{ $session->period->name ?? '-' }}

                    </td>

                    <td class="px-5 py-4">

                        {{ $session->subject->subject_name ?? '-' }}

                    </td>

                    <td class="px-5 py-4">

                        {{ $session->classroom->name ?? '-' }}

                    </td>

                    <td class="px-5 py-4">

                        @if($session->records_count > 0)

                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
                                เช็กแล้ว
                            </span>

                        @else

                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm">
                                ยังไม่เช็ก
                            </span>

                        @endif

                    </td>

                    <td class="px-5 py-4 text-center">

                        <a href="{{ route('teacher.attendance.create',$session) }}"
                           class="inline-block px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">

                            เช็กชื่อ

                        </a>

                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

    <div class="mt-6">

        {{ $sessions->links() }}

    </div>

    @endif

</div>
@endsection