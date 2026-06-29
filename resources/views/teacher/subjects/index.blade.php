@extends('layouts.teacher')

@section('teacher-content')

<div class="max-w-7xl mx-auto">

    <div class="flex justify-between items-center mb-6">

        <h1 class="text-3xl font-bold">
            📚 รายวิชาของฉัน
        </h1>

        <a href="{{ route('teacher.subjects.manage') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">

            ⚙️ จัดการรายวิชาที่สอน

        </a>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($subjects as $subject)

            <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-5">

                <div class="text-sm text-blue-600 font-semibold">
                    {{ $subject->subject_code }}
                </div>

                <h2 class="text-lg font-bold mt-2">
                    {{ $subject->subject_name }}
                </h2>

                <div class="mt-2 text-sm text-gray-500">

                    @if(isset($subject->credits))
                        {{ $subject->credits }} หน่วยกิต
                    @endif

                    @if(isset($subject->hours))
                        • {{ $subject->hours }} ชั่วโมง
                    @endif

                </div>

                <div class="mt-5">

                    <a href="{{ route('subjects.show', $subject->id) }}"
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">

                        📖 เปิดรายวิชา

                    </a>

                </div>

            </div>

        @empty

            <div class="col-span-full">

                <div class="bg-white rounded-xl shadow p-10 text-center">

                    <div class="text-5xl mb-4">
                        📚
                    </div>

                    <h2 class="text-xl font-semibold text-gray-700">
                        ยังไม่มีรายวิชาที่สอน
                    </h2>

                    <p class="text-gray-500 mt-2">
                        กรุณาให้ผู้ดูแลระบบกำหนดรายวิชาที่สอน
                        หรือกดปุ่ม "จัดการรายวิชาที่สอน"
                    </p>

                    <a href="{{ route('teacher.subjects.manage') }}"
                       class="inline-block mt-6 px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">

                        ⚙️ จัดการรายวิชาที่สอน

                    </a>

                </div>

            </div>

        @endforelse

    </div>

</div>

@endsection