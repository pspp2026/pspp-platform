@extends('layouts.app')

@section('title', 'ประวัติการเข้าใช้งานระบบ')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                ประวัติการเข้าใช้งานระบบ
            </h1>
            <p class="text-gray-500 mt-1">
                ตรวจสอบการ Login และ Logout ของผู้ใช้งานทั้งหมด
            </p>
        </div>

        <div class="text-sm text-gray-500">
            ทั้งหมด {{ number_format($logs->total()) }} รายการ
        </div>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-xl shadow p-5 mb-6">

        <form method="GET">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                {{-- Keyword --}}
                <div>
                    <label class="block text-sm font-medium mb-2">
                        ค้นหาผู้ใช้งาน
                    </label>

                    <input
                        type="text"
                        name="keyword"
                        value="{{ request('keyword') }}"
                        placeholder="ชื่อ หรือ Email"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                {{-- School --}}
                <div>
                    <label class="block text-sm font-medium mb-2">
                        โรงเรียน
                    </label>

                    <select
                        name="school_id"
                        class="w-full rounded-lg border-gray-300">

                        <option value="">ทั้งหมด</option>

                        @foreach($schools as $school)
                            <option
                                value="{{ $school->id }}"
                                @selected(request('school_id') == $school->id)>
                                {{ $school->school_name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- Role --}}
                <div>

                    <label class="block text-sm font-medium mb-2">
                        สิทธิ์
                    </label>

                    <select
                        name="role"
                        class="w-full rounded-lg border-gray-300">

                        <option value="">ทั้งหมด</option>
                        <option value="superadmin" @selected(request('role')=='superadmin')>SuperAdmin</option>
                        <option value="admin" @selected(request('role')=='admin')>Admin</option>
                        <option value="director" @selected(request('role')=='director')>Director</option>
                        <option value="teacher" @selected(request('role')=='teacher')>Teacher</option>
                        <option value="student" @selected(request('role')=='student')>Student</option>
                        <option value="staff" @selected(request('role')=='staff')>Staff</option>

                    </select>

                </div>

                {{-- Buttons --}}
                <div class="flex items-end gap-2">

                    <button
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">

                        ค้นหา

                    </button>

                    <a
                        href="{{ route('superadmin.user-login-logs.index') }}"
                        class="px-5 py-2 bg-gray-200 rounded-lg">

                        รีเซ็ต

                    </a>

                </div>

            </div>

        </form>

    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-gray-100">

                <tr>

                    <th class="px-4 py-3 text-left">
                        ผู้ใช้งาน
                    </th>

                    <th class="px-4 py-3 text-left">
                        โรงเรียน
                    </th>

                    <th class="px-4 py-3 text-center">
                        สิทธิ์
                    </th>

                    <th class="px-4 py-3 text-center">
                        Login
                    </th>

                    <th class="px-4 py-3 text-center">
                        Logout
                    </th>

                    <th class="px-4 py-3 text-center">
                        ระยะเวลา
                    </th>

                    <th class="px-4 py-3 text-center">
                        IP
                    </th>

                </tr>

                </thead>

                <tbody>

                @forelse($logs as $log)

                    <tr class="border-t hover:bg-gray-50">

                        <td class="px-4 py-4">

                            <div class="font-semibold">

                                {{ $log->user->name ?? '-' }}

                            </div>

                            <div class="text-xs text-gray-500">

                                {{ $log->user->email ?? '-' }}

                            </div>

                        </td>

                        <td class="px-4 py-4">

                            {{ $log->school->school_name ?? '-' }}

                        </td>

                        <td class="px-4 py-4 text-center">

                            @php

                                $badge = match($log->role){

                                    'superadmin' => 'bg-red-100 text-red-700',

                                    'admin' => 'bg-blue-100 text-blue-700',

                                    'director' => 'bg-purple-100 text-purple-700',

                                    'teacher' => 'bg-green-100 text-green-700',

                                    'student' => 'bg-yellow-100 text-yellow-700',

                                    'staff' => 'bg-gray-200 text-gray-700',

                                    default => 'bg-gray-100'

                                };

                            @endphp

                            <span class="px-3 py-1 rounded-full text-xs {{ $badge }}">

                                {{ ucfirst($log->role) }}

                            </span>

                        </td>

                        <td class="px-4 py-4 text-center">

                            {{ $log->login_at?->format('d/m/Y H:i') }}

                        </td>

                        <td class="px-4 py-4 text-center">

                            @if($log->logout_at)

                                {{ $log->logout_at->format('d/m/Y H:i') }}

                            @else

                                <span class="text-green-600 font-semibold">

                                    Online

                                </span>

                            @endif

                        </td>

                        <td class="px-4 py-4 text-center">

                            @if($log->logout_at)

                                {{ $log->login_at->diffForHumans($log->logout_at, true) }}

                            @else

                                -

                            @endif

                        </td>

                        <td class="px-4 py-4 text-center text-sm">

                            {{ $log->ip_address }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7"
                            class="text-center py-10 text-gray-500">

                            ยังไม่มีข้อมูล

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- Pagination --}}
    <div class="mt-6">

        {{ $logs->withQueryString()->links() }}

    </div>

</div>
@endsection