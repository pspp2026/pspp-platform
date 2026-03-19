<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>อนุมัติผู้ใช้</title>
</head>
<body>

<h2>รายชื่อผู้ใช้รอการอนุมัติ</h2>

{{-- success message --}}
@if (session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

{{-- validation error --}}
@if ($errors->any())
    <p style="color:red">{{ $errors->first() }}</p>
@endif

@if ($users->isEmpty())
    <p>ไม่มีผู้ใช้ที่รออนุมัติ</p>
@endif

@foreach ($users as $user)

    <form
        method="POST"
        action="{{ route('admin.users.approve', ['user' => $user->id]) }}"
        style="margin-bottom:20px;"
    >
        @csrf
        {{-- ไม่จำเป็นต้อง @method เพราะใช้ POST อยู่แล้ว --}}

        <p>
            <strong>{{ $user->name }}</strong><br>
            {{ $user->email }}<br>
            โรงเรียน: {{ $user->school_code }}
        </p>

        <label for="role_{{ $user->id }}">กำหนดบทบาท (Role)</label><br>

        <select name="role" id="role_{{ $user->id }}" required>
            <option value="">-- เลือก role --</option>
            <option value="teacher">ครู</option>
            <option value="student">นักเรียน</option>
            <option value="staff">เจ้าหน้าที่</option>
            <option value="director">ผู้อำนวยการ</option>
        </select>

        <br><br>
        <button type="submit">อนุมัติ</button>
    </form>

    <hr>

@endforeach

</body>
</html>