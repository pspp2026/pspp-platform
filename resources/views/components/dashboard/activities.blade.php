<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-lg font-bold text-gray-800">
            📋 Recent Activities
        </h2>

        <span class="text-sm text-gray-500">
            ล่าสุด
        </span>

    </div>

    <div class="space-y-5">

        @forelse($activities as $activity)

            <div class="flex gap-4">

                <div class="text-3xl">
                    {{ $activity['icon'] }}
                </div>

                <div>

                    <h3 class="font-semibold text-gray-800">
                        {{ $activity['title'] }}
                    </h3>

                    <p class="text-gray-500 text-sm">
                        {{ $activity['description'] }}
                    </p>

                    <p class="text-xs text-gray-400 mt-1">
                        {{ $activity['time'] }}
                    </p>

                </div>

            </div>

        @empty

            <div class="text-center py-10 text-gray-400">

                ยังไม่มีกิจกรรม

            </div>

        @endforelse

    </div>

</div>