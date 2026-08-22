{{-- =========================================================
 Footer Section
========================================================= --}}

<footer id="contact" class="bg-slate-900 text-gray-300">

    <div class="max-w-7xl mx-auto px-6 py-16">

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-10">

            {{-- PSPP --}}
            <div>

                <h3 class="text-2xl font-bold text-white mb-4">
                    PSPP Platform
                </h3>

                <p class="leading-7">
                    ระบบบริหารจัดการโรงเรียนพระปริยัติธรรม
                    แผนกสามัญศึกษา
                    กลุ่มจังหวัดแพร่
                </p>

                <div class="mt-6">

                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full bg-green-600 text-white text-sm">

                        ● Online

                    </span>

                </div>

            </div>
         
            {{-- Modules --}}
            <div>

                <h4 class="text-lg font-semibold text-white mb-4">
                    โมดูลหลัก
                </h4>

                <ul class="space-y-2">

                    <li>📘 Academic</li>

                    <li>👥 Human Resource</li>

                    <li>💰 Budget</li>

                    <li>🏢 General Administration</li>

                    <li>📋 Quality Assurance</li>

                </ul>

            </div>

            {{-- Users --}}
            <div>

                <h4 class="text-lg font-semibold text-white mb-4">
                    ผู้ใช้งาน
                </h4>

                <ul class="space-y-2">

                    <li>👑 Super Admin</li>

                    <li>👨‍💻 School Admin</li>

                    <li>👨‍🏫 Teacher</li>

                    <li>👨‍🎓 Student</li>

                    <li>👨‍💼 Staff</li>

                </ul>

            </div>

            {{-- Contact --}}
            <div>

                <h4 class="text-lg font-semibold text-white mb-4">
                    ติดต่อ
                </h4>

                <ul class="space-y-3">

                    <li>
                        🌐 pspp.online
                    </li>

                    <li>
                        📧 admin@pspp.online
                    </li>

                    <li>
                        🇹🇭 ประเทศไทย
                    </li>

                    <li>
                        สำนักงานเขตโรงเรียนพระปริยัติธรรม แผนกสามัญศึกษา เขต 6
                    </li>

                </ul>

            </div>

        </div>

        {{-- =========================================================
        Website Statistics
        ========================================================= --}}

        <div class="border-t border-slate-700 mt-10 pt-8">

            <h3 class="font-bold text-xl text-white mb-5">
                🌐 สถิติการใช้งานเว็บไซต์
            </h3>

            <ul class="space-y-3 text-gray-300">

                <li class="flex items-center gap-2">

                    <span>🌍</span>

                    <span>ผู้เข้าชมทั้งหมด</span>

                    <span class="font-bold text-cyan-300 text-sm">
                        {{ number_format($statistics['total_visitors']) }}
                    </span>

                </li>

                <li class="flex items-center gap-2">

                    <span>📅</span>

                    <span>ผู้เข้าชมวันนี้</span>

                    <span class="font-bold text-green-300 text-sm">
                        {{ number_format($statistics['today_visitors']) }}
                    </span>

                </li>

                <li class="flex items-center gap-2">

                    <span>🟢</span>

                    <span>ออนไลน์ขณะนี้</span>

                    <span class="font-bold text-yellow-300 text-sm">
                        {{ number_format($statistics['online_users']) }}
                    </span>

                </li>

            </ul>

        </div>

        {{-- Divider --}}
        <div class="border-t border-slate-700 my-10"></div>

        {{-- Statistics --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 text-center mb-10">

            <div>

                <h3 class="text-3xl font-bold text-white">
                    7
                </h3>

                <p class="text-sm text-gray-400 mt-2">
                    Schools
                </p>

            </div>

            <div>

                <h3 class="text-3xl font-bold text-white">
                    5
                </h3>

                <p class="text-sm text-gray-400 mt-2">
                    Core Modules
                </p>

            </div>

            <div>

                <h3 class="text-3xl font-bold text-white">
                    4
                </h3>

                <p class="text-sm text-gray-400 mt-2">
                    User Roles
                </p>

            </div>

            <div>

                <h3 class="text-3xl font-bold text-white">
                    100%
                </h3>

                <p class="text-sm text-gray-400 mt-2">
                    Web Based
                </p>

            </div>

        </div>

        {{-- Copyright --}}
        <div
            class="border-t border-slate-700 pt-6 flex flex-col md:flex-row justify-between items-center">

            <div class="text-sm text-gray-400">

                © {{ date('Y') }} PSPP Platform

                <span class="mx-2">|</span>

                All Rights Reserved.

            </div>

            <div class="mt-4 md:mt-0 text-sm text-gray-500">

                Powered by  สำนักเขตการศึกษาพระปริยัติธรรม แผนกสามัญศึกษา เขต 6

            </div>

        </div>

    </div>

</footer>