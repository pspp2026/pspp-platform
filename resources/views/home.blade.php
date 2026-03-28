<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สำนักเขต ปส.เขต ๖</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
</head>

<body class="bg-gray-100">

<!-- 🔷 HEADER -->
<div class="bg-purple-800 text-white">
    <div class="max-w-7xl mx-auto flex items-center p-4">

        <img src="{{ asset('images/logoBitpps.png') }}" class="h-16 w-16 object-contain">

        <div class="ml-3">
            <h1 class="text-lg font-bold">
                โรงเรียนพระปริยัติธรรม แผนกสามัญศึกษา กลุ่มจังหวัดแพร่
            </h1>
            <p class="text-xs">
                สำนักเขตการศึกษาพระปริยัติธรรม เขต ๖
            </p>
        </div>

    </div>

    <div class="bg-purple-700">
        <div class="max-w-7xl mx-auto flex space-x-6 text-sm p-3">
            <a href="/">หน้าหลัก</a>
            <a href="#">เกี่ยวกับเรา</a>
            <a href="#">ข่าวประชาสัมพันธ์</a>
            <a href="#">คำสั่ง/ประกาศ</a>
            <a href="#">แผนงาน-งบประมาณ</a>
            <a href="#">บริการวิชาการ</a>
            <a href="#">ติดต่อเรา</a>
        </div>
    </div>
</div>

<!-- 🔶 MAIN -->
<div class="flex max-w-7xl mx-auto mt-6 gap-6">

    <!-- 🔵 LEFT -->
    <aside class="w-64 bg-purple-900 text-white p-4 rounded shadow">

        <h2 class="font-bold mb-4">PSPP SYSTEM</h2>

        <nav class="space-y-2 text-sm">
            <a href="/" class="block px-3 py-2 hover:bg-purple-700 rounded">🏠 หน้าแรก</a>
            <a href="#" class="block px-3 py-2 hover:bg-purple-700 rounded">📘 รายวิชา</a>
            <a href="#" class="block px-3 py-2 hover:bg-purple-700 rounded">👨‍🎓 นักเรียน</a>
        </nav>

    </aside>

    <!-- 🟡 CENTER -->
    <div class="flex-1 space-y-6">

        <!-- SLIDER -->
        <div class="bg-white p-3 rounded shadow">
            <div class="relative overflow-hidden rounded">

                <div id="slider" class="flex transition-transform duration-700">
                    <img src="{{ asset('images/banner1.jpg') }}" class="w-full h-64 object-cover">
                    <img src="{{ asset('images/banner2.jpg') }}" class="w-full h-64 object-cover">
                    <img src="{{ asset('images/banner3.jpg') }}" class="w-full h-64 object-cover">
                </div>

                <button onclick="prevSlide()" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 text-white px-2">‹</button>
                <button onclick="nextSlide()" class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 text-white px-2">›</button>
            </div>
        </div>

        <!-- ข่าว -->
        <div class="bg-white p-4 rounded shadow">
            <h2 class="font-bold mb-2">ข่าวประชาสัมพันธ์</h2>
            <p>เนื้อหาข่าว...</p>
        </div>

        <!-- ปฏิทิน -->
        <div class="bg-white p-4 rounded shadow">
            <div id="calendar"></div>
        </div>

    </div>

    <!-- 🔴 RIGHT -->
    <div class="w-72 space-y-6">

        <!-- LOGIN -->
        <div class="bg-white p-4 rounded shadow text-sm">

              @guest

        <a href="{{ route('login') }}"
           class="block w-full text-center bg-purple-600 text-white py-2 rounded mb-3 hover:bg-purple-700">
            เข้าสู่ระบบ
        </a>

        <a href="{{ route('register') }}"
           class="block w-full text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            สมัครสมาชิก
        </a>

    @endguest


    {{-- ✅ login แล้ว --}}
    @auth
        <div class="space-y-2">

            <p class="font-semibold">
                👤 {{ auth()->user()->name }}
            </p>

            <p class="text-green-600 text-sm flex items-center gap-2">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                เข้าใช้งานได้
            </p>

            <a href="{{ url('/dashboard') }}"
               class="block w-full text-center bg-green-600 text-white py-2 rounded hover:bg-green-700">
                ไปที่ Dashboard
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
                    Logout
                </button>
            </form>

        </div>
    @endauth

        </div>

        <!-- โรงเรียน -->
        <div class="bg-white p-4 rounded shadow text-sm">
            <h2 class="font-bold mb-2">รายชื่อโรงเรียน</h2>

            @foreach($schools as $s)
                <div class="border-b py-1">
                    {{ $s->school_name }}
                </div>
            @endforeach
        </div>

    </div>

</div>

<!-- FOOTER -->
<div class="bg-purple-800 text-white text-center mt-10 p-4 text-sm">
    © สำนักเขตการศึกษาพระปริยัติธรรม แผนกสามัญศึกษา เขต ๖
</div>

<!-- SCRIPT -->
<script>
let currentIndex = 0;
const slider = document.getElementById('slider');

function showSlide(index) {
    const total = slider.children.length;
    currentIndex = (index + total) % total;
    slider.style.transform = `translateX(-${currentIndex * 100}%)`;
}
function nextSlide(){ showSlide(currentIndex+1); }
function prevSlide(){ showSlide(currentIndex-1); }
setInterval(nextSlide, 4000);
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        height: 400
    });
    calendar.render();
});
</script>

</body>
</html>