@props([
    'step' => 1,
    'total' => 6,
    'title' => '',
])

@php

    $percent = ($step / $total) * 100;

@endphp

<div class="bg-white rounded-xl shadow p-6 mb-8">

    <div class="flex items-center justify-between mb-4">

        <div>

            <h2 class="text-2xl font-bold text-gray-800">

                {{ $title }}

            </h2>

            <p class="text-sm text-gray-500 mt-1">

                ขั้นตอนที่ {{ $step }} จากทั้งหมด {{ $total }}

            </p>

        </div>

        <div class="text-blue-700 font-bold text-xl">

            {{ round($percent) }}%

        </div>

    </div>

    <!-- Progress Bar -->

    <div class="w-full h-3 rounded-full bg-gray-200 overflow-hidden">

        <div
            class="h-3 bg-blue-600 transition-all duration-500"
            style="width: {{ $percent }}%">

        </div>

    </div>

    <!-- Step Number -->

    <div class="flex justify-between mt-4">

        @for($i = 1; $i <= $total; $i++)

            <div class="flex flex-col items-center flex-1">

                <div
                    class="
                        w-10
                        h-10
                        rounded-full
                        flex
                        items-center
                        justify-center
                        font-bold

                        @if($i < $step)

                            bg-green-600 text-white

                        @elseif($i == $step)

                            bg-blue-600 text-white

                        @else

                            bg-gray-300 text-gray-600

                        @endif
                    ">

                    {{ $i }}

                </div>

            </div>

        @endfor

    </div>

</div>