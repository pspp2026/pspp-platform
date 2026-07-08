@extends('layouts.admin')

@section('content')

<div class="p-6 space-y-6 max-w-5xl mx-auto">

    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">➕ เพิ่มนักเรียน</h1>
            <p class="mt-1 text-sm text-gray-500">
                เพิ่มข้อมูลทะเบียนนักเรียน โดยยังไม่ต้องสร้างบัญชีเข้าใช้งาน
            </p>
        </div>

        <a href="{{ route('admin.students.index') }}"
           class="inline-flex items-center justify-center px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
            ← กลับหน้าทะเบียนนักเรียน
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 text-red-800 bg-red-100 border border-red-200 rounded-xl">
            <p class="font-semibold mb-2">กรุณาตรวจสอบข้อมูลอีกครั้ง</p>
            <ul class="list-disc pl-5 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.students.store') }}"
          class="bg-white rounded-xl shadow overflow-hidden">
        @csrf

        <div class="p-6 space-y-8">

            {{-- ข้อมูลสังกัด --}}
            <section>
                <h2 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">
                    🏫 ข้อมูลสังกัดและห้องเรียน
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>
                        <label for="school_id" class="block mb-1 text-sm font-medium text-gray-700">
                            โรงเรียน <span class="text-red-500">*</span>
                        </label>

                        <select id="school_id"
                                name="school_id"
                                class="w-full px-3 py-2 border rounded-lg @error('school_id') border-red-500 @else border-gray-300 @enderror"
                                required>
                            <option value="">-- เลือกโรงเรียน --</option>

                            @foreach($schools as $school)
                                <option value="{{ $school->id }}"
                                    @selected(old('school_id') == $school->id)>
                                    {{ $school->school_name }}
                                </option>
                            @endforeach
                        </select>

                        @error('school_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="classroom_id" class="block mb-1 text-sm font-medium text-gray-700">
                            ห้องเรียน
                        </label>

                        <select id="classroom_id"
                                name="classroom_id"
                                class="w-full px-3 py-2 border rounded-lg @error('classroom_id') border-red-500 @else border-gray-300 @enderror">
                            <option value="">-- ยังไม่กำหนดห้อง --</option>

                            @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}"
                                    data-school-id="{{ $classroom->school_id }}"
                                    @selected(old('classroom_id') == $classroom->id)>
                                    {{ $classroom->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('classroom_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="temple_id" class="block mb-1 text-sm font-medium text-gray-700">
                            วัด
                        </label>

                        <select id="temple_id"
                                name="temple_id"
                                class="w-full px-3 py-2 border rounded-lg @error('temple_id') border-red-500 @else border-gray-300 @enderror">
                            <option value="">-- ไม่ระบุวัด --</option>

                            @foreach($temples as $temple)
                                <option value="{{ $temple->id }}"
                                    @selected(old('temple_id') == $temple->id)>
                                    {{ $temple->temple_name }}
                                </option>
                            @endforeach
                        </select>

                        @error('temple_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </section>

            {{-- ข้อมูลทะเบียน --}}
            <section>
                <h2 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">
                    🎓 ข้อมูลทะเบียนนักเรียน
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <div>
                        <label for="student_code" class="block mb-1 text-sm font-medium text-gray-700">
                            รหัสนักเรียน <span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                               id="student_code"
                               name="student_code"
                               value="{{ old('student_code') }}"
                               class="w-full px-3 py-2 border rounded-lg @error('student_code') border-red-500 @else border-gray-300 @enderror"
                               placeholder="เช่น 65001"
                               required>

                        @error('student_code')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="prefix" class="block mb-1 text-sm font-medium text-gray-700">
                            คำนำหน้า
                        </label>

                        <input type="text"
                               id="prefix"
                               name="prefix"
                               value="{{ old('prefix') }}"
                               class="w-full px-3 py-2 border rounded-lg border-gray-300"
                               placeholder="เช่น นาย / สามเณร">
                    </div>

                    <div>
                        <label for="first_name" class="block mb-1 text-sm font-medium text-gray-700">
                            ชื่อ <span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                               id="first_name"
                               name="first_name"
                               value="{{ old('first_name') }}"
                               class="w-full px-3 py-2 border rounded-lg @error('first_name') border-red-500 @else border-gray-300 @enderror"
                               required>

                        @error('first_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block mb-1 text-sm font-medium text-gray-700">
                            นามสกุล <span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                               id="last_name"
                               name="last_name"
                               value="{{ old('last_name') }}"
                               class="w-full px-3 py-2 border rounded-lg @error('last_name') border-red-500 @else border-gray-300 @enderror"
                               required>

                        @error('last_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </section>

            {{-- ข้อมูลส่วนตัว --}}
            <section>
                <h2 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">
                    📋 ข้อมูลส่วนตัว
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label for="id_card" class="block mb-1 text-sm font-medium text-gray-700">
                            เลขบัตรประชาชน
                        </label>

                        <input type="text"
                               id="id_card"
                               name="id_card"
                               value="{{ old('id_card') }}"
                               maxlength="13"
                               inputmode="numeric"
                               class="w-full px-3 py-2 border rounded-lg @error('id_card') border-red-500 @else border-gray-300 @enderror"
                               placeholder="13 หลัก">

                        @error('id_card')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block mb-1 text-sm font-medium text-gray-700">
                            วันเกิด
                        </label>

                        <input type="date"
                               id="birth_date"
                               name="birth_date"
                               value="{{ old('birth_date') }}"
                               class="w-full px-3 py-2 border rounded-lg @error('birth_date') border-red-500 @else border-gray-300 @enderror">

                        @error('birth_date')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nationality" class="block mb-1 text-sm font-medium text-gray-700">
                            สัญชาติ
                        </label>

                        <input type="text"
                               id="nationality"
                               name="nationality"
                               value="{{ old('nationality', 'ไทย') }}"
                               class="w-full px-3 py-2 border rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label for="ethnicity" class="block mb-1 text-sm font-medium text-gray-700">
                            เชื้อชาติ
                        </label>

                        <input type="text"
                               id="ethnicity"
                               name="ethnicity"
                               value="{{ old('ethnicity', 'ไทย') }}"
                               class="w-full px-3 py-2 border rounded-lg border-gray-300">
                    </div>

                </div>
            </section>

        </div>

        <div class="flex items-center justify-between gap-3 px-6 py-4 bg-gray-50 border-t">
            <a href="{{ route('admin.students.index') }}"
               class="px-5 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                ยกเลิก
            </a>

            <button type="submit"
                    class="px-6 py-2 font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                💾 บันทึกรายชื่อนักเรียน
            </button>
        </div>

    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const schoolSelect = document.getElementById('school_id');
    const classroomSelect = document.getElementById('classroom_id');
    const classroomOptions = Array.from(classroomSelect.options);

    function filterClassrooms() {
        const schoolId = schoolSelect.value;
        const selectedClassroomId = classroomSelect.value;

        classroomOptions.forEach(function (option) {
            if (!option.value) {
                option.hidden = false;
                return;
            }

            option.hidden = schoolId !== '' && option.dataset.schoolId !== schoolId;
        });

        const selectedOption = classroomSelect.options[classroomSelect.selectedIndex];

        if (
            selectedOption &&
            selectedOption.value &&
            schoolId !== '' &&
            selectedOption.dataset.schoolId !== schoolId
        ) {
            classroomSelect.value = '';
        }
    }

    schoolSelect.addEventListener('change', filterClassrooms);
    filterClassrooms();
});
</script>

@endsection