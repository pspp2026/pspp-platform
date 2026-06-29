@extends('layouts.admin')

@section('content')

<div class="w-full">

    <h1 class="text-2xl font-bold mb-6">
        เพิ่มตารางสอน
    </h1>

    <form action="{{ route('admin.schedules.store') }}"
          method="POST"
          class="space-y-4">

        @csrf

        <div>
            <label>ห้องเรียน</label>

            <select name="classroom_id"
                    class="w-full border rounded p-2">

                @foreach($classrooms as $classroom)

                    <option value="{{ $classroom->id }}">
                        {{ $classroom->name }}
                    </option>

                @endforeach

            </select>
        </div>

        <div>
            <label>วัน</label>

            <select name="day_of_week"
                    class="w-full border rounded p-2">

                <option value="Monday">จันทร์</option>
                <option value="Tuesday">อังคาร</option>
                <option value="Wednesday">พุธ</option>
                <option value="Thursday">พฤหัสบดี</option>
                <option value="Friday">ศุกร์</option>

            </select>
        </div>

        <div>
            <label>คาบ</label>

            <select name="period_id"
                    class="w-full border rounded p-2">

                @foreach($periods as $period)

                    <option value="{{ $period->id }}">
                        {{ $period->name }}
                    </option>

                @endforeach

            </select>
        </div>

        <div>
            <label>วิชา</label>

            <select name="subject_id"
                    class="w-full border rounded p-2">

                @foreach($subjects as $subject)

                    <option value="{{ $subject->id }}">
                        {{ $subject->subject_name }}
                    </option>

                @endforeach

            </select>
        </div>

        <div>
            <label>ครูผู้สอน</label>

            <select name="teacher_id"
                    class="w-full border rounded p-2">

                @foreach($teachers as $teacher)

                    <option value="{{ $teacher->id }}">
                        {{ $teacher->name }}
                    </option>

                @endforeach

            </select>
        </div>

        <button
            class="bg-blue-600 text-white px-5 py-2 rounded">

            บันทึก
        </button>

    </form>

</div>

@endsection