<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>สมัครสมาชิก | PSPP Platform</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100" style="font-family: 'Sarabun', sans-serif;">

<div class="min-h-screen flex items-center justify-center px-4 py-8">

    <div class="bg-white shadow-lg rounded-xl w-full max-w-md p-8">

        <h2 class="text-2xl font-bold text-center text-purple-700 mb-6">
            สมัครสมาชิก
        </h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-1 text-sm">ชื่อ</label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500"
                    required>

                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 text-sm">Email</label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    required>

                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 text-sm">โรงเรียน</label>

                <select
                    name="school_id"
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500"
                    required>

                    <option value="" disabled {{ old('school_id') ? '' : 'selected' }}>
                        เลือกโรงเรียน
                    </option>

                    @isset($schools)
                        @foreach($schools as $s)
                            <option
                                value="{{ $s->id }}"
                                {{ old('school_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->school_name }}
                            </option>
                        @endforeach
                    @endisset

                </select>

                @error('school_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 text-sm">รหัสผ่าน</label>

                <div class="relative">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="w-full border rounded-lg px-3 py-2 pr-20 focus:outline-none focus:ring-2 focus:ring-purple-500"
                        required>

                    <button
                        type="button"
                        onclick="togglePassword('password', this)"
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-sm text-purple-600 hover:text-purple-800 font-medium">
                        แสดง
                    </button>
                </div>

                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block mb-1 text-sm">ยืนยันรหัสผ่าน</label>

                <div class="relative">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="w-full border rounded-lg px-3 py-2 pr-20 focus:outline-none focus:ring-2 focus:ring-purple-500"
                        required>

                    <button
                        type="button"
                        onclick="togglePassword('password_confirmation', this)"
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-sm text-purple-600 hover:text-purple-800 font-medium">
                        แสดง
                    </button>
                </div>
            </div>

            <button
                type="submit"
                class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition">
                สมัครสมาชิก
            </button>
        </form>

        <p class="text-center text-sm mt-4">
            มีบัญชีแล้ว?
            <a href="{{ route('login') }}" class="text-purple-600 hover:underline">
                เข้าสู่ระบบ
            </a>
        </p>

    </div>

</div>

<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);

        if (input.type === 'password') {
            input.type = 'text';
            button.textContent = 'ซ่อน';
        } else {
            input.type = 'password';
            button.textContent = 'แสดง';
        }
    }
</script>

</body>
</html>