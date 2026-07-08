@extends('layouts.admin')

@section('title', '✏️ แก้ไขข้อมูลนักเรียน')

@section('content')
@php
    $nationality = old('nationality', $student->nationality);
    $ethnicity = old('ethnicity', $student->ethnicity);

    if (blank($nationality) || $nationality === '???') {
        $nationality = 'ไทย';
    }

    if (blank($ethnicity) || $ethnicity === '???') {
        $ethnicity = 'ไทย';
    }

    $initial = mb_substr($student->first_name ?? 'น', 0, 1);
@endphp

<div class="max-w-6xl mx-auto px-4 py-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-3xl shadow-sm">
                🎓
            </div>

            <div>
                <h1 class="text-2xl font-bold text-slate-800">✏️ แก้ไขข้อมูลนักเรียน</h1>
                <p class="text-sm text-slate-500 mt-1">
                    ตรวจสอบและปรับปรุงข้อมูลประวัตินักเรียน
                </p>
            </div>
        </div>

        <a href="{{ route('admin.students.index') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
            <span>←</span>
            <span>กลับรายชื่อนักเรียน</span>
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
            <div class="font-semibold mb-2">⚠️ กรุณาตรวจสอบข้อมูลอีกครั้ง</div>
            <ul class="list-disc pl-5 space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <aside class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 text-center sticky top-6">

                <div class="w-28 h-28 mx-auto rounded-full bg-gradient-to-br from-emerald-100 to-teal-100 text-emerald-700 flex items-center justify-center text-5xl font-bold shadow-inner">
                    {{ $initial }}
                </div>

                <h2 class="mt-4 text-xl font-bold text-slate-800">
                    {{ $student->full_name }}
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    🪪 รหัสนักเรียน: {{ $student->student_code ?? '-' }}
                </p>

                <div class="mt-5 border-t border-slate-100 pt-5 text-left space-y-3">
                    <div class="flex items-center gap-3">
                        <span class="text-lg">🏫</span>
                        <div>
                            <p class="text-xs text-slate-400">โรงเรียน</p>
                            <p class="text-sm font-medium text-slate-700">
                                {{ $student->school?->school_name ?? 'ยังไม่กำหนด' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-lg">🚪</span>
                        <div>
                            <p class="text-xs text-slate-400">ห้องเรียน</p>
                            <p class="text-sm font-medium text-slate-700">
                                {{ $student->classroom?->name ?? 'ยังไม่กำหนด' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-lg">🗓️</span>
                        <div>
                            <p class="text-xs text-slate-400">วันเกิด</p>
                            <p class="text-sm font-medium text-slate-700">
                                {{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->translatedFormat('j F Y') : 'ไม่ระบุ' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <div class="lg:col-span-2">
            <form action="{{ route('admin.students.update', $student) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h2 class="font-semibold text-slate-800">📋 ข้อมูลประวัตินักเรียน</h2>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">🏫 โรงเรียน <span class="text-red-500">*</span></label>
                            <select name="school_id" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" required>
                                <option value="">-- เลือกโรงเรียน --</option>
                                @foreach ($schools as $school)
                                    <option value="{{ $school->id }}" @selected(old('school_id', $student->school_id) == $school->id)>
                                        {{ $school->school_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">🚪 ห้องเรียน</label>
                            <select name="classroom_id" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">-- ยังไม่กำหนดห้องเรียน --</option>
                                @foreach ($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}" @selected(old('classroom_id', $student->classroom_id) == $classroom->id)>
                                        {{ $classroom->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">🪪 รหัสนักเรียน <span class="text-red-500">*</span></label>
                            <input type="text" name="student_code" value="{{ old('student_code', $student->student_code) }}"
                                   class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">🙏 คำนำหน้า</label>
                            <select name="prefix" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">-- เลือกคำนำหน้า --</option>
                                <option value="สามเณร" @selected(old('prefix', $student->prefix) === 'สามเณร')>สามเณร</option>
                                <option value="พระ" @selected(old('prefix', $student->prefix) === 'พระ')>พระ</option>
                                <option value="นาย" @selected(old('prefix', $student->prefix) === 'นาย')>นาย</option>
                                <option value="นางสาว" @selected(old('prefix', $student->prefix) === 'นางสาว')>นางสาว</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">👤 ชื่อ <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}"
                                   class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">👥 นามสกุล <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}"
                                   class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">🪪 เลขบัตรประชาชน</label>
                            <input type="text" name="id_card" value="{{ old('id_card', $student->id_card) }}"
                                   class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">🎂 วันเกิด</label>
                            <input type="date" name="birth_date"
                                   value="{{ old('birth_date', $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('Y-m-d') : '') }}"
                                   class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">🌏 สัญชาติ</label>
                            <select name="nationality" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="ไทย" @selected($nationality === 'ไทย')>ไทย</option>
                                <option value="ลาว" @selected($nationality === 'ลาว')>ลาว</option>
                                <option value="เมียนมา" @selected($nationality === 'เมียนมา')>เมียนมา</option>
                                <option value="กัมพูชา" @selected($nationality === 'กัมพูชา')>กัมพูชา</option>
                                <option value="จีน" @selected($nationality === 'จีน')>จีน</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">🧬 เชื้อชาติ</label>
                            <select name="ethnicity" class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="ไทย" @selected($ethnicity === 'ไทย')>ไทย</option>
                                <option value="ลาว" @selected($ethnicity === 'ลาว')>ลาว</option>
                                <option value="เมียนมา" @selected($ethnicity === 'เมียนมา')>เมียนมา</option>
                                <option value="กัมพูชา" @selected($ethnicity === 'กัมพูชา')>กัมพูชา</option>
                                <option value="จีน" @selected($ethnicity === 'จีน')>จีน</option>
                            </select>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row justify-end gap-3">
                        <a href="{{ route('admin.students.index') }}"
                           class="text-center px-5 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100 transition">
                            ✖️ ยกเลิก
                        </a>

                        <button type="submit"
                                class="px-5 py-2.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition shadow-sm">
                            💾 บันทึกการแก้ไข
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection