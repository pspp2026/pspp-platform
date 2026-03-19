<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
</head>
<body>

<h1>Admin Dashboard</h1>
<p>ยินดีต้อนรับผู้ดูแลระบบ</p>

<ul>
    <li>
        <a href="{{ route('admin.users.pending') }}">
            อนุมัติผู้ใช้
        </a>
    </li>
</ul>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">ออกจากระบบ</button>
</form>

</body>
</html>