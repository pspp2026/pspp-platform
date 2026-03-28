<aside class="w-64 bg-blue-900 text-white p-5 space-y-4">

    <h2 class="text-xl font-bold mb-4">TEACHER PANEL</h2>

    <nav class="space-y-2 text-sm">

        <a href="{{ route('teacher.dashboard') }}"
           class="block px-3 py-2 rounded hover:bg-blue-700">
            🏠 หน้าแรก
        </a>

        <a href="#"
           class="block px-3 py-2 rounded hover:bg-blue-700">
            📘 รายวิชาของฉัน
        </a>

        <a href="#"
           class="block px-3 py-2 rounded hover:bg-blue-700">
            👨‍🎓 นักเรียน
        </a>

        <a href="#"
           class="block px-3 py-2 rounded hover:bg-blue-700">
            📝 บันทึกคะแนน
        </a>

        <a href="#"
           class="block px-3 py-2 rounded hover:bg-blue-700">
            📅 ตารางสอน
        </a>

        <a href="#"
           class="block px-3 py-2 rounded hover:bg-blue-700">
            📊 รายงานผล
        </a>

        <a href="{{ route('teacher.profile') }}"
           class="block px-3 py-2 rounded hover:bg-blue-700">
            👤 โปรไฟล์
        </a>

    </nav>

</aside>