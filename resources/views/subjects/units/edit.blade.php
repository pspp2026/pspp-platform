@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto p-6">

    <div class="bg-white rounded-3xl shadow-lg p-8">

        <div class="mb-6">

            <h1 class="text-3xl font-bold text-yellow-600">
                ✏️ แก้ไขหน่วยการเรียนรู้
            </h1>

            <p class="text-gray-500 mt-2">
                {{ $unit->subject->subject_code }}
                :
                {{ $unit->subject->subject_name }}
            </p>

        </div>

        <form method="POST"
              action="{{ route('units.update',$unit->id) }}">

            @csrf
            @method('PUT')

            <div class="mb-5">

                <label class="block mb-2 font-semibold">
                    หน่วยที่
                </label>

                <input
                    type="number"
                    value="{{ $unit->unit_no }}"
                    class="w-full border rounded-xl p-3 bg-gray-100"
                    readonly>

            </div>

            <div class="mb-5">

                <label class="block mb-2 font-semibold">
                    ชื่อหน่วยการเรียนรู้
                </label>

                <input
                    type="text"
                    name="unit_name"
                    value="{{ old('unit_name',$unit->unit_name) }}"
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
                    value="{{ old('hours',$unit->hours) }}"
                    class="w-full border rounded-xl p-3">

            </div>

            <div class="mb-6">

                <label class="block mb-2 font-semibold">
                    รายละเอียด
                </label>

                <textarea
                    name="description"
                    rows="5"
                    class="w-full border rounded-xl p-3">{{ old('description',$unit->description) }}</textarea>

            </div>

            <div class="flex gap-3">

                <button
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-xl">

                    💾 บันทึกการแก้ไข

                </button>

                <a href="{{ route('subjects.show',$unit->subject_id) }}"
                   class="bg-gray-300 hover:bg-gray-400 px-6 py-3 rounded-xl">

                    ยกเลิก

                </a>

            </div>

        </form>

    </div>

</div>

@endsection