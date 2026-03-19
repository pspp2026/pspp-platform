<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สำนักเขต ปส.เขต ๖</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
</head>

<body class="bg-gray-100">

{{-- ✅ HEADER --}}
<div class="bg-purple-800 text-white">
    <div class="max-w-7xl mx-auto flex items-center justify-between p-4">

        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logoBitpps.png') }}" 
                 class="h-16 w-16 object-contain">

            <div class="leading-tight">
                <h1 class="text-lg font-bold">
                    โรงเรียนพระปริยัติธรรม แผนกสามัญศึกษา กลุ่มจังหวัดแพร่
                </h1>
                <p class="text-xs">
                    สำนักเขตการศึกษาพระปริยัติธรรม แผนกสามัญศึกษา เขต ๖
                </p>
            </div>
        </div>

    </div>

    <div class="bg-purple-700">
        <div class="max-w-7xl mx-auto flex space-x-6 text-sm p-3">
            <a href="/" class="hover:underline">หน้าหลัก</a>
            <a href="#">เกี่ยวกับเรา</a>
            <a href="#">ข่าวประชาสัมพันธ์</a>
            <a href="#">คำสั่ง/ประกาศ</a>
            <a href="#">แผนงาน-งบประมาณ</a>
            <a href="#">บริการ</a>
            <a href="#">ติดต่อเรา</a>
        </div>
    </div>
</div>

{{-- ✅ CONTENT --}}
<div class="max-w-7xl mx-auto mt-6 grid grid-cols-12 gap-6">

    {{-- LEFT --}}
    <div class="col-span-3">
        <div class="bg-white border rounded shadow">
            <div class="bg-gray-200 p-2 font-bold">เมนู</div>
            <ul class="p-3 space-y-2 text-sm">
                <li><a href="#">ระบบสมัครครู</a></li>
                <li><a href="#">ระบบรายงาน</a></li>
                <li><a href="#">ระบบนักเรียน</a></li>
            </ul>
        </div>
    </div>

    {{-- CENTER --}}
    <div class="col-span-6 space-y-6">

        {{-- โรงเรียน --}}
        <div class="bg-white border rounded shadow">
            <div class="bg-gray-200 p-2 font-bold">โรงเรียนในเครือ</div>
            <div class="p-4 grid grid-cols-2 gap-3 text-sm">
                <a href="#" class="border p-2">PSPP01</a>
                <a href="#" class="border p-2">PSPP02</a>
                <a href="#" class="border p-2">PSPP03</a>
                <a href="#" class="border p-2">PSPP04</a>
                <a href="#" class="border p-2">PSPP05</a>
                <a href="#" class="border p-2">PSPP06</a>
                <a href="#" class="border p-2">PSPP07</a>
            </div>
        </div>
{{-- 🎞 SLIDER --}}
<div class="max-w-7xl mx-auto mt-4">
    <div class="relative overflow-hidden rounded shadow">

        <div id="slider" class="flex transition-transform duration-700">

            <img src="{{ asset('images/banner1.jpg') }}" class="w-full h-64 object-cover">
            <img src="{{ asset('images/banner2.jpg') }}" class="w-full h-64 object-cover">
            <img src="{{ asset('images/banner3.jpg') }}" class="w-full h-64 object-cover">

        </div>

        <!-- ปุ่ม -->
        <button onclick="prevSlide()" 
            class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 text-white px-3 py-1 rounded">
            ‹
        </button>

        <button onclick="nextSlide()" 
            class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 text-white px-3 py-1 rounded">
            ›
        </button>

    </div>
</div>
        {{-- ข่าว --}}
        <div class="bg-white border rounded shadow">
            <div class="bg-gray-200 p-2 font-bold">ข่าวประชาสัมพันธ์</div>
            <div class="p-4 space-y-3 text-sm">
                <div class="border-b pb-2">
                    <p class="font-semibold">ประชุมผู้บริหาร</p>
                    <p class="text-gray-500">วันที่ 10 มีนาคม 2569</p>
                </div>
            </div>
        </div>

        {{-- 🔥 ปฏิทิน --}}
        <div class="bg-white border rounded shadow">
            <div class="bg-gray-200 p-2 font-bold">📅 ปฏิทินกิจกรรม</div>
            <div class="p-4">
                <div id="calendar"></div>
            </div>
        </div>

    </div>
 {{-- RIGHT --}}
    <div class="col-span-3 space-y-6">

        {{-- 🔗 หน่วยงาน --}}
        <div class="bg-white border rounded shadow">
            <div class="bg-gray-200 p-2 font-bold">หน่วยงานที่เกี่ยวข้อง</div>
            <div class="p-3 space-y-2 text-sm">

                <a href="https://www.moe.go.th/" 
                target="_blank"
                rel="noopener noreferrer" 
                class="block">กระทรวงศึกษาธิการ</a>

                <a href="https://www.ogbe.go.th/th/index.php" 
                target="_blank" 
                rel="noopener noreferrer"
                class="block">
                สศป.แผนกสามัญ.
                </a>

                <a href="https://deb.onab.go.th/th/page/item/index/id/13" 
                target="_blank" 
                rel="noopener noreferrer"
                class="block">
                กองพุทธศาสนศึกษา
                </a>

            </div>
        </div>

        {{-- 🔐 LOGIN --}}
        <div class="bg-white border rounded shadow p-4 text-sm">
            @guest
                <a href="/login" 
                target="_blank" 
                rel="noopener noreferrer"
                class="block bg-purple-600 text-white text-center py-2 rounded mb-2">
                    เข้าสู่ระบบ
                </a>
                <a href="/register" 
                target="_blank" 
                rel="noopener noreferrer"
                class="block bg-blue-600 text-white text-center py-2 rounded mb-2">
                    สมัครสมาชิก
                </a>
            @endguest

            @auth
                <p>👤 {{ auth()->user()->name }}</p>

                @if(auth()->user()->status == 'pending')
                    <p class="text-yellow-600">รออนุมัติ</p>
                @else
                    <p class="text-green-600">เข้าใช้งานได้</p>
                @endif
            @endauth
        </div>

    </div>

</div>
{{-- FOOTER --}}
<div class="bg-purple-800 text-white text-center mt-10 p-4 text-sm">
    © สำนักเขตการศึกษาพระปริยัติธรรม เขต ๖
</div>

{{-- 🔥 SCRIPT สไลด์ --}}
<script>
let currentIndex = 0;
const slider = document.getElementById('slider');
const totalSlides = slider.children.length;

function showSlide(index) {
    if (index >= totalSlides) currentIndex = 0;
    else if (index < 0) currentIndex = totalSlides - 1;
    else currentIndex = index;

    slider.style.transform = `translateX(-${currentIndex * 100}%)`;
}

function nextSlide() {
    showSlide(currentIndex + 1);
}

function prevSlide() {
    showSlide(currentIndex - 1);
}

// auto slide
setInterval(() => {
    nextSlide();
}, 4000);
</script>

{{-- 🔥 SCRIPT ปฏิทิน --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 500,

        events: [
            @foreach(\App\Models\Event::all() as $event)
            {
                title: "{{ $event->title }}",
                start: "{{ $event->event_date }}"
            },
            @endforeach
        ],

        eventClick: function(info) {
            alert(info.event.title);
        }
    });

    calendar.render();
});
</script>

</body>
</html>