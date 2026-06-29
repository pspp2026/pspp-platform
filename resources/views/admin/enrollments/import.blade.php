@extends('layouts.app')

@section('content')

<div class="flex min-h-screen bg-gray-100">

    @include('admin.partials.sidebar')

    <div class="flex-1">

        {{-- Header --}}
        <div class="bg-white shadow px-6 py-4 flex justify-between items-center">

            <h1 class="text-2xl font-bold text-slate-800">
                📥 นำเข้าข้อมูลนักเรียน
            </h1>

            <a href="{{ route('admin.enrollments.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                ← กลับ

            </a>

        </div>

        <div class="p-6">

            {{-- Success --}}
            @if(session('success'))

                <div class="mb-5 bg-green-100 border border-green-300 text-green-700 p-4 rounded-lg">

                    {{ session('success') }}

                </div>

            @endif

            {{-- Error --}}
            @if ($errors->any())

                <div class="mb-5 bg-red-100 border border-red-300 text-red-700 p-4 rounded-lg">

                    <ul class="list-disc ml-5">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <div class="bg-white rounded-xl shadow p-8">

                <h2 class="text-xl font-semibold mb-6">

                    ขั้นตอนการนำเข้าข้อมูลนักเรียน

                </h2>

                <div class="space-y-4">

                    <div class="flex items-start gap-3">

                        <span class="text-2xl">①</span>

                        <div>

                            <div class="font-semibold">

                                ดาวน์โหลด Template Excel

                            </div>

                            <div class="text-gray-600">

                                กรอกข้อมูลนักเรียนตามรูปแบบที่กำหนด

                            </div>

                        </div>

                    </div>

                    <div class="flex items-start gap-3">

                        <span class="text-2xl">②</span>

                        <div>

                            <div class="font-semibold">

                                บันทึกเป็น CSV

                            </div>

                            <div class="text-gray-600">

                                File → Save As → CSV UTF-8 (*.csv)

                            </div>

                        </div>

                    </div>

                    <div class="flex items-start gap-3">

                        <span class="text-2xl">③</span>

                        <div>

                            <div class="font-semibold">

                                Upload ไฟล์ CSV

                            </div>

                            <div class="text-gray-600">

                                ระบบจะตรวจสอบข้อมูลก่อนนำเข้า

                            </div>

                        </div>

                    </div>

                </div>

                <hr class="my-8">

                <div class="flex flex-wrap gap-4 mb-8">

                    <a href="{{ asset('templates/PSPP_student_template.xlsx') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg">

                            📄 ดาวน์โหลด Template Excel

                    </a>

                    <a href="{{ asset('templates/คู่มือการนำเข้าข้อมูลนักเรียน.pdf') }}"
                    target="_blank"
                    class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-lg">

                        📘 คู่มือการใช้งาน

                    </a>

                </div>

                <form action="{{ route('admin.enrollments.import.store') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    <div>

                        <label class="block font-semibold mb-2">

                            เลือกไฟล์ CSV

                        </label>

                        <input
                            type="file"
                            name="csv"
                            accept=".csv"
                            required
                            class="w-full border rounded-lg p-3">

                        <p class="text-sm text-gray-500 mt-2">

                            รองรับเฉพาะไฟล์ .csv (UTF-8)

                        </p>

                    </div>

                    <div class="mt-8">

                        <button
                            type="submit"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-lg">

                            📤 นำเข้าข้อมูลนักเรียน

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection