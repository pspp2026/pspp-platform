@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">

    {{-- 🔥 FORM --}}
    <div class="bg-white shadow-xl rounded-2xl p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">🏫 เพิ่มโรงเรียน</h2>

                    <a href="/" class="home-btn">HOME</a>

        {{-- SUCCESS --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR --}}
        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('schools.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <input name="school_code" value="{{ old('school_code') }}" placeholder="รหัสโรงเรียน" class="border p-2 rounded-lg">
                <input name="school_name" value="{{ old('school_name') }}" placeholder="ชื่อโรงเรียน" class="border p-2 rounded-lg">
            </div>

            <input name="temple" value="{{ old('temple') }}" placeholder="วัด" class="border p-2 rounded-lg w-full">

            <div class="grid grid-cols-2 gap-4">
                <input name="address1" value="{{ old('address1') }}" placeholder="ที่อยู่ 1" class="border p-2 rounded-lg">
                <input name="address2" value="{{ old('address2') }}" placeholder="ที่อยู่ 2" class="border p-2 rounded-lg">
            </div>

            {{-- จังหวัด อำเภอ ตำบล --}}
            <div class="grid grid-cols-3 gap-4">

                <select id="province" name="province_id" class="border p-2 rounded-lg">
                    <option value="">-- จังหวัด --</option>
                    @foreach($provinces as $p)
                        <option value="{{ $p->province_id }}"
                            {{ old('province_id') == $p->province_id ? 'selected' : '' }}>
                            {{ $p->name_th }}
                        </option>
                    @endforeach
                </select>

                <select id="district" name="district_id" class="border p-2 rounded-lg">
                    <option value="">-- อำเภอ --</option>
                </select>

                <select id="subdistrict" name="subdistrict_id" class="border p-2 rounded-lg">
                    <option value="">-- ตำบล --</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <input id="zipcode" name="zipcode" readonly class="border p-2 rounded-lg bg-gray-100" placeholder="รหัสไปรษณีย์">
                <input id="zone_code" name="zone_code" readonly class="border p-2 rounded-lg bg-gray-100" placeholder="Zone">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <input name="website" value="{{ old('website') }}" placeholder="Website" class="border p-2 rounded-lg">
                <input name="facebook" value="{{ old('facebook') }}" placeholder="Facebook" class="border p-2 rounded-lg">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <input name="phone" value="{{ old('phone') }}" placeholder="เบอร์โทร" class="border p-2 rounded-lg">
                <input name="email" value="{{ old('email') }}" placeholder="Email" class="border p-2 rounded-lg">
            </div>

            <button class="bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg w-full">
                💾 บันทึก
            </button>
        </form>
    </div>

    {{-- 🔥 TABLE --}}
    <div class="bg-white shadow-xl rounded-2xl p-6">
        <h2 class="text-xl font-bold mb-4">📋 รายชื่อโรงเรียน</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm table-fixed">
                <thead>
                    <tr class="border-b bg-gray-50 text-gray-600">
                        <th class="p-2 ">รหัส</th>
                        <th class="p-2 ">ชื่อโรงเรียน</th>
                        <th class="p-2 ">วัด</th>
                        <th class="p-2 ">จังหวัด</th>
                        <th class="p-2 ">อำเภอ</th>
                        <th class="p-2 ">ตำบล</th>
                        <th class="p-2 ">ไปรษณีย์</th>
                        <th class="p-2 ">Zone</th>
                        <th class="p-2 text-center">จัดการ</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($schools as $s)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-2">{{ $s->school_code }}</td>
                        <td class="p-2">{{ $s->school_name }}</td>
                        <td class="p-2">{{ $s->temple ?? '-' }}</td>
                        <td class="p-2">{{ $s->province->name_th ?? '-' }}</td>
                        <td class="p-2">{{ $s->district->name_th ?? '-' }}</td>
                        <td class="p-2">{{ $s->subdistrict->name_th ?? '-' }}</td>
                        <td class="p-2">{{ $s->subdistrict->zipcode ?? '-' }}</td>

                        <td class="p-2 text-center">
                            @if($s->zone_code)
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded">
                                    เขต {{ $s->zone_code }}
                                </span>
                            @else
                                -
                            @endif
                        </td>

                        <td class="p-2 text-center space-x-2">
                            <a href="{{ route('schools.edit', $s->id) }}"
                               class="bg-yellow-400 px-2 py-1 rounded text-sm">✏️</a>

                            <form action="{{ route('schools.destroy', $s->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('ลบจริงไหม?')"
                                    class="bg-red-500 text-white px-2 py-1 rounded text-sm">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center p-4 text-gray-400">ไม่พบข้อมูล</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- 🔥 JS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const province = document.getElementById('province');
    const district = document.getElementById('district');
    const subdistrict = document.getElementById('subdistrict');
    const zipcode = document.getElementById('zipcode');
    const zone = document.getElementById('zone_code');

    function mapZone(provinceId) {
        provinceId = Number(provinceId);

        if ([10,11,12,13,14,15,16,18,19,73,74,75].includes(provinceId)) return 1;
        if ([70,71,72,76,77].includes(provinceId)) return 2;
        if ([60,61,62,63,64,65,66].includes(provinceId)) return 3;
        if ([67,42,39,41,43,38].includes(provinceId)) return 4;
        if ([50,51,58].includes(provinceId)) return 5;
        if ([57,56,54,55,52].includes(provinceId)) return 6;
        if ([40,46,44,45].includes(provinceId)) return 7;
        if ([47,48,49,35,34,37].includes(provinceId)) return 8;
        if ([30,31,32,33,36].includes(provinceId)) return 9;
        if ([24,25,26,27,20,21,22,23].includes(provinceId)) return 12;

        return '';
    }

    function setZone() {
        zone.value = mapZone(province.value);
    }

    province.addEventListener('change', function () {
        setZone();

        fetch('/districts/' + this.value)
            .then(res => res.json())
            .then(data => {
                district.innerHTML = '<option value="">-- อำเภอ --</option>';
                subdistrict.innerHTML = '<option value="">-- ตำบล --</option>';
                zipcode.value = '';

                data.forEach(d => {
                    district.innerHTML += `<option value="${d.district_id}">${d.name_th}</option>`;
                });
            });
    });

    district.addEventListener('change', function () {
        fetch('/subdistricts/' + this.value)
            .then(res => res.json())
            .then(data => {
                subdistrict.innerHTML = '<option value="">-- ตำบล --</option>';

                data.forEach(s => {
                    let zip = s.zipcode ?? s.zip_code ?? '';
                    subdistrict.innerHTML += `<option value="${s.subdistrict_id}" data-zip="${zip}">${s.name_th}</option>`;
                });
            });
    });

    subdistrict.addEventListener('change', function () {
        let zip = this.options[this.selectedIndex].dataset.zip || '';
        zipcode.value = zip;
    });

});
</script>

@endsection