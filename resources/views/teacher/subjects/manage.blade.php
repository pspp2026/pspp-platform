@extends('layouts.teacher')

@section('teacher-content')

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-5">
        <h1 class="text-2xl font-bold">
            📚 จัดการรายวิชาที่สอน
        </h1>

        <span class="text-sm text-gray-500">
            เลือกรายวิชาที่ครูรับผิดชอบ
        </span>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-700 p-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- SEARCH --}}
    <input
        id="searchSubject"
        type="text"
        placeholder="🔍 ค้นหารหัสวิชา หรือชื่อวิชา..."
        class="w-full border rounded-lg p-3 mb-5"
    >

    <form method="POST"
          action="{{ route('teacher.subjects.manage.update') }}">

        @csrf

        <div class="space-y-6">

            @foreach($subjects as $groupName => $items)

                <div class="subject-group border rounded-xl overflow-hidden">

                    <div class="bg-blue-50 px-5 py-3 border-b">
                        <h2 class="font-bold text-blue-700">
                            📘 {{ $groupName }}
                        </h2>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">

                        @foreach($items as $subject)

                            <label class="subject-item border rounded-lg p-4 hover:bg-gray-50 flex gap-3 cursor-pointer transition">

                                <input
                                    type="checkbox"
                                    name="subjects[]"
                                    value="{{ $subject->id }}"
                                    class="mt-1"
                                    {{ in_array($subject->id, $selectedSubjects) ? 'checked' : '' }}
                                >

                                <div class="flex-1">

                                    <div class="font-bold text-blue-600 text-lg">
                                        {{ $subject->subject_code }}
                                    </div>

                                    <div class="text-gray-800">
                                        {{ $subject->subject_name }}
                                    </div>

                                    <div class="mt-3 flex flex-wrap gap-2">

                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">
                                            🎓
                                            {{ trim(($subject->level ?? '') . ' ' . ($subject->class ?? '')) }}
                                        </span>

                                        @if($subject->credits)
                                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">
                                                📚 {{ $subject->credits }} หน่วยกิต
                                            </span>
                                        @endif

                                        @if($subject->hours)
                                            <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded text-xs">
                                                ⏰ {{ $subject->hours }} ชั่วโมง
                                            </span>
                                        @endif

                                        @if($subject->semester)
                                            <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs">
                                                📖 ภาคเรียน {{ $subject->semester }}
                                            </span>
                                        @endif

                                        @if($subject->class)
                                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
                                                🚀 ม.{{ $subject->class }}
                                            </span>
                                        @endif

                                    </div>

                                </div>

                            </label>

                        @endforeach

                    </div>

                </div>

            @endforeach

        </div>

        <div class="mt-6 flex justify-end">

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

                💾 บันทึกข้อมูล

            </button>

        </div>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

document.getElementById('searchSubject').addEventListener('keyup', function () {

    let keyword = this.value.toLowerCase();

    document.querySelectorAll('.subject-item').forEach(function (item) {

        item.style.display = item.innerText.toLowerCase().includes(keyword)
            ? ''
            : 'none';

    });

});

document.querySelectorAll('input[name="subjects[]"]').forEach(function (cb) {

    cb.addEventListener('change', function () {

        if (this.checked) {

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'เลือกวิชาแล้ว',
                showConfirmButton: false,
                timer: 1500
            });

        }

    });

});

</script>

@endsection