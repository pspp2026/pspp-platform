@extends('layouts.admin')

@section('content')

<div class="flex min-h-screen bg-gray-100">

    @include('admin.partials.sidebar')

    <div class="flex-1">

        {{-- Header --}}
        <div class="bg-white shadow px-6 py-4 flex justify-between items-center">

            <h1 class="text-2xl font-bold">
                🎓 <h1>แก้ไขการลงทะเบียน</h1>
            </h1>

            <a href="{{ route('admin.enrollments.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                ← กลับ

            </a>

        </div>

        <div class="p-6">

            @if ($errors->any())

                <div class="mb-5 bg-red-100 text-red-700 rounded-lg p-4">

                    <ul class="list-disc ml-5">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <form action="{{ route('admin.enrollments.update', $enrollment) }}" method="POST">
            @csrf
            @method('PUT')

                <div class="bg-white rounded-xl shadow p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- นักเรียน --}}
                        <div>
                            <label class="font-medium">นักเรียน</label>

                            <div class="mt-2 rounded-lg border bg-gray-100 p-3">
                                {{ $enrollment->student->student_code }}
                                -
                                {{ $enrollment->student->full_name }}
                            </div>

                            <input
                                type="hidden"
                                name="student_id"
                                value="{{ $enrollment->student_id }}">
                        </div>


     
                        {{-- ห้องเรียน --}}
                        <div>

                            <label class="font-medium">
                                ห้องเรียน
                            </label>

                            <select
                                name="classroom_id"
                                class="w-full mt-2 border rounded-lg p-2"
                                required>

                                <option value="">-- เลือกห้องเรียน --</option>

                                @foreach($classrooms as $room)

                                    <option
                                        value="{{ $room->id }}"
                                        {{ old('classroom_id')==$room->id?'selected':'' }}>

                                        {{ $room->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- ภาคเรียน --}}
                        <div>

                            <label class="font-medium">
                                ภาคเรียน
                            </label>

                            <select
                                name="academic_term_id"
                                class="w-full mt-2 border rounded-lg p-2"
                                required>

                                <option value="">-- เลือกภาคเรียน --</option>

                                @foreach($terms as $term)

                                    <option
                                        value="{{ $term->id }}"
                                        {{ old('academic_term_id')==$term->id?'selected':'' }}>

                                        ปี {{ $term->academic_year }}
                                        /
                                        เทอม {{ $term->semester }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                    

                        {{-- สถานะ --}}
                        <div>

                            <label class="font-medium">

                                สถานะ

                            </label>

                            <select
                                name="status"
                                class="w-full mt-2 border rounded-lg p-2">

                                <option value="active">
                                    Active
                                </option>

                                <option value="graduated">
                                    Graduated
                                </option>

                                <option value="transferred">
                                    Transferred
                                </option>

                                <option value="inactive">
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>

                    <div class="mt-8">

                        <button
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-lg">

                            💾 บันทึกข้อมูล

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection