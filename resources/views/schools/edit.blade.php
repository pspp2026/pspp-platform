<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขโรงเรียน</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded-2xl shadow">

    <h2 class="text-2xl font-bold mb-6 text-gray-700">
        ✏️ แก้ไขโรงเรียน
    </h2>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('schools.update', $school->id) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <input name="school_code" value="{{ $school->school_code }}" class="w-full border p-2 rounded-lg" placeholder="รหัสโรงเรียน">

        <input name="school_name" value="{{ $school->school_name }}" class="w-full border p-2 rounded-lg" placeholder="ชื่อโรงเรียน">

        <input name="temple" value="{{ $school->temple }}" class="w-full border p-2 rounded-lg" placeholder="วัด">

        <input name="address1" value="{{ $school->address1 }}" class="w-full border p-2 rounded-lg" placeholder="ที่อยู่ 1">

        <input name="address2" value="{{ $school->address2 }}" class="w-full border p-2 rounded-lg" placeholder="ที่อยู่ 2">

        {{-- จังหวัด --}}
        <select id="province" name="province_id" class="w-full border p-2 rounded-lg">
            <option value="">-- จังหวัด --</option>
            @foreach($provinces as $p)
                <option value="{{ $p->province_id }}"
                    data-zone="{{ $p->zone_code ?? '' }}"
                    {{ $p->province_id == $school->province_id ? 'selected' : '' }}>
                    {{ $p->name_th }}
                </option>
            @endforeach
        </select>

        {{-- อำเภอ --}}
        <select id="district" name="district_id" class="w-full border p-2 rounded-lg">
            <option value="">-- เลือกอำเภอ --</option>
        </select>

        {{-- ตำบล --}}
        <select id="subdistrict" name="subdistrict_id" class="w-full border p-2 rounded-lg">
            <option value="">-- เลือกตำบล --</option>
        </select>

        {{-- zipcode --}}
        <input id="zipcode" class="w-full border p-2 rounded-lg bg-gray-100" readonly placeholder="รหัสไปรษณีย์">

        {{-- zone --}}
        <input id="zone" name="zone_code"
            value="{{ $school->zone_code }}"
            class="w-full border p-2 rounded-lg bg-gray-100"
            placeholder="โซน"
            readonly>

        <input name="website" value="{{ $school->website }}" class="w-full border p-2 rounded-lg" placeholder="Website">

        <input name="facebook" value="{{ $school->facebook }}" class="w-full border p-2 rounded-lg" placeholder="Facebook">

        <input name="phone" value="{{ $school->phone }}" class="w-full border p-2 rounded-lg" placeholder="เบอร์โทร">

        <input name="email" value="{{ $school->email }}" class="w-full border p-2 rounded-lg" placeholder="Email">

        <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
            💾 บันทึก
        </button>

    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const province = document.getElementById('province');
    const district = document.getElementById('district');
    const subdistrict = document.getElementById('subdistrict');
    const zipcode = document.getElementById('zipcode');
    const zone = document.getElementById('zone');

    const selectedDistrict = "{{ $school->district_id }}";
    const selectedSubdistrict = "{{ $school->subdistrict_id }}";

    // 🔥 set zone จากจังหวัด
   function setZone() {

        let provinceId = province.value;

        // หา option ที่ value ตรง
        let selected = province.querySelector(`option[value="${provinceId}"]`);

        if (selected && selected.dataset.zone) {
            zone.value = selected.dataset.zone;
        } else {
            zone.value = '';
        }
    }

    // โหลดอำเภอ
    function loadDistricts(provinceId, selected = null) {

        if (!provinceId) return;

        fetch(`/districts/${provinceId}`)
            .then(res => res.json())
            .then(data => {

                district.innerHTML = '<option value="">-- เลือกอำเภอ --</option>';

                data.forEach(d => {
                    let selectedAttr = (selected == d.district_id) ? 'selected' : '';
                    district.innerHTML += `<option value="${d.district_id}" ${selectedAttr}>${d.name_th}</option>`;
                });

                if (selected) {
                    loadSubdistricts(selected, selectedSubdistrict);
                }
            });
    }

    // โหลดตำบล
    function loadSubdistricts(districtId, selected = null) {

        if (!districtId) return;

        fetch(`/subdistricts/${districtId}`)
            .then(res => res.json())
            .then(data => {

                subdistrict.innerHTML = '<option value="">-- เลือกตำบล --</option>';

                data.forEach(s => {
                    let selectedAttr = (selected == s.subdistrict_id) ? 'selected' : '';
                    let zip = s.zipcode ?? s.zip_code ?? '';

                    subdistrict.innerHTML += `
                        <option value="${s.subdistrict_id}" data-zip="${zip}" ${selectedAttr}>
                            ${s.name_th}
                        </option>`;
                });

                if (selected) {
                    let selectedOption = subdistrict.querySelector('option[selected]');
                    if (selectedOption) {
                        zipcode.value = selectedOption.dataset.zip || '';
                    }
                }
            });
    }

    province.addEventListener('change', function () {
        loadDistricts(this.value);
        setZone();
        zipcode.value = '';
    });

    district.addEventListener('change', function () {
        loadSubdistricts(this.value);
        zipcode.value = '';
    });

    subdistrict.addEventListener('change', function () {
        let zip = this.options[this.selectedIndex].dataset.zip || '';
        zipcode.value = zip;
    });

    // โหลดตอน edit
    if (province.value) {
        loadDistricts(province.value, selectedDistrict);
        setZone();
    }

});
</script>

</body>
</html>