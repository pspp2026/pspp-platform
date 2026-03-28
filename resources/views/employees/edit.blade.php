@extends('layouts.app')

@section('content')
<div class="p-6 max-w-xl">

<h1 class="text-xl font-bold mb-4">แก้ไขพนักงาน</h1>

<form method="POST" action="{{ route('employees.update',$employee) }}">
@csrf
@method('PUT')

<input name="employee_code" value="{{ $employee->employee_code }}"
class="border p-2 w-full mb-2">

<input name="name_th" value="{{ $employee->name_th }}"
class="border p-2 w-full mb-2">

<select name="school_id" class="border p-2 w-full mb-2">
    @foreach($schools as $s)
        <option value="{{ $s->id }}"
            {{ $employee->school_id == $s->id ? 'selected' : '' }}>
            {{ $s->name }}
        </option>
    @endforeach
</select>

<button class="bg-blue-500 text-white px-4 py-2 rounded">
    อัปเดต
</button>

</form>

</div>
@endsection