@extends('layouts.app')

@section('content')
<div class="p-6 max-w-xl">

<h1 class="text-xl font-bold mb-4">เพิ่มพนักงาน</h1>

<form method="POST" action="{{ route('employees.store') }}">
@csrf

<input name="employee_code" placeholder="รหัส"
class="border p-2 w-full mb-2">

<input name="name_th" placeholder="ชื่อ"
class="border p-2 w-full mb-2">

<select name="school_id" class="border p-2 w-full mb-2">
    @foreach($schools as $s)
        <option value="{{ $s->id }}">{{ $s->name }}</option>
    @endforeach
</select>

<input name="position" placeholder="ตำแหน่ง"
class="border p-2 w-full mb-2">

<button class="bg-green-500 text-white px-4 py-2 rounded">
    บันทึก
</button>

</form>

</div>
@endsection