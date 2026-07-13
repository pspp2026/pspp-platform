{{-- =========================================================
Statistics Section
========================================================= --}}

<section id="statistics" class="bg-white py-16">

<div class="max-w-7xl mx-auto px-6">

    <div class="text-center mb-12">

        <span
            class="inline-flex items-center px-4 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">
            สถิติของระบบ
        </span>

        <h2 class="mt-4 text-4xl font-bold text-gray-800">
            📊 สถิติระบบ
        </h2>

        <p class="mt-4 max-w-3xl mx-auto text-gray-600">
            ข้อมูลภาพรวมของระบบบริหารโรงเรียนพระปริยัติธรรม
            แผนกสามัญศึกษา กลุ่มจังหวัดแพร่
        </p>

    </div>

    {{-- =========================================================
        Statistics Cards
    ========================================================== --}}

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">

        {{-- โรงเรียน --}}
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-2xl p-6 text-center shadow-lg">

            <div class="text-5xl mb-3">🏫</div>

            <div class="text-4xl font-bold">
                {{ number_format($statistics['schools']) }}
            </div>

            <div class="mt-2 text-blue-100">
                โรงเรียนในระบบ
            </div>

        </div>

        {{-- ผู้บริหาร --}}
        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 text-white rounded-2xl p-6 text-center shadow-lg">

            <div class="text-5xl mb-3">👨‍💼</div>

            <div class="text-4xl font-bold">
                {{ number_format($statistics['directors']) }}
            </div>

            <div class="mt-2 text-indigo-100">
                ผู้บริหารโรงเรียน
            </div>

        </div>

        {{-- ครู --}}
        <div class="bg-gradient-to-br from-green-600 to-emerald-600 text-white rounded-2xl p-6 text-center shadow-lg">

            <div class="text-5xl mb-3">👨‍🏫</div>

            <div class="text-4xl font-bold">
                {{ number_format($statistics['teachers']) }}
            </div>

            <div class="mt-2 text-green-100">
                ครูผู้สอน
            </div>

        </div>

        {{-- นักเรียน --}}
        <div class="bg-gradient-to-br from-yellow-500 to-orange-500 text-white rounded-2xl p-6 text-center shadow-lg">

            <div class="text-5xl mb-3">👨‍🎓</div>

            <div class="text-4xl font-bold">
                {{ number_format($statistics['students']) }}
            </div>

            <div class="mt-2 text-yellow-100">
                นักเรียน
            </div>

        </div>

        {{-- เจ้าหน้าที่ --}}
        <div class="bg-gradient-to-br from-pink-600 to-red-600 text-white rounded-2xl p-6 text-center shadow-lg">

            <div class="text-5xl mb-3">👥</div>

            <div class="text-4xl font-bold">
                {{ number_format($statistics['staffs']) }}
            </div>

            <div class="mt-2 text-pink-100">
                เจ้าหน้าที่
            </div>

        </div>

    </div>

    {{-- =========================================================
        Platform Info
    ========================================================== --}}

    <div class="mt-12 rounded-2xl bg-slate-900 text-white p-8">

        <div class="grid md:grid-cols-3 gap-8 text-center">

            <div>

                <h3 class="text-3xl font-bold">
                    Multi School
                </h3>

                <p class="mt-2 text-gray-300">
                    รองรับการบริหารหลายโรงเรียนภายในระบบเดียว
                </p>

            </div>

            <div>

                <h3 class="text-3xl font-bold">
                    Web Based
                </h3>

                <p class="mt-2 text-gray-300">
                    ใช้งานได้ทุกที่ ทุกเวลา ผ่านเว็บเบราว์เซอร์
                </p>

            </div>

            <div>

                <h3 class="text-3xl font-bold">
                    Laravel 12
                </h3>

                <p class="mt-2 text-gray-300">
                    พัฒนาด้วย Laravel, Tailwind CSS และ MySQL
                </p>

            </div>

        </div>

    </div>

</div>

</section>