@extends('layouts.app')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-4">👨‍💼 พนักงาน</h1>

    <a href="{{ route('employees.create') }}"
       class="bg-blue-500 text-white px-4 py-2 rounded">
       + เพิ่มพนักงาน
    </a>

    <form method="GET" class="my-4">
        <input type="text" name="search"
               placeholder="ค้นหา..."
               class="border p-2 rounded w-1/3">
    </form>

    <table class="w-full border mt-4">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2">รหัส</th>
                <th>ชื่อ</th>
                <th>โรงเรียน</th>
                <th>ตำแหน่ง</th>
                <th>สถานะ</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
        @foreach($employees as $e)
            <tr class="border-t">
                <td class="p-2">{{ $e->employee_code }}</td>
                <td>{{ $e->name_th }}</td>
                <td>{{ $e->school->name ?? '-' }}</td>
                <td>{{ $e->position }}</td>
                <td>{{ $e->status }}</td>
                <td>
                    <a href="{{ route('employees.edit',$e) }}" class="text-blue-500">แก้ไข</a>
                    |
                    <form action="{{ route('employees.destroy',$e) }}"
                          method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('ลบ?')"
                                class="text-red-500">
                            ลบ
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $employees->links() }}
    </div>

</div>
@endsection