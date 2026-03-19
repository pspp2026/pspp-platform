<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>Staff Dashboard</title>
</head>
<body>

<h1>Staff Dashboard</h1>
<p>ยินดีต้อนรับเจ้าหน้าที่</p>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">ออกจากระบบ</button>
</form>

</body>
</html>