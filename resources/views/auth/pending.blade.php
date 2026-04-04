<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>รอการอนุมัติ</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-xl rounded-2xl p-8 max-w-md w-full text-center">

        <!-- Icon -->
        <div class="mb-4">
            <div class="w-20 h-20 mx-auto flex items-center justify-center rounded-full bg-yellow-100 text-yellow-600 text-4xl">
                ⏳
            </div>
        </div>

        <!-- Title -->
        <h2 class="text-2xl font-bold text-gray-800 mb-2">
            รอการอนุมัติบัญชี
        </h2>

        <!-- Description -->
        <p class="text-gray-600 mb-6">
            บัญชีของคุณกำลังอยู่ระหว่างการตรวจสอบจากผู้ดูแลระบบ<br>
            กรุณารอสักครู่ แล้วลองเข้าสู่ระบบใหม่อีกครั้ง
        </p>

        <!-- Status Box -->
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-lg p-3 mb-6">
            สถานะ: <strong>รอการอนุมัติ</strong>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col gap-3">

            <!-- HOME -->
            <a href="/" 
               class="bg-blue-600 text-white py-2 rounded-lg shadow hover:bg-blue-700 transition">
                🏠 กลับหน้าแรก
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button 
                    class="w-full bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300 transition">
                    🚪 ออกจากระบบ
                </button>
            </form>

        </div>

    </div>

</body>
</html>