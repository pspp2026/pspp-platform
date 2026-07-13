@extends('layouts.admin')

@section('content')

<div class="flex min-h-screen">

    {{-- 🔵 SIDEBAR --}}
    <div class="flex-1">

        {{-- Header --}}
        <div class="bg-white shadow px-6 py-4 flex justify-between items-center">

            <h1 class="text-2xl font-bold text-slate-800">
                🎓 จัดนักเรียนเข้าห้องเรียน
            </h1>

            <a href="{{ route('admin.enrollments.create') }}"
               class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg shadow transition">
                ➕ เพิ่มการลงทะเบียน
            </a>

        </div>

        {{-- Search & Filter --}}
<div class="bg-white rounded-xl shadow p-5 mb-6">

    <form method="GET" action="{{ route('admin.enrollments.index') }}">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">

            {{-- ค้นหา --}}
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    🔍 ค้นหา
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="รหัสนักเรียน / ชื่อ"
                    class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            {{-- ห้องเรียน --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    🏫 ห้องเรียน
                </label>

                <select
                    name="classroom_id"
                    class="w-full rounded-lg border-gray-300">

                    <option value="">ทั้งหมด</option>

                    @foreach($classrooms as $room)
                        <option
                            value="{{ $room->id }}"
                            {{ request('classroom_id') == $room->id ? 'selected' : '' }}>
                            {{ $room->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- ปีการศึกษา --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    📅 ปีการศึกษา
                </label>

                <input
                    type="number"
                    name="academic_year"
                    value="{{ request('academic_year') }}"
                    class="w-full rounded-lg border-gray-300">
            </div>

            {{-- ภาคเรียน --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    📖 ภาคเรียน
                </label>

                <select
                    name="semester"
                    class="w-full rounded-lg border-gray-300">

                    <option value="">ทั้งหมด</option>
                    <option value="1" {{ request('semester') == 1 ? 'selected' : '' }}>1</option>
                    <option value="2" {{ request('semester') == 2 ? 'selected' : '' }}>2</option>

                </select>
            </div>

            {{-- ปุ่ม --}}
            <div class="flex items-end gap-2">

                <button
                    type="submit"
                    class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-4 py-2">

                    🔍 ค้นหา

                </button>

                <a href="{{ route('admin.enrollments.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 rounded-lg px-4 py-2">

                    ♻️

                </a>

            </div>

        </div>

    </form>

</div>

        <div class="p-6">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-200 text-green-700 p-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-gray-100">

                        <tr>
                            <th class="p-3 text-left">รหัสนักเรียน</th>
                            <th class="p-3 text-left">ชื่อ</th>
                            <th class="p-3 text-left">โรงเรียน</th>
                            <th class="p-3 text-left">ห้องเรียน</th>
                            <th class="p-3 text-center">ปีการศึกษา</th>
                            <th class="p-3 text-center">ภาคเรียน</th>
                            <th class="p-3 text-center">สถานะ</th>
                            <th class="p-3 text-center">จัดการ</th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($enrollments as $item)

                        <tr class="border-t hover:bg-gray-50">

                            <td class="p-3">
                                {{ $item->student?->student_code ?? '-' }}
                            </td>

                            <td class="p-3">
                                {{ $item->student?->full_name
                                    ?? trim(($item->student?->prefix ?? '') . ' ' .
                                            ($item->student?->first_name ?? '') . ' ' .
                                            ($item->student?->last_name ?? ''))
                                    ?: '-' }}
                            </td>

                            <td class="p-3">
                                {{ $item->school?->school_name ?? '-' }}
                            </td>

                            <td class="p-3">
                                {{ $item->classroom?->name ?? '-' }}
                            </td>

                            <td class="p-3 text-center">
                                {{ $item->academic_year }}
                            </td>

                            <td class="p-3 text-center">
                                {{ $item->semester }}
                            </td>

                            <td class="p-3 text-center">

                                @if($item->status === 'active')

                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                        Active
                                    </span>

                                @else

                                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                                        {{ ucfirst($item->status) }}
                                    </span>

                                @endif

                            </td>

                            <td class="p-3 text-center whitespace-nowrap">

                                <a href="{{ route('admin.enrollments.edit', $item) }}"
                                   class="text-blue-600 hover:text-blue-800">
                                    ✏️ แก้ไข
                                </a>

                                <form action="{{ route('admin.enrollments.destroy', $item) }}"
                                      method="POST"
                                      class="inline">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            onclick="return confirm('ต้องการลบข้อมูลนี้ใช่หรือไม่?')"
                                            class="ml-3 text-red-600 hover:text-red-800">

                                        🗑️ ลบ

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="text-center p-8 text-gray-500">

                                ยังไม่มีข้อมูลการลงทะเบียนเรียน

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-6">
                {{ $enrollments->links() }}
            </div>

        </div>

    </div>

</div>

@endsection

