{{-- =========================================================
 Call To Action Section
========================================================= --}}

<section id="cta" class="py-20 bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-900">

    <div class="max-w-5xl mx-auto px-6 text-center">

        <span
            class="inline-flex items-center px-4 py-1 rounded-full bg-white/20 text-white text-sm font-semibold">
            🚀 เริ่มต้นใช้งาน PSPP Platform
        </span>

        <h2 class="mt-6 text-4xl lg:text-5xl font-extrabold text-white">
            พร้อมยกระดับการบริหารโรงเรียนของคุณแล้วหรือยัง?
        </h2>

        <p class="mt-6 text-lg text-blue-100 leading-8 max-w-3xl mx-auto">
            PSPP Platform ช่วยให้การบริหารจัดการโรงเรียนพระปริยัติธรรม
            เป็นเรื่องง่าย รวดเร็ว และเป็นระบบ
            รองรับผู้บริหาร ครู บุคลากร และนักเรียน
            ภายในแพลตฟอร์มเดียว
        </p>

        <div class="mt-10 flex flex-wrap justify-center gap-4">

            @guest

                <a href="{{ route('login') }}"
                    class="px-8 py-4 rounded-xl bg-white text-blue-700 font-bold shadow-lg hover:bg-gray-100 transition">

                    🔐 เข้าสู่ระบบ

                </a>

                <a href="{{ route('register') }}"
                    class="px-8 py-4 rounded-xl border-2 border-white text-white font-bold hover:bg-white hover:text-blue-700 transition">

                    📝 สมัครสมาชิก

                </a>

            @else

                <a href="{{ route('dashboard') }}"
                    class="px-8 py-4 rounded-xl bg-yellow-400 text-gray-900 font-bold shadow-lg hover:bg-yellow-300 transition">

                    📊 ไปยัง Dashboard

                </a>

            @endguest

        </div>

        <div class="mt-12 grid md:grid-cols-3 gap-6">

            <div class="bg-white/10 backdrop-blur rounded-xl p-6">

                <div class="text-4xl mb-3">
                    🏫
                </div>

                <h3 class="text-xl font-bold text-white">
                    7 โรงเรียน
                </h3>

                <p class="mt-2 text-blue-100">
                    รองรับการบริหารหลายโรงเรียน
                </p>

            </div>

            <div class="bg-white/10 backdrop-blur rounded-xl p-6">

                <div class="text-4xl mb-3">
                    📘
                </div>

                <h3 class="text-xl font-bold text-white">
                    5 โมดูลหลัก
                </h3>

                <p class="mt-2 text-blue-100">
                    ครอบคลุมการบริหารทุกด้าน
                </p>

            </div>

            <div class="bg-white/10 backdrop-blur rounded-xl p-6">

                <div class="text-4xl mb-3">
                    ☁️
                </div>

                <h3 class="text-xl font-bold text-white">
                    Cloud Ready
                </h3>

                <p class="mt-2 text-blue-100">
                    ใช้งานได้ทุกที่ ทุกอุปกรณ์
                </p>

            </div>

        </div>

    </div>

</section>