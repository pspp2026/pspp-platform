@extends('layouts.app')

@section('content')

<div class="flex gap-6 p-6">

    {{-- Sidebar --}}
    @include('teacher.sidebar')

    {{-- Main Content --}}
    <div class="flex-1">

        <div class="bg-white rounded-3xl shadow-lg p-8">

            <div class="mb-6">

                <h1 class="text-3xl font-bold text-yellow-600">
                    ✏️ แก้ไขหน่วยการเรียนรู้
                </h1>

                <p class="text-gray-500 mt-2">

                    @if($unit->subject)

                        {{ $unit->subject->subject_code }}
                        :
                        {{ $unit->subject->subject_name }}

                    @endif

                </p>

            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-700 p-4 rounded-xl mb-6">
                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('units.update',$unit->id) }}">

                @csrf
                @method('PUT')

                {{-- ลำดับหน่วย --}}
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

                {{-- ชื่อหน่วย --}}
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

                {{-- ชั่วโมง --}}
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

                {{-- รายละเอียด --}}
                <div class="mb-6">

                    <label class="block mb-2 font-semibold">
                        รายละเอียด
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        class="w-full border rounded-xl p-3">{{ old('description',$unit->description) }}</textarea>

                </div>

                {{-- ปุ่ม --}}
                <div class="flex gap-3">

                    <button
                        type="submit"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-xl">

                        💾 บันทึกการแก้ไข

                    </button>

                    <a href="{{ route('subjects.show',$unit->subject_id) }}"
                       class="bg-gray-300 hover:bg-gray-400 px-6 py-3 rounded-xl">

                        ↩️ ยกเลิก

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection