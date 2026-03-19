<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สมัครสมาชิก</title>

    <script src="https://cdn.tailwindcss.com"></script>

    {{-- ฟอนต์ราชการ --}}
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100" style="font-family: 'Sarabun', sans-serif;">

<div class="min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-lg rounded-xl w-full max-w-md p-8">

        {{-- 🔰 หัวข้อ --}}
        <h2 class="text-2xl font-bold text-center text-purple-700 mb-6">
            สมัครสมาชิก
        </h2>

        {{-- 🔴 error --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 📋 FORM --}}
        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            {{-- ชื่อ --}}
            <div class="mb-4">
                <label class="block mb-1 text-sm">ชื่อ</label>
                <input type="text" name="name"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    required>
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="block mb-1 text-sm">Email</label>
                <input type="email" name="email"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    required>
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label class="block mb-1 text-sm">รหัสผ่าน</label>
                <input type="password" name="password"
                    class="w-full border rounded-lg px-3 py-2"
                    required>
            </div>

            {{-- Confirm --}}
            <div class="mb-4">
                <label class="block mb-1 text-sm">ยืนยันรหัสผ่าน</label>
                <input type="password" name="password_confirmation"
                    class="w-full border rounded-lg px-3 py-2"
                    required>
            </div>

            {{-- รหัสโรงเรียน --}}
            <div class="mb-6">
                <label class="block mb-1 text-sm">รหัสโรงเรียน</label>
                <input type="text" name="school_code"
                    class="w-full border rounded-lg px-3 py-2"
                    placeholder="เช่น PSPP001"
                    required>
            </div>

            {{-- ปุ่ม --}}
            <button type="submit"
                class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition">
                สมัครสมาชิก
            </button>

        </form>

        {{-- 🔗 ลิงก์ --}}
        <p class="text-center text-sm mt-4">
            มีบัญชีแล้ว?
            <a href="/login" class="text-purple-600 hover:underline">
                เข้าสู่ระบบ
            </a>
        </p>

    </div>

</div>

</body>
</html>