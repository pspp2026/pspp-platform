<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>Director Dashboard</title>
</head>
<body>

<h1>Director Dashboard</h1>
<p>ยินดีต้อนรับผู้อำนวยการ</p>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">ออกจากระบบ</button>
</form>

</body>
</html>