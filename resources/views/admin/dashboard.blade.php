@extends('layouts.admin')

@section('content')

<div class="p-6 space-y-6">

    <!-- ============================= -->
    <!-- Header -->
    <!-- ============================= -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-8 text-white">

        <h1 class="text-3xl font-bold">
            ระบบบริหารจัดการโรงเรียนพระปริยัติธรรม
        </h1>

        <p class="mt-2 text-blue-100 text-lg">
            PSPP Platform (Phrae Sangha Provincial Platform)
        </p>

        <p class="mt-3 text-blue-100">
            ระบบต้นแบบสำหรับบริหารจัดการโรงเรียนพระปริยัติธรรม แผนกสามัญศึกษา
            กลุ่มจังหวัดแพร่ รองรับการบริหารจัดการทั้ง 5 งาน ได้แก่
            งานวิชาการ งานบุคคล งานงบประมาณ งานบริหารทั่วไป และงานประกันคุณภาพ
        </p>

    </div>

    <!-- ============================= -->
    <!-- KPI -->
    <!-- ============================= -->

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

        <div class="bg-white rounded-xl shadow border-l-4 border-blue-500 p-5">
            <p class="text-gray-500 text-sm">ผู้ใช้งานทั้งหมด</p>
            <h2 class="text-3xl font-bold text-blue-600 mt-2">
                {{ $totalUsers }}
            </h2>
        </div>

        <div class="bg-yellow-50 rounded-xl shadow border-l-4 border-yellow-500 p-5">
            <p class="text-gray-500 text-sm">รออนุมัติ</p>
            <h2 class="text-3xl font-bold text-yellow-600 mt-2">
                {{ $pendingUsers }}
            </h2>
        </div>

        <div class="bg-green-50 rounded-xl shadow border-l-4 border-green-500 p-5">
            <p class="text-gray-500 text-sm">อนุมัติแล้ว</p>
            <h2 class="text-3xl font-bold text-green-600 mt-2">
                {{ $approvedUsers }}
            </h2>
        </div>

        <div class="bg-red-50 rounded-xl shadow border-l-4 border-red-500 p-5">
            <p class="text-gray-500 text-sm">ไม่อนุมัติ</p>
            <h2 class="text-3xl font-bold text-red-600 mt-2">
                {{ $rejectedUsers }}
            </h2>
        </div>

    </div>

    <!-- ============================= -->
    <!-- ระบบบริหาร -->
    <!-- ============================= -->

    <div>

        <h2 class="text-xl font-bold mb-4">
            📁 ระบบสนับสนุนการบริหารงาน 5 ด้าน
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

            <div class="bg-blue-500 text-white rounded-xl shadow p-5 text-center">
                <div class="text-3xl mb-2">📚</div>
                <div class="font-semibold">บริหารวิชาการ</div>
            </div>

            <div class="bg-green-500 text-white rounded-xl shadow p-5 text-center">
                <div class="text-3xl mb-2">👨‍🏫</div>
                <div class="font-semibold">บริหารบุคคล</div>
            </div>

            <div class="bg-yellow-500 text-white rounded-xl shadow p-5 text-center">
                <div class="text-3xl mb-2">💰</div>
                <div class="font-semibold">งบประมาณ</div>
            </div>

            <div class="bg-purple-500 text-white rounded-xl shadow p-5 text-center">
                <div class="text-3xl mb-2">🏫</div>
                <div class="font-semibold">บริหารทั่วไป</div>
            </div>

            <div class="bg-red-500 text-white rounded-xl shadow p-5 text-center">
                <div class="text-3xl mb-2">📊</div>
                <div class="font-semibold">ประกันคุณภาพ</div>
            </div>

        </div>

    </div>

    <!-- ============================= -->
    <!-- Action -->
    <!-- ============================= -->

    <div class="flex flex-wrap gap-3">

        <a href="{{ route('admin.users.pending') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow transition">

            👥 ตรวจสอบผู้ใช้งาน

        </a>

    </div>

    <!-- ============================= -->
    <!-- ตาราง -->
    <!-- ============================= -->

    <div class="bg-white rounded-xl shadow">

        <div class="border-b px-6 py-4">

            <h2 class="text-xl font-semibold">

                สรุปจำนวนผู้ใช้งานแยกตามโรงเรียน

            </h2>

            <p class="text-gray-500 text-sm mt-1">

                แสดงจำนวนผู้ใช้งานที่ลงทะเบียนในแต่ละโรงเรียน

            </p>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="p-4 text-left">
                            โรงเรียน
                        </th>

                        <th class="p-4 text-center">
                            จำนวนผู้ใช้งาน
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($schools as $school)

                    <tr class="border-t hover:bg-gray-50">

                        <td class="p-4">
                            {{ $school->school }}
                        </td>

                        <td class="p-4 text-center font-semibold">
                            {{ $school->total }}
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

    <!-- ============================= -->
    <!-- Footer -->
    <!-- ============================= -->

    <div class="bg-white rounded-xl shadow p-6">

        <div class="text-center text-gray-600">

            <h3 class="font-semibold text-lg">
                PSPP Platform Version 1.0 (Prototype)
            </h3>

            <p class="mt-2">
                ระบบบริหารจัดการโรงเรียนพระปริยัติธรรม แผนกสามัญศึกษา
            </p>

            <p>
                กลุ่มจังหวัดแพร่
            </p>

            <p class="mt-3 text-sm text-gray-500">
                เพื่อสนับสนุนการบริหารจัดการโรงเรียนด้วยเทคโนโลยีสารสนเทศ
            </p>

        </div>

    </div>

</div>

@endsection