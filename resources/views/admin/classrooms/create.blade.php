@extends('layouts.admin')

@section('content')

<div class="w-full">

<div class="bg-white rounded-2xl shadow p-6">

    <h1 class="text-2xl font-bold mb-6">
        ➕ เพิ่มห้องเรียน
    </h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.classrooms.store') }}"
          method="POST">

        @csrf

        {{-- โรงเรียน --}}
        <div class="mb-4">
            <label class="block mb-2 font-medium">
                โรงเรียน
            </label>

            <select name="school_id"
                    class="w-full border rounded-lg p-3">

                <option value="">
                    -- เลือกโรงเรียน --
                </option>

                @foreach($schools as $school)
                    <option value="{{ $school->id }}">
                        {{ $school->school_name }}
                    </option>
                @endforeach

            </select>
        </div>

        {{-- ชื่อห้อง --}}
        <div class="mb-4">
            <label class="block mb-2 font-medium">
                ชื่อห้องเรียน
            </label>

            <input type="text"
                id="classroom_name"
                class="w-full border rounded-lg p-3 bg-gray-100"
                readonly>
        </div>

        {{-- ระดับชั้น --}}
        <div class="mb-4">
            <label class="block mb-2 font-medium">
                ระดับชั้น
            </label>

            <select name="level"
                    class="w-full border rounded-lg p-3"
                    required>

                <option value="">-- เลือกระดับชั้น --</option>

                <option value="ม.1">ม.1</option>
                <option value="ม.2">ม.2</option>
                <option value="ม.3">ม.3</option>
                <option value="ม.4">ม.4</option>
                <option value="ม.5">ม.5</option>
                <option value="ม.6">ม.6</option>

            </select>
        </div>

        {{-- เลขห้อง --}}
        <div class="mb-4">
            <label class="block mb-2 font-medium">
                เลขห้อง
            </label>

            <input type="number"
                name="room"
                min="1"
                value="1"
                class="w-full border rounded-lg p-3"
                required>
        </div>

        {{-- จำนวนนักเรียน --}}
        <div class="mb-6">
            <label class="block mb-2 font-medium">
                จำนวนนักเรียน
            </label>

            <input type="number"
                   name="student_count"
                   value="0"
                   min="0"
                   class="w-full border rounded-lg p-3">
        </div>

        <div class="flex gap-3">

            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg">
                💾 บันทึก
            </button>

            <a href="{{ route('admin.classrooms.index') }}"
               class="bg-gray-300 px-6 py-3 rounded-lg">
                ยกเลิก
            </a>

        </div>

    </form>

</div>

</div>

@endsection
