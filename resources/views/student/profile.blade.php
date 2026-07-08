@extends('layouts.app')

@section('content')

@php
    $student = $user->student;

    $profileImage = $user->profile_image
        ? asset('storage/' . $user->profile_image)
        : 'https://ui-avatars.com/api/?name=' . urlencode($student?->full_name ?? $user->name) . '&background=10b981&color=ffffff&size=256';

    $selectedProvinceId = old('province_id', $user->province_id);
    $selectedDistrictId = old('district_id', $user->district_id);
    $selectedSubdistrictId = old('subdistrict_id', $user->subdistrict_id);

    $enroll = $student
        ? $student->enrollments()->latest('academic_year')->latest('semester')->first()
        : null;
@endphp

<div class="flex min-h-screen bg-slate-100">

    @include('student.sidebar')

    <div class="flex-1 min-w-0">

        {{-- HEADER --}}
        <div class="bg-white border-b border-slate-200 px-6 py-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800">👤 โปรไฟล์นักเรียน</h1>
                <p class="text-sm text-slate-500 mt-1">จัดการข้อมูลบัญชีและข้อมูลติดต่อของตนเอง</p>
            </div>

            <div class="flex items-center gap-3">
                <img src="{{ $profileImage }}"
                     alt="รูปโปรไฟล์"
                     class="w-10 h-10 rounded-full object-cover border border-slate-200">

                <div class="text-right">
                    <p class="font-medium text-slate-800">
                        {{ $student?->full_name ?? $user->name }}
                    </p>
                    <p class="text-xs text-slate-500">นักเรียน</p>
                </div>
            </div>
        </div>

        <div class="p-4 md:p-6 max-w-5xl mx-auto">

            @if (session('success'))
                <div class="mb-5 p-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 p-4 rounded-xl border border-red-200 bg-red-50 text-red-700">
                    <p class="font-semibold mb-2">⚠️ กรุณาตรวจสอบข้อมูลอีกครั้ง</p>
                    <ul class="list-disc pl-5 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('student.profile.update') }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

                    {{-- PROFILE IMAGE --}}
                    <div class="p-6 border-b border-slate-200">
                        <div class="flex flex-col sm:flex-row items-center gap-5">
                            <img src="{{ $profileImage }}"
                                 alt="รูปโปรไฟล์"
                                 class="w-28 h-28 rounded-full object-cover border-4 border-emerald-100">

                            <div class="text-center sm:text-left">
                                <h2 class="font-semibold text-slate-800">📷 รูปโปรไฟล์</h2>
                                <p class="text-sm text-slate-500 mt-1 mb-3">
                                    รองรับไฟล์ JPG, JPEG, PNG และ WEBP
                                </p>

                                <input type="file"
                                       name="profile_image"
                                       accept="image/jpeg,image/png,image/webp"
                                       class="block w-full text-sm text-slate-600
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-lg file:border-0
                                              file:bg-emerald-50 file:text-emerald-700
                                              hover:file:bg-emerald-100">
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-8">

                        {{-- ACCOUNT --}}
                        <section>
                            <h2 class="font-semibold text-slate-800 mb-4">🔐 ข้อมูลบัญชี</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">
                                        ชื่อเข้าระบบ
                                    </label>
                                    <input type="text"
                                           name="name"
                                           value="{{ old('name', $user->name) }}"
                                           class="w-full border border-slate-300 p-2.5 rounded-lg focus:border-emerald-500 focus:ring-emerald-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">
                                        อีเมล
                                    </label>
                                    <input type="email"
                                           name="email"
                                           value="{{ old('email', $user->email) }}"
                                           class="w-full border border-slate-300 p-2.5 rounded-lg focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                            </div>
                        </section>

                        {{-- STUDENT --}}
                        <section>
                            <h2 class="font-semibold text-slate-800 mb-4">🎓 ข้อมูลนักเรียน</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">รหัสนักเรียน</label>
                                    <input value="{{ $student?->student_code ?? '-' }}"
                                           class="w-full border border-slate-200 p-2.5 rounded-lg bg-slate-100 text-slate-600"
                                           readonly>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">เลขบัตรประชาชน</label>
                                    <input value="{{ $student?->id_card ?? '-' }}"
                                           class="w-full border border-slate-200 p-2.5 rounded-lg bg-slate-100 text-slate-600"
                                           readonly>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">ชื่อ-นามสกุล</label>
                                    <input value="{{ $student?->full_name ?? '-' }}"
                                           class="w-full border border-slate-200 p-2.5 rounded-lg bg-slate-100 text-slate-600"
                                           readonly>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">โรงเรียน</label>
                                    <input value="{{ $student?->school?->school_name ?? '-' }}"
                                           class="w-full border border-slate-200 p-2.5 rounded-lg bg-slate-100 text-slate-600"
                                           readonly>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">สังกัดวัด</label>
                                    <input value="{{ $student?->temple?->temple_name ?? '-' }}"
                                           class="w-full border border-slate-200 p-2.5 rounded-lg bg-slate-100 text-slate-600"
                                           readonly>
                                </div>
                            </div>
                        </section>

                        {{-- EDUCATION --}}
                        <section>
                            <h2 class="font-semibold text-slate-800 mb-4">📚 ข้อมูลการศึกษา</h2>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">ระดับชั้น</label>
                                    <input value="ม.{{ $enroll?->grade_level ?? '-' }}"
                                           class="w-full border border-slate-200 p-2.5 rounded-lg bg-slate-100 text-slate-600"
                                           readonly>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">ภาคเรียน</label>
                                    <input value="เทอม {{ $enroll?->semester ?? '-' }}"
                                           class="w-full border border-slate-200 p-2.5 rounded-lg bg-slate-100 text-slate-600"
                                           readonly>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">ปีการศึกษา</label>
                                    <input value="{{ $enroll?->academic_year ?? '-' }}"
                                           class="w-full border border-slate-200 p-2.5 rounded-lg bg-slate-100 text-slate-600"
                                           readonly>
                                </div>
                            </div>
                        </section>

                        {{-- CONTACT --}}
                        <section>
                            <h2 class="font-semibold text-slate-800 mb-4">📞 ข้อมูลติดต่อ</h2>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">เบอร์โทรศัพท์</label>
                                <input type="text"
                                       name="phone"
                                       value="{{ old('phone', $user->phone) }}"
                                       placeholder="เช่น 0812345678"
                                       class="w-full border border-slate-300 p-2.5 rounded-lg focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                        </section>

                        {{-- ADDRESS --}}
                        <section>
                            <h2 class="font-semibold text-slate-800 mb-4">📍 ที่อยู่</h2>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">บ้านเลขที่ / หมู่ / ถนน</label>
                                    <textarea name="address1"
                                              rows="2"
                                              class="w-full border border-slate-300 p-2.5 rounded-lg focus:border-emerald-500 focus:ring-emerald-500"
                                              placeholder="บ้านเลขที่ หมู่ ถนน">{{ old('address1', $user->address1) }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">รายละเอียดเพิ่มเติม</label>
                                    <textarea name="address2"
                                              rows="2"
                                              class="w-full border border-slate-300 p-2.5 rounded-lg focus:border-emerald-500 focus:ring-emerald-500"
                                              placeholder="เช่น ซอย หมู่บ้าน จุดสังเกต">{{ old('address2', $user->address2) }}</textarea>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">จังหวัด</label>
                                        <select id="province"
                                                name="province_id"
                                                class="w-full border border-slate-300 p-2.5 rounded-lg focus:border-emerald-500 focus:ring-emerald-500">
                                            <option value="">-- เลือกจังหวัด --</option>

                                            @foreach ($provinces->sortBy('name_th') as $province)
                                                <option value="{{ $province->province_id }}"
                                                    @selected((string) $selectedProvinceId === (string) $province->province_id)>
                                                    {{ $province->name_th }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">อำเภอ</label>
                                        <select id="district"
                                                name="district_id"
                                                class="w-full border border-slate-300 p-2.5 rounded-lg focus:border-emerald-500 focus:ring-emerald-500">
                                            <option value="">-- เลือกอำเภอ --</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">ตำบล</label>
                                        <select id="subdistrict"
                                                name="subdistrict_id"
                                                class="w-full border border-slate-300 p-2.5 rounded-lg focus:border-emerald-500 focus:ring-emerald-500">
                                            <option value="">-- เลือกตำบล --</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">รหัสไปรษณีย์</label>
                                    <input id="zipcode"
                                           name="zipcode"
                                           value="{{ old('zipcode', $user->zipcode) }}"
                                           class="w-full border border-slate-200 p-2.5 rounded-lg bg-slate-100 text-slate-600"
                                           readonly>
                                </div>
                            </div>
                        </section>

                        {{-- PASSWORD --}}
                        <section>
                            <h2 class="font-semibold text-slate-800 mb-1">🔑 เปลี่ยนรหัสผ่าน</h2>
                            <p class="text-sm text-slate-500 mb-4">เว้นว่างไว้ หากยังไม่ต้องการเปลี่ยนรหัสผ่าน</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">รหัสผ่านใหม่</label>
                                    <input type="password"
                                           name="password"
                                           autocomplete="new-password"
                                           class="w-full border border-slate-300 p-2.5 rounded-lg focus:border-emerald-500 focus:ring-emerald-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">ยืนยันรหัสผ่านใหม่</label>
                                    <input type="password"
                                           name="password_confirmation"
                                           autocomplete="new-password"
                                           class="w-full border border-slate-300 p-2.5 rounded-lg focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                            </div>
                        </section>

                    </div>

                    {{-- BUTTON --}}
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                        <button type="submit"
                                class="bg-emerald-600 text-white px-6 py-2.5 rounded-lg hover:bg-emerald-700 transition shadow-sm">
                            💾 บันทึกข้อมูล
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const subdistrictSelect = document.getElementById('subdistrict');
    const zipcodeInput = document.getElementById('zipcode');

    const selectedDistrictId = @json($selectedDistrictId);
    const selectedSubdistrictId = @json($selectedSubdistrictId);

    function clearDistricts() {
        districtSelect.innerHTML = '<option value="">-- เลือกอำเภอ --</option>';
        subdistrictSelect.innerHTML = '<option value="">-- เลือกตำบล --</option>';
    }

    function clearSubdistricts() {
        subdistrictSelect.innerHTML = '<option value="">-- เลือกตำบล --</option>';
    }

    async function loadDistricts(provinceId, selectedId = null) {
        clearDistricts();

        if (!provinceId) {
            return;
        }

        try {
            const response = await fetch(`/districts/${provinceId}`);

            if (!response.ok) {
                throw new Error('ไม่สามารถโหลดข้อมูลอำเภอได้');
            }

            const districts = await response.json();

            districts.forEach(function (district) {
                const option = document.createElement('option');

                option.value = district.district_id;
                option.textContent = district.name_th;

                if (String(district.district_id) === String(selectedId)) {
                    option.selected = true;
                }

                districtSelect.appendChild(option);
            });
        } catch (error) {
            console.error(error);
        }
    }

    async function loadSubdistricts(districtId, selectedId = null) {
        clearSubdistricts();

        if (!districtId) {
            return;
        }

        try {
            const response = await fetch(`/subdistricts/${districtId}`);

            if (!response.ok) {
                throw new Error('ไม่สามารถโหลดข้อมูลตำบลได้');
            }

            const subdistricts = await response.json();

            subdistricts.forEach(function (subdistrict) {
                const option = document.createElement('option');

                option.value = subdistrict.subdistrict_id;
                option.textContent = subdistrict.name_th;
                option.dataset.zipcode = subdistrict.zipcode ?? '';

                if (String(subdistrict.subdistrict_id) === String(selectedId)) {
                    option.selected = true;
                }

                subdistrictSelect.appendChild(option);
            });

            updateZipcode();
        } catch (error) {
            console.error(error);
        }
    }

    function updateZipcode() {
        const selectedOption = subdistrictSelect.options[subdistrictSelect.selectedIndex];

        if (selectedOption && selectedOption.dataset.zipcode) {
            zipcodeInput.value = selectedOption.dataset.zipcode;
        }
    }

    provinceSelect.addEventListener('change', async function () {
        zipcodeInput.value = '';
        await loadDistricts(this.value);
    });

    districtSelect.addEventListener('change', async function () {
        zipcodeInput.value = '';
        await loadSubdistricts(this.value);
    });

    subdistrictSelect.addEventListener('change', function () {
        updateZipcode();
    });

    async function loadSavedAddress() {
        const provinceId = provinceSelect.value;

        if (!provinceId) {
            return;
        }

        await loadDistricts(provinceId, selectedDistrictId);

        if (selectedDistrictId) {
            await loadSubdistricts(selectedDistrictId, selectedSubdistrictId);
        }
    }

    loadSavedAddress();
});
</script>

@endsection