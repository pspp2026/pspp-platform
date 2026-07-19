<div class="bg-white rounded-xl shadow-sm border border-gray-200">

    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">

        <div>

            <h2 class="text-lg font-semibold text-gray-800">
                🟢 ผู้ใช้ออนไลน์
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                ขณะนี้มีผู้ใช้งานออนไลน์ทั้งหมด
                <span class="font-semibold text-emerald-600">
                    {{ $onlineUsersCount }}
                </span>
                คน
            </p>

        </div>

    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-50">

                <tr>

                    <th class="px-6 py-3 text-left font-semibold text-gray-700">
                        ชื่อ
                    </th>

                    <th class="px-6 py-3 text-left font-semibold text-gray-700">
                        บทบาท
                    </th>

                    <th class="px-6 py-3 text-left font-semibold text-gray-700">
                        โรงเรียน
                    </th>

                </tr>

            </thead>

            <tbody>

            @forelse($onlineUsers as $online)

                <tr class="border-t hover:bg-gray-50">

                    <td class="px-6 py-3">

                        <div class="flex items-center gap-2">

                            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>

                            {{ $online->user->name }}

                        </div>

                    </td>

                    <td class="px-6 py-3">

                        @switch($online->user->role)

                            @case('director')
                                👨‍💼 ผู้บริหาร
                                @break

                            @case('teacher')
                                👨‍🏫 ครู
                                @break

                            @case('student')
                                👨‍🎓 นักเรียน
                                @break

                            @case('staff')
                                👥 บุคลากร
                                @break

                            @default
                                {{ ucfirst($online->user->role) }}

                        @endswitch

                    </td>

                    <td class="px-6 py-3">
                        {{ $online->user->school->short_name ?? '-' }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="3"
                        class="px-6 py-6 text-center text-gray-500">

                        ไม่มีผู้ใช้ออนไลน์

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    {{-- Footer --}}
    <div class="px-6 py-4 border-t border-gray-200 text-center">

        <a href="{{ route('superadmin.online-users') }}"
           class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-medium">

            ดูทั้งหมด →

        </a>

    </div>

</div>