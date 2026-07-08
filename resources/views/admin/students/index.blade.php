@extends('layouts.admin')

@section('content')

<div class="p-6 space-y-6">

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">🎓 ทะเบียนนักเรียน</h1>
            <p class="mt-1 text-sm text-gray-500">
                จัดการรายชื่อนักเรียนก่อนสร้างบัญชีเข้าใช้งาน
            </p>
        </div>

        <a href="{{ route('admin.students.create') }}"
           class="inline-flex items-center justify-center px-5 py-3 text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700 transition">
            + เพิ่มนักเรียน
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 text-green-800 bg-green-100 border border-green-200 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 text-red-800 bg-red-100 border border-red-200 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <form method="GET"
          action="{{ route('admin.students.index') }}"
          class="grid grid-cols-1 gap-3 p-4 bg-white rounded-xl shadow md:grid-cols-4">

        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="ค้นหารหัส ชื่อ หรือเลขบัตร"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg md:col-span-2">

        <select name="school_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            <option value="">ทุกโรงเรียน</option>

            @foreach($schools as $school)
                <option value="{{ $school->id }}"
                    @selected((string) request('school_id') === (string) $school->id)>
                    {{ $school->school_name }}
                </option>
            @endforeach
        </select>

        <div class="flex gap-2">
            <button type="submit"
                    class="flex-1 px-4 py-2 font-medium text-white bg-gray-700 rounded-lg hover:bg-gray-800">
                ค้นหา
            </button>

            <a href="{{ route('admin.students.index') }}"
               class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                ล้าง
            </a>
        </div>
    </form>

    <div class="overflow-hidden bg-white rounded-xl shadow">

        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">
                รายชื่อนักเรียนทั้งหมด
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4 text-left">รหัสนักเรียน</th>
                        <th class="p-4 text-left">ชื่อ-นามสกุล</th>
                        <th class="p-4 text-left">โรงเรียน / ห้อง</th>
                        <th class="p-4 text-left">วัด</th>
                        <th class="p-4 text-center">บัญชี</th>
                        <th class="p-4 text-right">จัดการ</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($students as $student)
                        <tr class="border-t hover:bg-gray-50">

                            <td class="p-4 font-medium text-gray-800">
                                {{ $student->student_code }}
                            </td>

                            <td class="p-4 text-gray-700">
                                {{ trim(($student->prefix ? $student->prefix . ' ' : '') . $student->first_name . ' ' . $student->last_name) }}

                                @if($student->id_card)
                                    <div class="mt-1 text-xs text-gray-400">
                                        เลขบัตร: {{ $student->id_card }}
                                    </div>
                                @endif
                            </td>

                            <td class="p-4 text-gray-700">
                                <div>
                                    {{ $student->school?->school_name ?? '-' }}
                                </div>

                                <div class="mt-1 text-xs text-gray-400">
                                    {{ $student->classroom?->name ?? 'ยังไม่กำหนดห้อง' }}
                                </div>
                            </td>

                            <td class="p-4 text-gray-700">
                                {{ $student->temple?->temple_name ?? '-' }}
                            </td>

                            <td class="p-4 text-center">
                                @if($student->user)
                                    <span class="inline-flex px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                        มีบัญชีแล้ว
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full">
                                        ยังไม่มีบัญชี
                                    </span>
                                @endif
                            </td>

                            <td class="p-4 text-right">
                                <div class="inline-flex items-center gap-2">

                                    <a href="{{ route('admin.students.edit', $student) }}"
                                       class="px-3 py-1.5 text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100">
                                        แก้ไข
                                    </a>

                                    @if(!$student->user_id)
                                        <form method="POST"
                                              action="{{ route('admin.students.destroy', $student) }}"
                                              class="inline"
                                              onsubmit="return confirm('ยืนยันการลบรายชื่อนักเรียนนี้หรือไม่?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="px-3 py-1.5 text-red-700 bg-red-50 rounded-lg hover:bg-red-100">
                                                ลบ
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-gray-500">
                                ยังไม่มีรายชื่อนักเรียน
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        @if($students->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $students->links() }}
            </div>
        @endif

    </div>

</div>

@endsection