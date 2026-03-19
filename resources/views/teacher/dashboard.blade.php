<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>Teacher Dashboard</title>
</head>
<body>

<h1>Teacher Dashboard</h1>
<p>ยินดีต้อนรับคุณครู</p>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">ออกจากระบบ</button>
</form>

</body>
</html>