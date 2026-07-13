<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

    <div class="flex items-center justify-between mb-5">

        <div>

            <h2 class="text-xl font-bold text-gray-800">
                📊 ผลการประเมินประสิทธิภาพโดยรวมของ PSPP Platform
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                สรุปผลการประเมินประสิทธิภาพของระบบแยกตามบทบาท
            </p>

        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead>

                <tr class="border-b">

                    <th class="text-left py-3 font-semibold text-gray-600">
                        บทบาท
                    </th>

                    <th class="text-center py-3 font-semibold text-gray-600">
                        ผู้ตอบ
                    </th>

                    <th class="text-right py-3 font-semibold text-gray-600">
                        ค่าเฉลี่ย
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach($evaluationSummary as $item)

                    <tr class="border-b last:border-0 hover:bg-gray-50">

                        <td class="py-4">

                            <div class="flex items-center gap-3">

                                <span class="text-2xl">
                                    {{ $item['icon'] }}
                                </span>

                                <span class="font-medium text-gray-800">
                                    {{ $item['role'] }}
                                </span>

                            </div>

                        </td>

                        <td class="text-center">

                            {{ number_format($item['respondents']) }}

                        </td>

                        <td class="text-right">

                            @php

                                $avg = $item['average'];

                            @endphp

                            @if($avg >= 4.50)

                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 font-semibold">

                                    ⭐ {{ number_format($avg,2) }}

                                </span>

                            @elseif($avg >= 3.50)

                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 font-semibold">

                                    ⭐ {{ number_format($avg,2) }}

                                </span>

                            @else

                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-100 text-red-700 font-semibold">

                                    ⭐ {{ number_format($avg,2) }}

                                </span>

                            @endif

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>