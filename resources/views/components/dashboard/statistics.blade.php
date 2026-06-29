<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

    {{-- Schools --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm text-gray-500">
                    โรงเรียน
                </p>

                <h2 class="mt-2 text-3xl font-bold text-gray-900">
                    {{ number_format($statistics['schools']) }}
                </h2>

                <p class="mt-2 text-sm text-emerald-600">
                    โรงเรียนในระบบ
                </p>

            </div>

            <div class="w-14 h-14 rounded-xl bg-emerald-100 flex items-center justify-center text-3xl">
                🏫
            </div>

        </div>

    </div>

    {{-- Users --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm text-gray-500">
                    ผู้ใช้งาน
                </p>

                <h2 class="mt-2 text-3xl font-bold text-gray-900">
                    {{ number_format($statistics['users']) }}
                </h2>

                <p class="mt-2 text-sm text-blue-600">
                    Users ทั้งหมด
                </p>

            </div>

            <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center text-3xl">
                👤
            </div>

        </div>

    </div>

    {{-- Students --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm text-gray-500">
                    นักเรียน
                </p>

                <h2 class="mt-2 text-3xl font-bold text-gray-900">
                    {{ number_format($statistics['students']) }}
                </h2>

                <p class="mt-2 text-sm text-purple-600">
                    นักเรียนทั้งหมด
                </p>

            </div>

            <div class="w-14 h-14 rounded-xl bg-purple-100 flex items-center justify-center text-3xl">
                🎓
            </div>

        </div>

    </div>

    {{-- Teachers --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm text-gray-500">
                    ครูผู้สอน
                </p>

                <h2 class="mt-2 text-3xl font-bold text-gray-900">
                    {{ number_format($statistics['teachers']) }}
                </h2>

                <p class="mt-2 text-sm text-amber-600">
                    ครูทั้งหมด
                </p>

            </div>

            <div class="w-14 h-14 rounded-xl bg-amber-100 flex items-center justify-center text-3xl">
                👨‍🏫
            </div>

        </div>

    </div>

</div>