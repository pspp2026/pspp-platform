@extends('layouts.app')

@section('content')

<div class="flex min-h-screen">

    @include('teacher.sidebar')

    <div class="flex-1 bg-gray-100">

        {{-- TOPBAR --}}
        <div class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold">โปรไฟล์ของฉัน</h1>

            <div class="flex items-center gap-3">
                <img  src="{{ auth()->user()->profile_image 
                        ? asset('storage/' . auth()->user()->profile_image) 
                        : asset('images/default-user.png') }}"
                    class="w-10 h-10 rounded-full">
                <span>{{ auth()->user()->name }}</span>
            </div>
        </div>

        <div class="p-6">

            <div class="max-w-4xl mx-auto bg-white p-6 rounded-2xl shadow-md">

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('teacher.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- รูป --}}
                    <div class="mb-4 text-center">

                        <img id="preview"
                            src="{{ auth()->user()->profile_image 
                                ? asset('storage/' . auth()->user()->profile_image) 
                                : 'https://i.pravatar.cc/150' }}"
                            class="w-32 h-32 mx-auto rounded-full object-cover border mb-2">

                        <input type="file" 
                            name="profile_image" 
                            id="profileInput"
                            class="border p-2 rounded-lg w-full">

                        <input type="hidden" name="cropped_image" id="croppedImage">

                    </div>

                    {{-- SCHOOL --}}
                    <div class="mb-4">
                        <label class="text-sm font-medium">โรงเรียน</label>
                        <select name="school_id" class="w-full border p-2 rounded-lg" required>
                            <option value="">-- เลือกโรงเรียน --</option>
                            @foreach($schools as $s)
                                <option value="{{ $s->id }}"
                                    @selected(auth()->user()->school_id == $s->id)>
                                    {{ $s->school_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{--แสดงรหัสโรงเรียน--}}
                    <div class="mb-4">
                        <label class="text-sm font-medium">รหัสโรงเรียน</label>
                        <input 
                            value="{{ auth()->user()->school->school_code ?? '' }}"
                            class="w-full border p-2 rounded-lg bg-gray-100"
                            readonly
                        >
                    </div>

                    {{-- BASIC --}}
                    <div class="grid md:grid-cols-2 gap-4">
                        <input name="name" value="{{ auth()->user()->name }}" class="border p-2 rounded-lg" placeholder="ชื่อ">
                        <input name="email" value="{{ auth()->user()->email }}" class="border p-2 rounded-lg" placeholder="Email">
                    </div>

                    {{-- EXTRA --}}
                    <div class="grid md:grid-cols-3 gap-4 mt-4">
                        <input name="id_card" value="{{ auth()->user()->id_card }}" class="border p-2 rounded-lg" placeholder="เลขบัตรประชาชน">
                        <input name="name_th" value="{{ auth()->user()->name_th }}" class="border p-2 rounded-lg" placeholder="ชื่อ-สกุล (ไทย)">
                        <input name="name_en" value="{{ auth()->user()->name_en }}" class="border p-2 rounded-lg" placeholder="ชื่อ-สกุล (EN)">
                    </div>
                    {{--รหัสครู--}}
                    <div>
                        <label>รหัสครู</label>
                        <input 
                            name="external_code"
                            value="{{ old('external_code', auth()->user()->external_code) }}"
                            class="border p-2 rounded-lg w-full"
                            placeholder="กรอกรหัสที่หน่วยงานกำหนด"
                        >
                    </div>

                    {{--Phone--}}
                    <div class="grid md:grid-cols-2 gap-4 mt-4">
                        <input 
                            name="phone"
                            value="{{ old('phone', auth()->user()->phone) }}"
                            class="border p-2 rounded-lg"
                            placeholder="เบอร์โทรศัพท์"
                        >
                    </div>
                    {{-- ADDRESS --}}
                    <div class="mt-4">
                        <label class="block text-sm font-medium mb-1">ที่อยู่</label>
                        <textarea name="address1" class="w-full border p-2 rounded-lg"
                            placeholder="บ้านเลขที่ / ถนน">{{ auth()->user()->address1 }}</textarea>
                    </div>

                    <div class="mt-2">
                        <label class="block text-sm font-medium mb-1">รายละเอียดเพิ่มเติม</label>
                        <textarea name="address2" class="w-full border p-2 rounded-lg"
                            placeholder="อาคาร / ชั้น / ห้อง">{{ auth()->user()->address2 }}</textarea>
                    </div>

                    {{-- LOCATION --}}
                    <div class="grid md:grid-cols-3 gap-4 mt-4">
                        <select id="province" name="province_id" class="border p-2 rounded-lg">
                            <option value="">จังหวัด</option>
                            @foreach($provinces as $p)
                                <option value="{{ $p->province_id }}"
                                    @selected(auth()->user()->province_id == $p->province_id)>
                                    {{ $p->name_th }}
                                </option>
                            @endforeach
                        </select>

                        <select id="district" name="district_id" class="border p-2 rounded-lg"></select>

                        <select id="subdistrict" name="subdistrict_id" class="border p-2 rounded-lg"></select>
                    </div>

                    {{-- ZIPCODE --}}
                    <input id="zipcode" name="zipcode"
                        value="{{ auth()->user()->zipcode }}"
                        class="border p-2 mt-4 w-full rounded-lg bg-gray-100"
                        readonly>

                    {{-- PASSWORD --}}
                    <div class="mt-4">
                        <label class="block text-sm font-medium mb-1">รหัสผ่านใหม่</label>
                        <div class="grid md:grid-cols-2 gap-4">
                            <input type="password" name="password" class="w-full border p-2 rounded-lg">
                            <input type="password" name="password_confirmation" class="w-full border p-2 rounded-lg">
                        </div>
                    </div>

                    {{-- BUTTON --}}
                    <div class="flex justify-between mt-4">
                        <a href="{{ route('teacher.dashboard') }}" class="text-sm text-gray-500">← กลับ</a>
                        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                            💾 บันทึก
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

{{-- JS --}}
<script>

const province = document.getElementById('province');
const district = document.getElementById('district');
const subdistrict = document.getElementById('subdistrict');
const zipcode = document.getElementById('zipcode');

// จังหวัด → อำเภอ
province.addEventListener('change', async function() {
    let res = await fetch('/districts/' + this.value);
    let data = await res.json();

    let html = '<option value="">อำเภอ</option>';
    data.forEach(d => {
        html += `<option value="${d.district_id}">${d.name_th}</option>`;
    });
    district.innerHTML = html;
    subdistrict.innerHTML = '<option value="">ตำบล</option>';
    zipcode.value = '';
});

// อำเภอ → ตำบล
district.addEventListener('change', async function() {
    let res = await fetch('/subdistricts/' + this.value);
    let data = await res.json();

    let html = '<option value="">ตำบล</option>';
    data.forEach(s => {
        html += `<option value="${s.subdistrict_id}" data-zip="${s.zipcode}">
                    ${s.name_th}
                 </option>`;
    });
    subdistrict.innerHTML = html;
});

// zipcode
subdistrict.addEventListener('change', function() {
    zipcode.value = this.options[this.selectedIndex]?.dataset.zip || '';
});

// โหลดค่าเดิม
window.addEventListener('DOMContentLoaded', async () => {
    let province_id = "{{ auth()->user()->province_id }}";
    let district_id = "{{ auth()->user()->district_id }}";
    let subdistrict_id = "{{ auth()->user()->subdistrict_id }}";

    if (province_id) {
        let res = await fetch('/districts/' + province_id);
        let data = await res.json();

        let html = '<option value="">อำเภอ</option>';
        data.forEach(d => {
            html += `<option value="${d.district_id}" ${d.district_id == district_id ? 'selected' : ''}>
                        ${d.name_th}
                     </option>`;
        });
        district.innerHTML = html;
    }

    if (district_id) {
        let res = await fetch('/subdistricts/' + district_id);
        let data = await res.json();

        let html = '<option value="">ตำบล</option>';
        data.forEach(s => {
            html += `<option value="${s.subdistrict_id}" data-zip="${s.zipcode}"
                        ${s.subdistrict_id == subdistrict_id ? 'selected' : ''}>
                        ${s.name_th}
                     </option>`;
        });
        subdistrict.innerHTML = html;
    }

    if (subdistrict_id) {
        let selected = subdistrict.options[subdistrict.selectedIndex];
        zipcode.value = selected?.dataset.zip || '';
    }
});


// =========================
// 🖼️ preview + crop
// =========================
const profileInput = document.getElementById('profileInput');
const preview = document.getElementById('preview');
const cropImage = document.getElementById('cropImage');
const cropBtn = document.getElementById('cropBtn');

let cropper;

// เลือกรูป
if (profileInput) {
    profileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];

        if (!file) return;

        if (!file.type.startsWith('image/')) {
            alert('กรุณาเลือกไฟล์รูปภาพ');
            return;
        }

        const reader = new FileReader();

        reader.onload = function(e) {

            cropImage.src = e.target.result;
            cropImage.classList.remove('hidden');

            if (cropper) {
                cropper.destroy();
            }

            cropper = new Cropper(cropImage, {
                aspectRatio: 1,
                viewMode: 1,
            });

            cropBtn.classList.remove('hidden');
        }

        reader.readAsDataURL(file);
    });
}

// กด crop
if (cropBtn) {
    cropBtn.addEventListener('click', function() {

        const canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300,
        });

        preview.src = canvas.toDataURL();

        const base64 = canvas.toDataURL('image/jpeg');

        document.getElementById('croppedImage').value = base64;
    });
}

</script>

@endsection