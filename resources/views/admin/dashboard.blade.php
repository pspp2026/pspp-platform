@extends('layouts.app')

@section('content')

<div class="flex min-h-screen bg-gray-100">

    <!-- 🔵 SIDEBAR -->
    <aside class="w-64 bg-gray-900 text-white hidden md:block">

        <div class="p-4 text-xl font-bold border-b border-gray-700">
            ⚙️ ADMIN PANEL
        </div>

        <nav class="p-4 space-y-2 text-sm">

            <a href="/"
           class="block px-3 py-2 rounded hover:bg-blue-700">
            🏠 HOME
        </a>

         <div class="border-t border-gray-700 my-4"></div>

            <a href="/admin/dashboard"
               class="block px-3 py-2 rounded bg-gray-800">
                🏠 Dashboard
            </a>

            <div class="text-gray-400 text-xs mt-4">USERS</div>

            <a href="{{ route('admin.users.pending') }}"
               class="block px-3 py-2 rounded hover:bg-gray-800">
                ⏳ อนุมัติผู้ใช้
            </a>

            <a href="#"
               class="block px-3 py-2 rounded hover:bg-gray-800">
                👥 รายชื่อผู้ใช้
            </a>

            <div class="text-gray-400 text-xs mt-4">SCHOOLS</div>

            <a href="{{ route('schools.index') }}"
               class="block px-3 py-2 rounded hover:bg-gray-800">
                🏫 โรงเรียน
            </a>

            <a href="{{ route('schools.create') }}"
               class="block px-3 py-2 rounded hover:bg-gray-800">
                ➕ เพิ่มโรงเรียน
            </a>

            <div class="text-gray-400 text-xs mt-4">REPORTS</div>

            <a href="/reports/academic" class="block px-3 py-2 hover:bg-gray-800 rounded">📘 วิชาการ</a>
            <a href="/reports/personnel" class="block px-3 py-2 hover:bg-gray-800 rounded">👨‍💼 บุคคล</a>
            <a href="/reports/finance" class="block px-3 py-2 hover:bg-gray-800 rounded">💰 งบประมาณ</a>
            <a href="/reports/general" class="block px-3 py-2 hover:bg-gray-800 rounded">📁 ทั่วไป</a>
            <a href="/reports/qa" class="block px-3 py-2 hover:bg-gray-800 rounded">🏅 QA</a>

            <div class="text-gray-400 text-xs mt-4">OTHER</div>

            <a href="/calendar" class="block px-3 py-2 hover:bg-gray-800 rounded">📅 ปฏิทิน</a>

            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button class="w-full text-left px-3 py-2 bg-red-600 rounded hover:bg-red-700">
                    🚪 Logout
                </button>
            </form>

        </nav>
    </aside>


    <!-- 🟡 MAIN -->
    <div class="flex-1 flex flex-col">

        <!-- 🔷 TOPBAR -->
        <div class="bg-white shadow px-6 py-4 flex justify-between items-center">

            <div>
                <h1 class="text-xl font-bold">📊 Admin Dashboard</h1>
                <p class="text-sm text-gray-500">
                    ยินดีต้อนรับ {{ auth()->user()->name }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <img 
                    src="{{ auth()->user()->profile_image 
                        ? asset('storage/' . auth()->user()->profile_image) 
                        : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                    class="w-10 h-10 rounded-full border">

                <span class="text-sm">{{ auth()->user()->name }}</span>
            </div>

        </div>


        <!-- 🔶 CONTENT -->
        <div class="p-6 space-y-6">

            <!-- 🔶 STATS -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Total Users</p>
                    <h2 class="text-2xl font-bold">{{ $totalUsers }}</h2>
                </div>

                <div class="bg-yellow-100 p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Pending</p>
                    <h2 class="text-2xl font-bold">{{ $pendingUsers }}</h2>
                </div>

                <div class="bg-green-100 p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Approved</p>
                    <h2 class="text-2xl font-bold">{{ $approvedUsers }}</h2>
                </div>

                <div class="bg-red-100 p-5 rounded-xl shadow">
                    <p class="text-gray-500 text-sm">Rejected</p>
                    <h2 class="text-2xl font-bold">{{ $rejectedUsers }}</h2>
                </div>

            </div>


            <!-- 🔷 REPORT -->
            <div>
                <h2 class="text-lg font-bold mb-3">📁 ระบบรายงาน 5 งาน</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

                    <a href="/reports/academic" class="bg-blue-500 text-white p-4 rounded-xl shadow hover:scale-105 transition">
                        บริหารวิชาการ
                    </a>

                    <a href="/reports/personnel" class="bg-green-500 text-white p-4 rounded-xl shadow hover:scale-105 transition">
                        บริหารบุคคล
                    </a>

                    <a href="/reports/finance" class="bg-yellow-500 text-white p-4 rounded-xl shadow hover:scale-105 transition">
                        งบประมาณ
                    </a>

                    <a href="/reports/general" class="bg-purple-500 text-white p-4 rounded-xl shadow hover:scale-105 transition">
                        งานทั่วไป
                    </a>

                    <a href="/reports/qa" class="bg-red-500 text-white p-4 rounded-xl shadow hover:scale-105 transition">
                        ประกันคุณภาพ
                    </a>

                </div>
            </div>


            <!-- 🔷 ACTION -->
            <div class="flex gap-3">

                <a href="{{ route('schools.create') }}"
                   class="bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600">
                    ➕ เพิ่มโรงเรียน
                </a>

                <a href="{{ route('admin.users.pending') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    👥 ตรวจสอบผู้ใช้งาน
                </a>

            </div>


            <!-- 🔷 TABLE -->
            <div class="bg-white rounded-xl shadow p-6">

                <h2 class="text-lg font-semibold mb-4">Users by School</h2>

                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="p-3">School</th>
                            <th class="p-3">Total Users</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schools as $school)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3">{{ $school->school }}</td>
                            <td class="p-3">{{ $school->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>

    </div>

</div>

@endsection