@extends('layouts.admin')

@section('content')

<div class="w-full">

   <div class="flex justify-between items-center mb-6">

    <div>
        <h1 class="text-2xl font-bold">
            🏫 ห้องเรียน
        </h1>

        <p class="text-gray-500 text-sm">
            ภาคเรียน {{ $currentTerm->semester }}
            ปีการศึกษา {{ $currentTerm->academic_year }}
        </p>
    </div>

    <a href="{{ route('admin.classrooms.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded">
        ➕ เพิ่มห้องเรียน
    </a>

</div>

    <div class="bg-white rounded-xl shadow">

        <table class="w-full">

            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3">โรงเรียน</th>
                    <th class="p-3">ห้องเรียน</th>
                    <th class="p-3">ระดับชั้น</th>
                    <th class="p-3">นักเรียน</th>
                    <th class="p-3">จัดการ</th>
                </tr>
            </thead>

            <tbody>

                @foreach($classrooms as $classroom)

                <tr class="border-t">

                    <td class="p-3">
                        {{ $classroom->school->school_name ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ $classroom->name }}
                    </td>

                    <td class="p-3">
                        {{ $classroom->level }}
                    </td>

                    <td class="p-3">
                        {{ $classroom->student_count }}
                    </td>

                    <td class="p-3">

                        <a href="{{ route('admin.classrooms.edit',$classroom) }}"
                           class="text-blue-600">
                            แก้ไข
                        </a>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection