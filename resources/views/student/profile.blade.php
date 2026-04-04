@extends('layouts.app')

@section('content')

<div class="flex min-h-screen bg-gray-100">

    @include('student.sidebar')

    <div class="flex-1">

        {{-- HEADER --}}
        <div class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold">👤 โปรไฟล์นักเรียน</h1>

            <div class="flex items-center gap-3">
                <img src="{{ $user->profile_image 
                        ? asset('storage/'.$user->profile_image) 
                        : 'https://ui-avatars.com/api/?name='.$user->name }}"
                     class="w-10 h-10 rounded-full">

                <span class="font-medium">
                    {{ $user->student->full_name ?? $user->name }}
                </span>
            </div>
        </div>

        <div class="p-6 max-w-5xl mx-auto">

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- CARD --}}
                <div class="bg-white rounded-2xl shadow p-6 space-y-6">

                    {{-- PROFILE --}}
                    <div class="text-center">
                        <img src="{{ $user->profile_image 
                                ? asset('storage/'.$user->profile_image) 
                                : 'https://ui-avatars.com/api/?name='.$user->name }}"
                             class="w-28 h-28 rounded-full mx-auto mb-3 border">

                        <input type="file" name="profile_image"
                               class="text-sm">
                    </div>

                    {{-- ACCOUNT --}}
                    <div>
                        <h2 class="font-semibold text-gray-700 mb-3">ข้อมูลบัญชี</h2>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-600">ชื่อเข้าระบบ</label>
                                <input name="name" value="{{ $user->name }}"
                                       class="w-full border p-2 rounded-lg">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">อีเมล</label>
                                <input name="email" value="{{ $user->email }}"
                                       class="w-full border p-2 rounded-lg">
                            </div>
                        </div>
                    </div>

                    {{-- STUDENT --}}
                    <div>
                        <h2 class="font-semibold text-gray-700 mb-3">ข้อมูลนักเรียน</h2>

                        <div class="grid md:grid-cols-2 gap-4">

                            <div>
                                <label class="text-sm">ชื่อ-นามสกุล</label>
                                <input value="{{ $user->student->full_name ?? '-' }}"
                                       class="w-full border p-2 rounded-lg bg-gray-100" readonly>
                            </div>

                            <div>
                                <label class="text-sm">โรงเรียน</label>
                                <input value="{{ $user->student->school->school_name ?? '-' }}"
                                       class="w-full border p-2 rounded-lg bg-gray-100" readonly>
                            </div>

                            <div>
                                <label class="text-sm">วัด</label>
                                <input value="{{ $user->student->temple->temple_name ?? '-' }}"
                                       class="w-full border p-2 rounded-lg bg-gray-100" readonly>
                            </div>

                            <div>
                                <label class="text-sm">รหัสนักเรียน</label>
                                <input value="{{ $user->student->student_code ?? '-' }}"
                                       class="w-full border p-2 rounded-lg bg-gray-100" readonly>
                            </div>

                            <div>
                                <label class="text-sm">เลขบัตรประชาชน</label>
                                <input value="{{ $user->student->id_card ?? '-' }}"
                                       class="w-full border p-2 rounded-lg bg-gray-100" readonly>
                            </div>

                        </div>
                    </div>

                    {{-- CLASS --}}
                    @php
                        $enroll = optional($user->student)->enrollments->sortByDesc('academic_year')->first();
                    @endphp

                    <div>
                        <h2 class="font-semibold text-gray-700 mb-3">ข้อมูลการศึกษา</h2>

                        <div class="grid md:grid-cols-3 gap-4">
                            <input value="ม.{{ $enroll->grade_level ?? '-' }}" class="border p-2 rounded-lg bg-gray-100" readonly>
                            <input value="เทอม {{ $enroll->semester ?? '-' }}" class="border p-2 rounded-lg bg-gray-100" readonly>
                            <input value="ปี {{ $enroll->academic_year ?? '-' }}" class="border p-2 rounded-lg bg-gray-100" readonly>
                        </div>
                    </div>

                    {{-- CONTACT --}}
                    <div>
                        <h2 class="font-semibold text-gray-700 mb-3">ข้อมูลติดต่อ</h2>

                        <input name="phone" value="{{ $user->phone }}"
                               class="w-full border p-2 rounded-lg"
                               placeholder="เบอร์โทร">
                    </div>

                    {{-- ADDRESS --}}
                    <div>
                        <h2 class="font-semibold text-gray-700 mb-3">ที่อยู่</h2>

                        <textarea name="address1" class="w-full border p-2 rounded-lg mb-2"
                                  placeholder="บ้านเลขที่">{{ $user->address1 }}</textarea>

                        <textarea name="address2" class="w-full border p-2 rounded-lg mb-2"
                                  placeholder="รายละเอียดเพิ่มเติม">{{ $user->address2 }}</textarea>

                        <div class="grid md:grid-cols-3 gap-4">
                            <select id="province" name="province_id" class="border p-2 rounded-lg">
                                <option value="">จังหวัด</option>
                                @foreach($provinces->sortBy('name_th') as $p)
                                    <option value="{{ $p->province_id }}">{{ $p->name_th }}</option>
                                @endforeach
                            </select>

                            <select id="district" name="district_id" class="border p-2 rounded-lg"></select>
                            <select id="subdistrict" name="subdistrict_id" class="border p-2 rounded-lg"></select>
                        </div>

                        <input id="zipcode" name="zipcode"
                               value="{{ $user->zipcode }}"
                               class="w-full mt-3 border p-2 rounded-lg bg-gray-100"
                               readonly>
                    </div>

                    {{-- PASSWORD --}}
                    <div>
                        <h2 class="font-semibold text-gray-700 mb-3">เปลี่ยนรหัสผ่าน</h2>

                        <div class="grid md:grid-cols-2 gap-4">
                            <input type="password" name="password" class="border p-2 rounded-lg">
                            <input type="password" name="password_confirmation" class="border p-2 rounded-lg">
                        </div>
                    </div>

                    {{-- BUTTON --}}
                    <div class="flex justify-between pt-4">
                        <a href="/" class="text-gray-500">🏠 HOME</a>

                        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                            💾 บันทึก
                        </button>
                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- AJAX --}}
<script>
document.getElementById('province').addEventListener('change', function() {
    fetch('/districts/' + this.value)
    .then(res => res.json())
    .then(data => {
        let d = document.getElementById('district');
        d.innerHTML = '<option>อำเภอ</option>';
        data.forEach(i => d.innerHTML += `<option value="${i.district_id}">${i.name_th}</option>`);
    });
});

document.getElementById('district').addEventListener('change', function() {
    fetch('/subdistricts/' + this.value)
    .then(res => res.json())
    .then(data => {
        let s = document.getElementById('subdistrict');
        s.innerHTML = '<option>ตำบล</option>';
        data.forEach(i => s.innerHTML += `<option value="${i.subdistrict_id}" data-zip="${i.zipcode}">${i.name_th}</option>`);
    });
});

document.getElementById('subdistrict').addEventListener('change', function() {
    let zip = this.options[this.selectedIndex].dataset.zip;
    document.getElementById('zipcode').value = zip || '';
});
</script>

@endsection