<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>Student Home</title>
</head>
<body>

<h1>Student Home</h1>
<p>ยินดีต้อนรับนักเรียน</p>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">ออกจากระบบ</button>
</form>

</body>
</html>