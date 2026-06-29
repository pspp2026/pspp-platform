@extends('layouts.app')

@section('title', 'คู่มือการนำเข้าข้อมูลนักเรียน')

@section('content')

<div class="min-h-screen bg-slate-100">

    <div class="max-w-7xl mx-auto py-10 px-6">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-700 to-indigo-700 rounded-2xl shadow-xl text-white p-8">

            <h1 class="text-4xl font-bold">
                📘 คู่มือการนำเข้าข้อมูลนักเรียน
            </h1>

            <p class="mt-3 text-blue-100 text-lg">
                PSPP Platform
            </p>

            <p class="text-blue-100">
                โรงเรียนพระปริยัติธรรม แผนกสามัญศึกษา
                กลุ่มจังหวัดแพร่
            </p>

        </div>

        {{-- วัตถุประสงค์ --}}
        <div class="mt-8 bg-white rounded-xl shadow">

            <div class="border-b px-6 py-4">

                <h2 class="text-2xl font-bold text-slate-700">
                    🎯 วัตถุประสงค์
                </h2>

            </div>

            <div class="p-6 leading-8 text-gray-700">

                ระบบนี้ใช้สำหรับ

                <span class="font-semibold text-blue-700">
                    นำเข้าข้อมูลนักเรียนจำนวนมาก
                </span>

                จากไฟล์ CSV

                เข้าสู่ระบบ PSPP

                โดยข้อมูลทั้งหมดจะถูกบันทึกลงตาราง

                <span class="font-semibold">
                    students
                </span>

                และเมื่อผู้เรียน Login ครั้งแรก

                ระบบจะสร้างบัญชีผู้ใช้อัตโนมัติ

            </div>

        </div>

        {{-- Download --}}
        <div class="mt-8 grid md:grid-cols-3 gap-6">

            <div class="bg-white rounded-xl shadow p-6">

                <div class="text-5xl">
                    📄
                </div>

                <h3 class="mt-4 text-xl font-bold">
                    Template Excel
                </h3>

                <p class="mt-2 text-gray-600">

                    ดาวน์โหลดไฟล์ตัวอย่าง

                    เพื่อกรอกข้อมูลนักเรียน

                </p>

               
                <a href="{{ asset('templates/PSPP_student_template.xlsx') }}"
                    download
                    class="mt-5 inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg shadow">

                        📥 ดาวน์โหลด Template Excel

                    </a>

            </div>

            <div class="bg-white rounded-xl shadow p-6">

                <div class="text-5xl">
                    📄
                </div>

                <h3 class="mt-4 text-xl font-bold">
                    Template CSV
                </h3>

                <p class="mt-2 text-gray-600">

                    สำหรับ Import เข้าระบบ

                </p>

                <a href="{{ asset('templates/student_template.csv') }}"
                   class="mt-5 inline-block bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg">

                    ดาวน์โหลด CSV

                </a>

            </div>

            <div class="bg-white rounded-xl shadow p-6">

                <div class="text-5xl">
                    📕
                </div>

                <h3 class="mt-4 text-xl font-bold">
                    คู่มือ PDF
                </h3>

                <p class="mt-2 text-gray-600">

                    ดาวน์โหลดคู่มือฉบับเต็ม

                </p>

                <a href="{{ asset('templates/คู่มือการนำเข้าข้อมูลนักเรียน.pdf') }}"
                   target="_blank"
                   class="mt-5 inline-block bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-lg">

                    ดาวน์โหลด PDF

                </a>

            </div>

        </div>

        {{-- STEP 1 --}}
        <div class="mt-8 bg-white rounded-xl shadow">

            <div class="bg-blue-600 text-white px-6 py-4 rounded-t-xl">

                <h2 class="text-2xl font-bold">

                    ขั้นตอนที่ 1

                    ดาวน์โหลด Template

                </h2>

            </div>

            <div class="p-6">

                <ol class="list-decimal ml-6 space-y-3">

                    <li>

                        เข้าสู่เมนู

                        <span class="font-semibold">

                            วิชาการ

                            →

                            จัดนักเรียนเข้าห้องเรียน

                            →

                            Import นักเรียน

                        </span>

                    </li>

                    <li>

                       ดาวน์โหลดไฟล์

                    <a href="{{ asset('templates/PSPP_student_template.xlsx') }}"
                    download
                    class="font-semibold text-blue-700 hover:text-blue-900 underline">

                        PSPP_student_template.xlsx

                    </a>

                    </li>

                    <li>

                        เปิดด้วย Microsoft Excel

                        หรือ LibreOffice Calc

                    </li>

                </ol>

            </div>

        </div>

        {{-- STEP 2 --}}
        <div class="mt-8 bg-white rounded-xl shadow">

            <div class="bg-emerald-600 text-white px-6 py-4 rounded-t-xl">

                <h2 class="text-2xl font-bold">

                    ขั้นตอนที่ 2

                    กรอกข้อมูลนักเรียน

                </h2>

            </div>

            <div class="p-6">

                <p class="mb-6 text-gray-700">

                    กรอกข้อมูลตามหัวตาราง

                    โดยห้ามเปลี่ยนชื่อคอลัมน์

                </p>

                <div class="overflow-x-auto">

                    <table class="min-w-full border">

                        <thead class="bg-slate-100">

                            <tr>

                                <th class="border p-3 text-left">
                                    คอลัมน์
                                </th>

                                <th class="border p-3 text-left">
                                    ตัวอย่าง
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td class="border p-3">school_id</td>
                                <td class="border p-3">1</td>

                            </tr>

                            <tr>

                                <td class="border p-3">classroom_id</td>
                                <td class="border p-3">3</td>

                            </tr>

                            <tr>

                                <td class="border p-3">student_code</td>
                                <td class="border p-3">66001</td>

                            </tr>

                            <tr>

                                <td class="border p-3">prefix</td>
                                <td class="border p-3">สามเณร</td>

                            </tr>

                            <tr>

                                <td class="border p-3">first_name</td>
                                <td class="border p-3">สมชาย</td>

                            </tr>

                            <tr>

                                <td class="border p-3">last_name</td>
                                <td class="border p-3">ใจดี</td>

                            </tr>

                            <tr>

                                <td class="border p-3">id_card</td>
                                <td class="border p-3">1234567890123</td>

                            </tr>

                            <tr>

                                <td class="border p-3">birth_date</td>
                                <td class="border p-3">2012-10-25</td>

                            </tr>

                            <tr>

                                <td class="border p-3">nationality</td>
                                <td class="border p-3">ไทย</td>

                            </tr>

                            <tr>

                                <td class="border p-3">ethnicity</td>
                                <td class="border p-3">ไทย</td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
                {{-- STEP 3 --}}
        <div class="mt-8 bg-white rounded-xl shadow">

            <div class="bg-amber-600 text-white px-6 py-4 rounded-t-xl">

                <h2 class="text-2xl font-bold">

                    ขั้นตอนที่ 3

                    บันทึกเป็นไฟล์ CSV UTF-8

                </h2>

            </div>

            <div class="p-6">

                <p class="text-gray-700 mb-5">

                    หลังจากกรอกข้อมูลนักเรียนครบแล้ว
                    ให้บันทึกไฟล์เป็น

                    <span class="font-semibold text-red-600">

                        CSV UTF-8 (Comma delimited)

                    </span>

                    เท่านั้น

                </p>

                <div class="bg-slate-100 rounded-lg p-5">

<pre class="text-sm leading-8">
File
   ↓
Save As
   ↓
CSV UTF-8 (Comma delimited)
   ↓
Save
</pre>

                </div>

                <div class="mt-6 rounded-lg border-l-4 border-red-500 bg-red-50 p-4">

                    <h3 class="font-bold text-red-700">

                        ❌ ห้าม Upload ไฟล์ Excel (.xlsx)

                    </h3>

                    <p class="mt-2 text-gray-700">

                        ระบบ PSPP รองรับเฉพาะ

                        <span class="font-semibold">

                            CSV UTF-8

                        </span>

                        เพื่อให้ทุกโรงเรียนใช้มาตรฐานเดียวกัน

                    </p>

                </div>

            </div>

        </div>

        {{-- STEP 4 --}}
        <div class="mt-8 bg-white rounded-xl shadow">

            <div class="bg-indigo-600 text-white px-6 py-4 rounded-t-xl">

                <h2 class="text-2xl font-bold">

                    ขั้นตอนที่ 4

                    Upload เข้าระบบ

                </h2>

            </div>

            <div class="p-6">

                <ol class="list-decimal ml-6 space-y-3 text-gray-700">

                    <li>

                        เข้าเมนู

                        <strong>

                            จัดนักเรียนเข้าห้องเรียน

                        </strong>

                    </li>

                    <li>

                        เลือก

                        <strong>

                            Import นักเรียน

                        </strong>

                    </li>

                    <li>

                        เลือกไฟล์

                        <strong>

                            student_template.csv

                        </strong>

                    </li>

                    <li>

                        กดปุ่ม

                        <span class="font-semibold text-blue-700">

                            📤 นำเข้าข้อมูลนักเรียน

                        </span>

                    </li>

                </ol>

            </div>

        </div>

        {{-- STEP 5 --}}
        <div class="mt-8 bg-white rounded-xl shadow">

            <div class="bg-green-600 text-white px-6 py-4 rounded-t-xl">

                <h2 class="text-2xl font-bold">

                    ขั้นตอนที่ 5

                    ตรวจสอบผลการนำเข้า

                </h2>

            </div>

            <div class="p-6">

                <p class="mb-5">

                    เมื่อระบบ Import เสร็จแล้ว
                    ระบบจะแสดงผลการทำงาน เช่น

                </p>

                <div class="grid md:grid-cols-3 gap-6">

                    <div class="bg-green-50 rounded-lg p-5 border">

                        <div class="text-4xl">

                            ✅

                        </div>

                        <div class="mt-3 font-bold">

                            Import สำเร็จ

                        </div>

                        <div class="text-gray-600 mt-2">

                            จำนวนรายการที่ถูกเพิ่มเข้าสู่ระบบ

                        </div>

                    </div>

                    <div class="bg-yellow-50 rounded-lg p-5 border">

                        <div class="text-4xl">

                            ⚠️

                        </div>

                        <div class="mt-3 font-bold">

                            ข้อมูลซ้ำ

                        </div>

                        <div class="text-gray-600 mt-2">

                            student_code ซ้ำกับข้อมูลเดิม

                        </div>

                    </div>

                    <div class="bg-red-50 rounded-lg p-5 border">

                        <div class="text-4xl">

                            ❌

                        </div>

                        <div class="mt-3 font-bold">

                            ข้อมูลผิดพลาด

                        </div>

                        <div class="text-gray-600 mt-2">

                            ตรวจสอบไฟล์ CSV แล้ว Import ใหม่

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- ข้อควรระวัง --}}
        <div class="mt-8 bg-white rounded-xl shadow">

            <div class="border-b px-6 py-4">

                <h2 class="text-2xl font-bold text-red-700">

                    ⚠️ ข้อควรระวัง

                </h2>

            </div>

            <div class="p-6">

                <ul class="space-y-4">

                    <li>✅ student_code ต้องไม่ซ้ำ</li>

                    <li>✅ school_id ต้องมีอยู่ในระบบ</li>

                    <li>✅ classroom_id ต้องมีอยู่ในระบบ</li>

                    <li>✅ birth_date ต้องเป็นรูปแบบ YYYY-MM-DD</li>

                    <li>✅ บันทึกเป็น CSV UTF-8 ทุกครั้งก่อน Upload</li>

                    <li>✅ ห้ามแก้ชื่อคอลัมน์ใน Template</li>

                </ul>

            </div>

        </div>

        {{-- Tips --}}
        <div class="mt-8 rounded-xl bg-blue-50 border border-blue-200 p-6">

            <h2 class="text-2xl font-bold text-blue-700">

                💡 คำแนะนำ

            </h2>

            <div class="mt-5 space-y-3 text-gray-700">

                <p>

                    • ใช้ไฟล์ Template ที่ระบบเตรียมไว้ทุกครั้ง

                </p>

                <p>

                    • หาก Import ไม่ผ่าน ให้เปิดไฟล์ CSV ด้วย Notepad
                    ตรวจสอบ Encoding เป็น UTF-8

                </p>

                <p>

                    • หากพบข้อมูลซ้ำ
                    ให้แก้ไข student_code แล้ว Import ใหม่

                </p>

                <p>

                    • แนะนำให้ Import ทีละโรงเรียน
                    เพื่อให้ตรวจสอบข้อมูลได้ง่าย

                </p>

            </div>

        </div>
                {{-- FAQ --}}
        <div class="mt-8 bg-white rounded-xl shadow">

            <div class="border-b px-6 py-4">

                <h2 class="text-2xl font-bold text-slate-700">

                    ❓ คำถามที่พบบ่อย (FAQ)

                </h2>

            </div>

            <div class="divide-y">

                <div class="p-6">

                    <h3 class="font-bold text-lg">

                        Q : ทำไมระบบไม่รับไฟล์ Excel (.xlsx) ?

                    </h3>

                    <p class="mt-2 text-gray-700">

                        A : เพื่อให้ทุกโรงเรียนใช้มาตรฐานเดียวกัน
                        ระบบรองรับเฉพาะ
                        <strong>CSV UTF-8</strong>
                        สำหรับการนำเข้าข้อมูล

                    </p>

                </div>

                <div class="p-6">

                    <h3 class="font-bold text-lg">

                        Q : สามารถ Import ได้กี่คน ?

                    </h3>

                    <p class="mt-2 text-gray-700">

                        A : ไม่จำกัดจำนวน
                        ขึ้นอยู่กับประสิทธิภาพของ Server
                        และขนาดไฟล์ CSV

                    </p>

                </div>

                <div class="p-6">

                    <h3 class="font-bold text-lg">

                        Q : ถ้านำเข้าซ้ำจะเกิดอะไรขึ้น ?

                    </h3>

                    <p class="mt-2 text-gray-700">

                        A : ระบบจะตรวจสอบ
                        <strong>student_code</strong>

                        หากซ้ำ จะไม่เพิ่มข้อมูลซ้ำ

                    </p>

                </div>

                <div class="p-6">

                    <h3 class="font-bold text-lg">

                        Q : ต้องสร้าง User ก่อนหรือไม่ ?

                    </h3>

                    <p class="mt-2 text-gray-700">

                        A : ไม่ต้อง

                        ระบบจะสร้าง User
                        เมื่อนักเรียน Login
                        ครั้งแรกโดยอัตโนมัติ

                    </p>

                </div>

            </div>

        </div>


        {{-- Workflow --}}
        <div class="mt-8 bg-white rounded-xl shadow">

            <div class="border-b px-6 py-4">

                <h2 class="text-2xl font-bold text-indigo-700">

                    🔄 ลำดับการทำงานของระบบ

                </h2>

            </div>

            <div class="p-8">

                <div class="space-y-3 text-center text-lg font-semibold">

                    <div class="bg-blue-50 rounded-lg p-4">

                        📄 Excel Template

                    </div>

                    <div class="text-3xl">

                        ↓

                    </div>

                    <div class="bg-blue-50 rounded-lg p-4">

                        💾 Save As CSV UTF-8

                    </div>

                    <div class="text-3xl">

                        ↓

                    </div>

                    <div class="bg-green-50 rounded-lg p-4">

                        📤 Import CSV

                    </div>

                    <div class="text-3xl">

                        ↓

                    </div>

                    <div class="bg-yellow-50 rounded-lg p-4">

                        👨‍🎓 Students

                    </div>

                    <div class="text-3xl">

                        ↓

                    </div>

                    <div class="bg-yellow-50 rounded-lg p-4">

                        🎓 Enrollment

                    </div>

                    <div class="text-3xl">

                        ↓

                    </div>

                    <div class="bg-yellow-50 rounded-lg p-4">

                        📝 Attendance

                    </div>

                    <div class="text-3xl">

                        ↓

                    </div>

                    <div class="bg-yellow-50 rounded-lg p-4">

                        📊 Score

                    </div>

                    <div class="text-3xl">

                        ↓

                    </div>

                    <div class="bg-yellow-50 rounded-lg p-4">

                        🏅 Grade

                    </div>

                    <div class="text-3xl">

                        ↓

                    </div>

                    <div class="bg-purple-50 rounded-lg p-4">

                        🔐 นักเรียน Login ครั้งแรก

                    </div>

                    <div class="text-3xl">

                        ↓

                    </div>

                    <div class="bg-emerald-100 rounded-lg p-4">

                        👤 ระบบสร้าง Users อัตโนมัติ

                    </div>

                    <div class="text-3xl">

                        ↓

                    </div>

                    <div class="bg-emerald-100 rounded-lg p-4">

                        🔗 students.user_id = users.id

                    </div>

                </div>

            </div>

        </div>


        {{-- หมายเหตุ --}}
        <div class="mt-8 bg-amber-50 border border-amber-300 rounded-xl p-6">

            <h2 class="text-xl font-bold text-amber-700">

                📌 หมายเหตุ

            </h2>

            <p class="mt-4 text-gray-700 leading-8">

                ระบบ PSPP ถูกออกแบบให้
                นำเข้าข้อมูลนักเรียนก่อน
                เพื่อให้โรงเรียนสามารถจัดการข้อมูลนักเรียนได้ทันที

                จากนั้นจึงดำเนินการ

                จัดนักเรียนเข้าห้องเรียน

                บันทึกการเข้าเรียน

                บันทึกคะแนน

                และตัดเกรด

                เมื่อผู้เรียนเข้าสู่ระบบครั้งแรก
                ระบบจะสร้างบัญชีผู้ใช้ (User)
                ให้อัตโนมัติ
                และเชื่อมโยงกับข้อมูลนักเรียน
                ผ่านฟิลด์

                <strong>

                    students.user_id

                </strong>

            </p>

        </div>


        {{-- Footer --}}
        <div class="mt-10 mb-8 text-center text-gray-500 text-sm">

            <p>

                PSPP Platform

            </p>

            <p>

                โรงเรียนพระปริยัติธรรม แผนกสามัญศึกษา

            </p>

            <p>

                กลุ่มจังหวัดแพร่

            </p>

            <p class="mt-2">

                Version 1.0

            </p>

        </div>

    </div>

</div>

@endsection