<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

    <h2 class="text-lg font-bold text-gray-800 mb-5">
        ⚡ Quick Actions
    </h2>

    <div class="grid grid-cols-2 gap-4">

        @foreach($quickActions as $action)

            <a href="{{ $action['route'] }}"
               class="rounded-xl border border-gray-200 hover:border-emerald-500 hover:bg-emerald-50 transition p-5 text-center">

                <div class="text-4xl mb-3">
                    {{ $action['icon'] }}
                </div>

                <div class="font-semibold text-gray-700">
                    {{ $action['title'] }}
                </div>

            </a>

        @endforeach

    </div>

</div>