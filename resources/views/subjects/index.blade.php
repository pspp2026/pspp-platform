<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบรายวิชา</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .card-hover {
            transition: all 0.25s ease;
        }
        .card-hover:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen p-6">

<div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                📚 ระบบรายวิชา
            </h1>
            <p class="text-gray-500">จัดการรายวิชาตามระดับชั้น</p>
        </div>
    </div>

    <!-- FILTER CARD -->
    <div class="bg-white p-5 rounded-2xl shadow-lg mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">

            <!-- ชั้น -->
            <div>
                <label class="text-sm text-gray-600">ชั้น</label>
                <select name="class" class="border rounded-lg px-3 py-2">
                    @for($i=1; $i<=6; $i++)
                        <option value="{{ $i }}" {{ request('class',1)==$i?'selected':'' }}>
                            ม.{{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- เทอม -->
            <div>
                <label class="text-sm text-gray-600">เทอม</label>
                <select name="semester" class="border rounded-lg px-3 py-2">
                    <option value="1" {{ request('semester',1)==1?'selected':'' }}>เทอม 1</option>
                    <option value="2" {{ request('semester')==2?'selected':'' }}>เทอม 2</option>
                </select>
            </div>

            <!-- ประเภท -->
            <div>
                <label class="text-sm text-gray-600">ประเภท</label>
                <select name="type" class="border rounded-lg px-3 py-2">
                    <option value="">ทั้งหมด</option>
                    <option value="พื้นฐาน" {{ request('type')=='พื้นฐาน'?'selected':'' }}>พื้นฐาน</option>
                    <option value="เพิ่มเติม" {{ request('type')=='เพิ่มเติม'?'selected':'' }}>เพิ่มเติม</option>
                </select>
            </div>

            <button class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
                🔍 ค้นหา
            </button>

        </form>
    </div>

    <!-- TITLE -->
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-700">
            📘 รายวิชา ม.{{ request('class',1) }} เทอม {{ request('semester',1) }}
        </h2>

        <span class="px-3 py-1 text-sm rounded-full text-white
            {{ request('class',1) <= 3 ? 'bg-green-500' : 'bg-purple-500' }}">
            {{ request('class',1) <= 3 ? 'มัธยมต้น' : 'มัธยมปลาย' }}
        </span>
    </div>

    <!-- GRID -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">

        @forelse($subjects as $sub)

        <div class="bg-white rounded-2xl p-5 shadow card-hover border-l-4
            {{ $sub->subject_type == 'พื้นฐาน' ? 'border-blue-500' : 'border-orange-500' }}">

            <!-- CODE -->
            <div class="text-blue-600 font-mono text-lg font-bold mb-1">
                {{ $sub->subject_code }}
            </div>

            <!-- NAME -->
            <div class="text-gray-800 font-semibold mb-2">
                {{ $sub->subject_name }}
            </div>

            <!-- GROUP -->
            <div class="mb-3">
                <span class="bg-gray-200 text-xs px-2 py-1 rounded">
                    {{ $sub->group->name ?? '-' }}
                </span>
            </div>

            <!-- INFO -->
            <div class="flex justify-between items-center">

                <!-- TYPE -->
                <span class="text-xs px-2 py-1 rounded text-white
                    {{ $sub->subject_type == 'พื้นฐาน' ? 'bg-blue-500' : 'bg-orange-500' }}">
                    {{ $sub->subject_type }}
                </span>

                <!-- CLASS -->
                <span class="text-xs text-gray-500">
                    {{ $sub->class_name }}
                </span>

            </div>

        </div>

        @empty

        <div class="col-span-3 text-center text-gray-400 py-10">
            ❌ ไม่พบข้อมูล
        </div>

        @endforelse

    </div>

</div>

</body>
</html>