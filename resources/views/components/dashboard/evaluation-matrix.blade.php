<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

    <div class="flex items-center justify-between mb-6">

        <div>

            <h2 class="text-xl font-bold text-gray-800">
                📊 ผลการประเมินประสิทธิภาพระบบ PSPP Platform แยกตามโรงเรียน
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                ค่าเฉลี่ยผลการประเมิน (คะแนนเฉลี่ย และจำนวนผู้ตอบ)
            </p>

        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="min-w-full border-collapse">

            <thead>

                <tr class="bg-gray-50 border-b">

                    <th class="text-left px-4 py-3 font-semibold">
                        โรงเรียน
                    </th>

                    <th class="text-center px-4 py-3">
                        👨‍💼 ผู้บริหาร
                    </th>

                    <th class="text-center px-4 py-3">
                        👨‍🏫 ครู
                    </th>

                    <th class="text-center px-4 py-3">
                        👨‍🎓 นักเรียน
                    </th>

                    <th class="text-center px-4 py-3">
                        👥 บุคลากร
                    </th>

                    <th class="text-center px-4 py-3 font-bold">
                        ⭐ รวม
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach($evaluationMatrix as $row)

                    <tr class="border-b hover:bg-gray-50">

                        <td class="px-4 py-3 font-semibold">

                            {{ $row['school'] }}

                        </td>

                        @foreach(['director','teacher','student','staff'] as $role)

                            @php

                                $item = $row[$role];

                            @endphp

                            <td class="text-center px-4 py-3">

                                @if($item['count'] > 0)

                                    {{ number_format($item['average'],2) }}
                                    ({{ $item['count'] }})

                                @else

                                    -

                                @endif

                            </td>

                        @endforeach

                        <td class="text-center px-4 py-3 font-bold text-emerald-700">

                            @if($row['total']['count'] > 0)

                                {{ number_format($row['total']['average'],2) }}
                                ({{ $row['total']['count'] }})

                            @else

                                -

                            @endif

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>