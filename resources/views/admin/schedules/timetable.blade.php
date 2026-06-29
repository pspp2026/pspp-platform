@extends('layouts.admin')

@section('content')

@if(session('success'))

<div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">
    {{ session('success') }}
</div>
@endif

@if(session('error'))

<div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
    {{ session('error') }}
</div>
@endif

@if($errors->any())

<div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
    <ul>
        @foreach($errors->all() as $error)
            <li>• {{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="flex justify-between items-start mb-6">


<div>

    <h1 class="text-3xl font-bold">
        ตารางสอน {{ $classroom->name }}
    </h1>

    <p class="text-gray-500">
        จัดการตารางเรียนของห้อง {{ $classroom->name }}
    </p>

    <form method="GET" class="mt-4">

        <select
            name="term"
            onchange="this.form.submit()"
            class="border rounded-lg px-4 py-2"
        >

            @foreach($academicTerms as $term)

                <option
                    value="{{ $term->id }}"
                    {{ $currentTermId == $term->id ? 'selected' : '' }}
                >
                    ปี {{ $term->academic_year }}
                    ภาคเรียน {{ $term->semester }}
                </option>

            @endforeach

        </select>

    </form>

</div>

<a
    href="{{ route('admin.schedules.index') }}"
    class="bg-gray-600 text-white px-4 py-2 rounded-lg"
>
    ← กลับ
</a>


</div>

@php

$days = [
'Monday',
'Tuesday',
'Wednesday',
'Thursday',
'Friday'
];

$dayNames = [
'Monday' => 'จันทร์',
'Tuesday' => 'อังคาร',
'Wednesday' => 'พุธ',
'Thursday' => 'พฤหัสบดี',
'Friday' => 'ศุกร์',
];

@endphp

<div class="overflow-x-auto bg-white rounded-xl shadow">

<table class="w-full border-collapse">


<thead>

    <tr class="bg-gray-100">

        <th class="border p-3 w-36">
            วัน / คาบ
        </th>

        @foreach($periods as $period)

            <th class="border p-3 text-center min-w-[180px]">

                <div class="font-bold">
                    {{ $period->name }}
                </div>

                <div class="text-xs text-gray-500">
                    {{ $period->start_time }}
                    -
                    {{ $period->end_time }}
                </div>

            </th>

            @if($period->name == 'คาบ 3')

                <th
                    class="border p-3 text-center
                           bg-orange-100 text-orange-700
                           min-w-[120px]"
                >
                    🍜 ฉันเพล
                </th>

            @endif

        @endforeach

    </tr>

</thead>

<tbody>

    @foreach($days as $day)

        <tr>

            <td class="border p-3 bg-gray-50 font-bold">
                {{ $dayNames[$day] }}
            </td>

            @foreach($periods as $period)

                @php

                    $item = $schedules
                        ->where('day_of_week', $day)
                        ->where('period_id', $period->id)
                        ->first();

                @endphp

                <td class="border p-3 align-top h-32">

                    @if($item)

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-2">

                            <div class="text-sm">

                                <span class="font-semibold text-blue-700">
                                    {{ $item->subject->subject_code ?? '' }}
                                </span>

                                <span class="font-bold">
                                    {{ $item->subject->subject_name ?? '-' }}
                                </span>

                            </div>

                            <div class="text-xs text-gray-500 mt-1">
                                {{ $item->subject->credits ?? 0 }}
                                หน่วยกิต
                            </div>

                            <div class="text-xs text-green-700 mt-1">
                                {{ $item->teacher->name ?? '-' }}
                            </div>

                            <div class="flex items-center gap-3 mt-3">

                              <a
                                    href="{{ route('admin.schedules.edit', $item) }}"
                                    class="text-blue-600 hover:text-blue-800 text-xs font-semibold"
                                >
                                    ✏️ แก้ไข
                                </a>

                                <form
                                    action="{{ route('admin.schedules.destroy', $item->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('ลบรายการนี้ ?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="text-red-600 hover:text-red-800 text-xs font-semibold"
                                    >
                                        ❌ ลบ
                                    </button>

                                </form>

                            </div>

                        </div>

                    @else

                        <button
                            onclick="
                                document.getElementById('classroom_id').value='{{ $classroom->id }}';
                                document.getElementById('period_id').value='{{ $period->id }}';
                                document.getElementById('day_of_week').value='{{ $day }}';

                                document.getElementById('scheduleModal').classList.remove('hidden');
                                document.getElementById('scheduleModal').classList.add('flex');
                            "
                            class="w-full h-full min-h-[100px]
                                   border-2 border-dashed border-gray-300
                                   rounded-lg hover:border-blue-500
                                   hover:bg-blue-50 transition"
                        >
                            <span class="text-blue-600">
                                + เพิ่มวิชา
                            </span>
                        </button>

                    @endif

                </td>

                @if($period->name == 'คาบ 3')

                    <td
                        class="border p-3 text-center
                               bg-orange-50
                               text-orange-700
                               font-bold"
                    >
                        🍜🍚🍛
                    </td>

                @endif

            @endforeach

        </tr>

    @endforeach

</tbody>


</table>



</div>

{{-- Modal เพิ่มตารางสอน --}}

<div
    id="scheduleModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50"
>


<div class="bg-white rounded-xl p-6 w-full max-w-lg">

    <h2
        id="modalTitle"
        class="text-xl font-bold mb-4">
        เพิ่มตารางสอน
    </h2>

    <form
        id="scheduleForm"
        action="{{ route('admin.schedules.store') }}"
        method="POST"
    >

        @csrf
        <input type="hidden" id="schedule_id" value="">
        <input type="hidden" name="classroom_id" id="classroom_id">
        <input type="hidden" name="period_id" id="period_id">
        <input type="hidden" name="day_of_week" id="day_of_week">

        <input type="hidden" name="academic_term_id" value="{{ $currentTermId }}">

        <div class="mb-4">

            <label class="block mb-2">
                วิชา
            </label>

            <select
                id="subject_id"
                name="subject_id"
                class="w-full border rounded-lg p-2"
                required
            >

                @foreach($subjects as $subject)

                    <option value="{{ $subject->id }}">
                        {{ $subject->subject_code }}
                        -
                        {{ $subject->subject_name }}
                    </option>

                @endforeach

            </select>

        </div>

        <div class="mb-4">

            <label class="block mb-2">
                ครูผู้สอน
            </label>

            <select
                id="teacher_id"
                name="teacher_id"
                class="w-full border rounded-lg p-2"
                required
            >

                @foreach($teachers as $teacher)

                    <option value="{{ $teacher->id }}">
                        {{ $teacher->name }}
                    </option>

                @endforeach

            </select>

        </div>

        <div class="flex justify-end gap-2">

            <button
                type="button"
                onclick="
                    document.getElementById('scheduleModal').classList.add('hidden');
                    document.getElementById('scheduleModal').classList.remove('flex');
                "
                class="px-4 py-2 bg-gray-300 rounded-lg"
            >
                ยกเลิก
            </button>

            <button
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg"
            >
                บันทึก
            </button>

        </div>

    </form>

</div>


</div>


@endsection