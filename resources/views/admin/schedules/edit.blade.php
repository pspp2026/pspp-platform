@extends('layouts.admin')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="flex justify-between items-center mb-6">

        <div>

            <h1 class="text-3xl font-bold">
                ✏️ แก้ไขตารางสอน
            </h1>

            <p class="text-gray-500 mt-1">
                แก้ไขข้อมูลตารางสอน
            </p>

        </div>

        <a
            href="{{ route('admin.schedules.timetable', $schedule->classroom_id) }}"
            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
        >
            ← กลับ
        </a>

    </div>

    @if ($errors->any())

        <div class="bg-red-100 border border-red-300 text-red-700 rounded-lg p-4 mb-6">

            <ul class="list-disc ml-6">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <div class="bg-white rounded-xl shadow p-6">

        <form
            action="{{ route('admin.schedules.update', $schedule) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-6">

                {{-- ปีการศึกษา --}}

                <div>

                    <label class="block font-semibold mb-2">
                        ปีการศึกษา / ภาคเรียน
                    </label>

                    <select
                        name="academic_term_id"
                        class="w-full border rounded-lg p-2"
                        required
                    >

                        @foreach($academicTerms as $term)

                            <option
                                value="{{ $term->id }}"
                                {{ $schedule->academic_term_id == $term->id ? 'selected' : '' }}
                            >
                                ปี {{ $term->academic_year }}
                                ภาคเรียน {{ $term->semester }}
                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- ห้องเรียน --}}

                <div>

                    <label class="block font-semibold mb-2">
                        ห้องเรียน
                    </label>

                    <select
                        name="classroom_id"
                        class="w-full border rounded-lg p-2"
                        required
                    >

                        @foreach($classrooms as $classroom)

                            <option
                                value="{{ $classroom->id }}"
                                {{ $schedule->classroom_id == $classroom->id ? 'selected' : '' }}
                            >
                                {{ $classroom->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- วันเรียน --}}

                <div>

                    <label class="block font-semibold mb-2">
                        วันเรียน
                    </label>

                    <select
                        name="day_of_week"
                        class="w-full border rounded-lg p-2"
                        required
                    >

                        <option value="Monday"
                            {{ $schedule->day_of_week == 'Monday' ? 'selected' : '' }}>
                            วันจันทร์
                        </option>

                        <option value="Tuesday"
                            {{ $schedule->day_of_week == 'Tuesday' ? 'selected' : '' }}>
                            วันอังคาร
                        </option>

                        <option value="Wednesday"
                            {{ $schedule->day_of_week == 'Wednesday' ? 'selected' : '' }}>
                            วันพุธ
                        </option>

                        <option value="Thursday"
                            {{ $schedule->day_of_week == 'Thursday' ? 'selected' : '' }}>
                            วันพฤหัสบดี
                        </option>

                        <option value="Friday"
                            {{ $schedule->day_of_week == 'Friday' ? 'selected' : '' }}>
                            วันศุกร์
                        </option>

                    </select>

                </div>

                {{-- คาบเรียน --}}

                <div>

                    <label class="block font-semibold mb-2">
                        คาบเรียน
                    </label>

                    <select
                        name="period_id"
                        class="w-full border rounded-lg p-2"
                        required
                    >

                        @foreach($periods as $period)

                            <option
                                value="{{ $period->id }}"
                                {{ $schedule->period_id == $period->id ? 'selected' : '' }}
                            >
                                {{ $period->name }}
                                ({{ $period->start_time }} - {{ $period->end_time }})
                            </option>

                        @endforeach

                    </select>

                </div>
                                {{-- วิชา --}}

                <div>

                    <label class="block font-semibold mb-2">
                        วิชา
                    </label>

                    <select
                        name="subject_id"
                        class="w-full border rounded-lg p-2"
                        required
                    >

                        @foreach($subjects as $subject)

                            <option
                                value="{{ $subject->id }}"
                                {{ $schedule->subject_id == $subject->id ? 'selected' : '' }}
                            >
                                {{ $subject->subject_code }}
                                -
                                {{ $subject->subject_name }}
                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- ครูผู้สอน --}}

                <div>

                    <label class="block font-semibold mb-2">
                        ครูผู้สอน
                    </label>

                    <select
                        name="teacher_id"
                        class="w-full border rounded-lg p-2"
                        required
                    >

                        @foreach($teachers as $teacher)

                            <option
                                value="{{ $teacher->id }}"
                                {{ $schedule->teacher_id == $teacher->id ? 'selected' : '' }}
                            >
                                {{ $teacher->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

            <div class="mt-8 flex justify-end gap-3">

                <a
                    href="{{ route('admin.schedules.timetable', $schedule->classroom_id) }}"
                    class="px-5 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg"
                >
                    ↩️ ยกเลิก
                </a>

                <button
                    type="submit"
                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg"
                >
                    💾 บันทึกการแก้ไข
                </button>

            </div>

        </form>

    </div>

</div>

@endsection