@extends('layouts.admin')

@section('content')

<div class="w-full">

    {{-- Header --}}
<div class="bg-gradient-to-r from-indigo-600 to-purple-700 text-white rounded-3xl shadow-lg p-8 mb-8">

    <div class="flex justify-between items-center">

        <div>

            <h1 class="text-4xl font-bold mb-2">
                📚 จัดการตารางสอน
            </h1>

            <p class="text-indigo-100">
                ระบบจัดตารางสอน PSPP Platform
            </p>

        </div>

        <div class="flex gap-2">

            <a
                href="{{ route('admin.schedules.copy.form') }}"
                class="bg-emerald-500 hover:bg-emerald-600 px-5 py-3 rounded-xl font-semibold shadow"
            >
                📋 คัดลอกตารางสอน
            </a>

        </div>

    </div>

</div>

    {{-- Summary --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

        <div class="bg-white rounded-2xl shadow p-5">
            <div class="text-3xl font-bold text-indigo-600">
                {{ $classrooms->count() }}
            </div>
            <div class="text-gray-500">
                ห้องเรียน
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-5">
            <div class="text-3xl font-bold text-green-600">
                {{ $subjects->count() }}
             </div>
            <div class="text-gray-500">
                รายวิชา
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-5">
            <div class="text-3xl font-bold text-blue-600">
                {{ $teachers->count() }}
            </div>
            <div class="text-gray-500">
                ครูผู้สอน
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-5">
            <div class="text-3xl font-bold text-orange-600">
                2569
            </div>
            <div class="text-gray-500">
                ปีการศึกษา
            </div>
        </div>

    </div>

    {{-- Classrooms --}}
    <div class="mb-4">

        <h2 class="text-2xl font-bold mb-4">
            🏫 ห้องเรียน
        </h2>

    </div>

    <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-6">

        @foreach($classrooms as $classroom)

            <div
                class="bg-white rounded-2xl shadow hover:shadow-xl transition duration-300 overflow-hidden"
            >

                <div class="p-6">

                    <div class="text-3xl mb-3">
                        🎓
                    </div>

                    <h3 class="text-2xl font-bold">
                        {{ $classroom->name }}
                    </h3>

                    <p class="text-gray-500 text-sm mt-1">
                        {{ $classroom->level }}
                    </p>

                    <div class="mt-4 text-sm text-gray-600">
                        👨‍🎓 {{ $classroom->student_count ?? 0 }} คน
                    </div>

                    <a
                        href="{{ route('admin.schedules.timetable', $classroom) }}"
                        class="mt-5 block text-center bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-xl font-semibold"
                    >
                        จัดตารางสอน
                    </a>

                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection