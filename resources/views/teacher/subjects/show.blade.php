@extends('layouts.teacher')

@section('teacher-content')

<div class="bg-white rounded-xl shadow p-6">

    {{-- =========================
         HEADER
    ========================== --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-3xl shadow-lg p-8 mb-6">

        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">

            <div>

                <div class="text-blue-100 text-sm">
                    รหัสวิชา
                </div>

                <h1 class="text-4xl font-bold mt-1">
                    {{ $subject->subject_code }}
                </h1>

                <h2 class="text-xl text-blue-100 mt-2">
                    {{ $subject->subject_name }}
                </h2>

            </div>

            <div class="flex flex-wrap gap-2">

                <span class="bg-white/20 px-4 py-2 rounded-full text-sm">
                    📚 รายวิชา
                </span>

                <span class="bg-white/20 px-4 py-2 rounded-full text-sm">
                    🎯 หลักสูตรแกนกลาง
                </span>

            </div>

        </div>

    </div>

    {{-- =========================
         ACTION BUTTONS
    ========================== --}}

    <div class="flex flex-wrap gap-3 mb-6">

        <a href="{{ route('units.create', $subject->id) }}"
           class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl shadow transition">

            ➕ เพิ่มหน่วยการเรียนรู้

        </a>

        <a href="#"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl shadow transition">

            🎯 มาตรฐานการเรียนรู้

        </a>

        <a href="#"
           class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-3 rounded-xl shadow transition">

            📑 ตัวชี้วัด

        </a>

        <a href="#"
           class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-3 rounded-xl shadow transition">

            📝 แผนการสอน

        </a>

    </div>

    {{-- =========================
         SUMMARY
    ========================== --}}

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

        <div class="bg-white rounded-2xl shadow p-5 border">

            <div class="text-gray-500 text-sm">
                หน่วยการเรียนรู้
            </div>

            <div class="text-3xl font-bold text-blue-600 mt-2">
                {{ $subject->units->count() }}
            </div>

        </div>

        <div class="bg-white rounded-2xl shadow p-5 border">

            <div class="text-gray-500 text-sm">
                มาตรฐาน
            </div>

            <div class="text-3xl font-bold text-green-600 mt-2">
                0
            </div>

        </div>

        <div class="bg-white rounded-2xl shadow p-5 border">

            <div class="text-gray-500 text-sm">
                ตัวชี้วัด
            </div>

            <div class="text-3xl font-bold text-purple-600 mt-2">
                0
            </div>

        </div>

    </div>

    {{-- =========================
         LEARNING UNITS
    ========================== --}}

    <div class="bg-white rounded-3xl shadow-lg border">

        <div class="flex justify-between items-center p-6 border-b">

            <h3 class="text-xl font-bold text-gray-800">
                📚 หน่วยการเรียนรู้
            </h3>

            <span class="text-sm text-gray-500">
                ทั้งหมด {{ $subject->units->count() }} หน่วย
            </span>

        </div>

        <div class="p-6">

            @if($subject->units->count())

                <div class="space-y-4">

                    @foreach($subject->units as $unit)

                        <div class="border rounded-2xl p-5 hover:shadow-md transition">

                            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-5">

                                <div>

                                    <div class="font-bold text-blue-700">

                                        📚 หน่วยที่ {{ $unit->unit_no }}

                                    </div>

                                    <div class="text-xl font-semibold mt-2">

                                        {{ $unit->unit_name }}

                                    </div>

                                    <div class="text-gray-500 mt-2">

                                        ⏰ {{ $unit->hours }} ชั่วโมง

                                    </div>

                                </div>

                                <div class="flex flex-wrap gap-2">
                                       

                                    <a href="{{ route('units.show', [$subject->id, $unit->id]) }}"
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl shadow transition">

                                        📖 ดูรายละเอียด

                                    </a>

                                    <a href="{{ route('units.edit', [$subject->id, $unit->id]) }}"
                                       class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-xl shadow transition">

                                        ✏️ แก้ไข

                                    </a>

                                    <form action="{{ route('units.destroy', [$subject->id, $unit->id]) }}"
                                          method="POST"
                                          onsubmit="return confirm('ยืนยันการลบหน่วยการเรียนรู้?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl shadow transition">

                                            🗑️ ลบ

                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="text-center py-16">

                    <div class="text-6xl mb-4">
                        📖
                    </div>

                    <h3 class="text-xl font-semibold text-gray-700">
                        ยังไม่มีหน่วยการเรียนรู้
                    </h3>

                    <p class="text-gray-500 mt-3">
                        เริ่มต้นออกแบบรายวิชา โดยสร้างหน่วยการเรียนรู้แรก
                    </p>

                    <a href="{{ route('units.create', $subject->id) }}"
                       class="inline-block mt-6 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl shadow transition">

                        ➕ เพิ่มหน่วยการเรียนรู้

                    </a>

                </div>

            @endif

        </div>

    </div>

    {{-- Back Button --}}
    <div class="mt-8">

        <a href="{{ route('teacher.subjects') }}"
           class="inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white px-5 py-3 rounded-xl shadow transition">

            ← กลับไปรายวิชาของฉัน

        </a>

    </div>

</div>

@endsection