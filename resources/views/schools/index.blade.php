<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายการโรงเรียน</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

<div class="max-w-7xl mx-auto bg-white shadow-lg rounded-xl p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">📋 รายชื่อโรงเรียน</h2>

        <a href="{{ route('schools.create') }}"
           class="bg-green-500 text-white px-4 py-2 rounded-lg">
           ➕ เพิ่มโรงเรียน
        </a>
    </div>

    <!-- 🔍 Filter -->
    <form method="GET" class="flex gap-3 mb-4">

        <!-- จังหวัด -->
        <select name="province_id" class="border p-2 rounded">
            <option value="">-- ทุกจังหวัด --</option>
            @foreach($provinces as $p)
                <option value="{{ $p->id }}"
                    {{ request('province_id') == $p->id ? 'selected' : '' }}>
                    {{ $p->name_th }}
                </option>
            @endforeach
        </select>

        <!-- เขต -->
        <select name="zone_code" class="border p-2 rounded">
            <option value="">-- ทุกเขต --</option>
            @for($i=1; $i<=12; $i++)
                <option value="{{ $i }}"
                    {{ request('zone_code') == $i ? 'selected' : '' }}>
                    เขต {{ $i }}
                </option>
            @endfor
        </select>

        <button class="bg-blue-500 text-white px-4 rounded">
            🔍 ค้นหา
        </button>

    </form>

    <!-- ตาราง -->
    <div class="overflow-x-auto">
        <table class="w-full border text-sm">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">รหัส</th>
                    <th class="p-2 border">ชื่อโรงเรียน</th>
                    <th class="p-2 border">จังหวัด</th>
                    <th class="p-2 border">อำเภอ</th>
                    <th class="p-2 border">ตำบล</th>
                    <th class="p-2 border">เขต</th>
                    <th class="p-2 border">จัดการ</th>
                </tr>
            </thead>

            <tbody>
                @forelse($schools as $i => $s)
                <tr class="hover:bg-gray-50">
                    <td class="p-2 border text-center">
                        {{ $schools->firstItem() + $i }}
                    </td>

                    <td class="p-2 border">{{ $s->school_code }}</td>
                    <td class="p-2 border font-medium">{{ $s->school_name }}</td>

                    <td class="p-2 border">
                        {{ $s->province->name_th ?? '-' }}
                    </td>

                    <td class="p-2 border">
                        {{ $s->district->name_th ?? '-' }}
                    </td>

                    <td class="p-2 border">
                        {{ $s->subdistrict->name_th ?? '-' }}
                    </td>

                    <!-- 🔥 เขต -->
                    <td class="p-2 border text-center">
                        @if($s->zone_code)
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded">
                                เขต {{ $s->zone_code }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>

                    <!-- จัดการ -->
                    <td class="p-2 border text-center space-x-2">

                        <a href="{{ route('schools.edit', $s->id) }}"
                           class="bg-yellow-400 px-2 py-1 rounded">
                           ✏️
                        </a>

                        <form action="{{ route('schools.destroy', $s->id) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirm('ยืนยันการลบ?')">

                            @csrf
                            @method('DELETE')

                            <button class="bg-red-500 text-white px-2 py-1 rounded">
                                🗑️
                            </button>
                        </form>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center p-4 text-gray-500">
                        ไม่พบข้อมูล
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 📄 Pagination -->
    <div class="mt-4">
        {{ $schools->links() }}
    </div>

</div>

</body>
</html>