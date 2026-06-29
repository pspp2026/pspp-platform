<div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-600 via-emerald-500 to-teal-500 shadow-xl">

    {{-- Background Decoration --}}
    <div class="absolute -top-10 -right-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-10 -left-10 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>

    <div class="relative px-8 py-8 lg:px-10 lg:py-10">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">

            {{-- Left --}}
            <div>

                <p class="text-emerald-100 text-sm font-medium">
                    {{ $hero['greeting'] }}
                </p>

                <h1 class="mt-2 text-3xl lg:text-4xl font-bold text-white">
                    {{ $hero['name'] }}
                </h1>

                <p class="mt-3 text-emerald-100 leading-relaxed max-w-2xl">
                    ยินดีต้อนรับสู่
                    <span class="font-semibold text-white">
                        PSPP Platform
                    </span>

                    ระบบบริหารจัดการโรงเรียนพระปริยัติธรรม
                    แผนกสามัญศึกษา กลุ่มจังหวัดแพร่
                </p>

                <div class="mt-5 flex flex-wrap gap-3">

                    <span
                        class="inline-flex items-center rounded-full bg-white/20 px-4 py-2 text-sm text-white">

                        📅
                        {{ $hero['today']->translatedFormat('l ที่ d F Y') }}

                    </span>

                    <span
                        class="inline-flex items-center rounded-full bg-white/20 px-4 py-2 text-sm text-white">

                        🕒
                        {{ $hero['today']->format('H:i') }}

                    </span>

                </div>

            </div>

            {{-- Right --}}
            <div class="flex-shrink-0">

                <div
                    class="w-36 h-36 lg:w-44 lg:h-44 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center border border-white/20">

                    <div class="text-center">

                        <div class="text-6xl">
                            🏫
                        </div>

                        <div class="mt-2 text-white font-semibold">
                            PSPP
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>