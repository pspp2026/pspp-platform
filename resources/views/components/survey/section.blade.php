@props([
    'step' => null,
    'title',
    'description' => null,
])

<div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-2xl shadow-lg p-6 mb-8">

    <div class="flex items-center gap-4">

        @if($step)

            <div
                class="w-14 h-14 rounded-full bg-white text-blue-700
                       flex items-center justify-center
                       text-2xl font-bold shadow">

                {{ $step }}

            </div>

        @endif

        <div>

            <h2 class="text-2xl font-bold">

                {{ $title }}

            </h2>

            @if($description)

                <p class="mt-2 text-blue-100">

                    {{ $description }}

                </p>

            @endif

        </div>

    </div>

</div>