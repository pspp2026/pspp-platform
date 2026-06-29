@extends('layouts.app')

@section('content')

<div class="flex gap-6">

    {{-- Sidebar --}}
    @include('teacher.sidebar')

    {{-- Content --}}
    <div class="flex-1">

        <div class="bg-white rounded-xl shadow p-6">

    <div class="bg-white rounded-3xl shadow-lg p-8">

        <div class="mb-6">

            <h1 class="text-3xl font-bold text-blue-700">
                ➕ เพิ่มหน่วยการเรียนรู้
            </h1>

            <p class="text-gray-500 mt-2">
                {{ $subject->subject_code }}
                :
                {{ $subject->subject_name }}
            </p>

        </div>

        <form method="POST"
              action="{{ route('units.store',$subject->id) }}">

            @csrf

            <div class="mb-5">

                <label class="block mb-2 font-semibold">
                    หน่วยที่
                </label>

                <input
                    type="number"
                    name="unit_no"
                    class="w-full border rounded-xl p-3"
                    required>

            </div>

            <div class="mb-5">

                <label class="block mb-2 font-semibold">
                    ชื่อหน่วยการเรียนรู้
                </label>

                <input
                    type="text"
                    name="unit_name"
                    class="w-full border rounded-xl p-3"
                    required>

            </div>

            <div class="mb-5">

                <label class="block mb-2 font-semibold">
                    จำนวนชั่วโมง
                </label>

                <input
                    type="number"
                    name="hours"
                    class="w-full border rounded-xl p-3"
                    value="0">

            </div>

            <div class="mb-6">

                <label class="block mb-2 font-semibold">
                    รายละเอียด
                </label>

                <textarea
                    name="description"
                    rows="5"
                    class="w-full border rounded-xl p-3"></textarea>

            </div>

            <div class="flex gap-3">

                <button
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl">

                    💾 บันทึก

                </button>

                <a href="{{ route('subjects.show',$subject->id) }}"
                   class="bg-gray-300 hover:bg-gray-400 px-6 py-3 rounded-xl">

                    ยกเลิก

                </a>

            </div>

        </form>

    </div>

</div>

@endsection