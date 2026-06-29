{{-- =========================================================
 Hero Section
========================================================= --}}

<section class="relative overflow-hidden bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-900 text-white">

    <div class="absolute inset-0 bg-black/20"></div>

    <div class="relative max-w-7xl mx-auto px-6 py-24 lg:py-32">

        <div class="grid lg:grid-cols-2 gap-12 items-center">

            {{-- Left --}}
            <div>

                <span
                    class="inline-flex items-center rounded-full bg-blue-500/20 px-4 py-1 text-sm font-semibold border border-blue-300/20">
                    🎓 PSPP Platform
                </span>

                <h1 class="mt-6 text-4xl lg:text-6xl font-extrabold leading-tight">

                    ระบบบริหารจัดการโรงเรียน<br>

                    <span class="text-yellow-300">
                        พระปริยัติธรรม
                    </span>

                    แผนกสามัญศึกษา

                </h1>

                <p class="mt-6 text-lg text-blue-100 leading-8">

                    ระบบสารสนเทศเพื่อการบริหารโรงเรียนพระปริยัติธรรม
                    รองรับการจัดการด้านวิชาการ บุคลากร งบประมาณ
                    การประกันคุณภาพ และงานบริหารทั่วไป
                    สำหรับโรงเรียนในกลุ่มจังหวัดแพร่

                </p>

                <div class="mt-10 flex flex-wrap gap-4">

                    @guest

                        <a href="{{ route('login') }}"
                            class="px-6 py-3 rounded-lg bg-white text-blue-800 font-semibold hover:bg-gray-100 transition">

                            เข้าสู่ระบบ

                        </a>

                        <a href="{{ route('register') }}"
                            class="px-6 py-3 rounded-lg border border-white hover:bg-white hover:text-blue-800 transition">

                            สมัครสมาชิก

                        </a>

                    @else

                        <a href="{{ route('dashboard') }}"
                            class="px-6 py-3 rounded-lg bg-yellow-400 text-gray-900 font-bold hover:bg-yellow-300 transition">

                            เข้าสู่ Dashboard

                        </a>

                    @endguest

                </div>

            </div>

            {{-- Right --}}
            <div class="hidden lg:flex justify-center">

                <div class="bg-white/10 backdrop-blur rounded-3xl p-10 shadow-2xl">

                    <div class="grid grid-cols-2 gap-6 text-center">

                        <div>

                            <h2 class="text-5xl font-bold text-yellow-300">
                                7
                            </h2>

                            <p class="mt-2 text-blue-100">
                                โรงเรียน
                            </p>

                        </div>

                        <div>

                            <h2 class="text-5xl font-bold text-yellow-300">
                                5
                            </h2>

                            <p class="mt-2 text-blue-100">
                                โมดูลหลัก
                            </p>

                        </div>

                        <div>

                            <h2 class="text-5xl font-bold text-yellow-300">
                                4
                            </h2>

                            <p class="mt-2 text-blue-100">
                                ระดับผู้ใช้งาน
                            </p>

                        </div>

                        <div>

                            <h2 class="text-5xl font-bold text-yellow-300">
                                100%
                            </h2>

                            <p class="mt-2 text-blue-100">
                                Web Based
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>