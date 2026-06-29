<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-lg font-bold text-gray-800">
            🖥️ System Status
        </h2>

        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
            Online
        </span>

    </div>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-5">

        <div class="rounded-xl bg-gray-50 p-5 text-center">

            <div class="text-gray-500 text-sm">
                PHP
            </div>

            <div class="mt-2 text-xl font-bold">
                {{ $systemStatus['php'] }}
            </div>

        </div>

        <div class="rounded-xl bg-gray-50 p-5 text-center">

            <div class="text-gray-500 text-sm">
                Laravel
            </div>

            <div class="mt-2 text-xl font-bold">
                {{ $systemStatus['laravel'] }}
            </div>

        </div>

        <div class="rounded-xl bg-gray-50 p-5 text-center">

            <div class="text-gray-500 text-sm">
                Database
            </div>

            <div class="mt-2 text-xl font-bold">

                {{ $systemStatus['database'] }}

            </div>

        </div>

        <div class="rounded-xl bg-gray-50 p-5 text-center">

            <div class="text-gray-500 text-sm">
                Environment
            </div>

            <div class="mt-2 text-xl font-bold">

                {{ ucfirst($systemStatus['environment']) }}

            </div>

        </div>

        <div class="rounded-xl bg-gray-50 p-5 text-center">

            <div class="text-gray-500 text-sm">
                Timezone
            </div>

            <div class="mt-2 text-xl font-bold">

                {{ $systemStatus['timezone'] }}

            </div>

        </div>

    </div>

</div>