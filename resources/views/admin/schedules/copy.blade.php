@extends('layouts.admin')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-emerald-600 to-green-700 text-white rounded-3xl shadow-lg p-8 mb-8">

        <h1 class="text-3xl font-bold mb-2">
            📋 คัดลอกตารางสอน
        </h1>

        <p class="text-emerald-100">
            คัดลอกตารางสอนจากภาคเรียนหนึ่งไปยังอีกภาคเรียนหนึ่ง
        </p>

    </div>

    {{-- Validation --}}
    @if(session('success'))

        <div class="bg-green-100 border border-green-300 text-green-700 rounded-lg p-4 mb-6">
            {{ session('success') }}
        </div>

    @endif

    @if(session('error'))

        <div class="bg-red-100 border border-red-300 text-red-700 rounded-lg p-4 mb-6">
            {{ session('error') }}
        </div>

    @endif

    @if($errors->any())

        <div class="bg-red-100 border border-red-300 text-red-700 rounded-lg p-4 mb-6">

            <ul class="list-disc ml-5">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <div class="bg-white rounded-2xl shadow-lg p-8">

        <form
            action="{{ route('admin.schedules.copy.store') }}"
            method="POST"
        >

            @csrf

            {{-- ต้นทาง --}}
            <div class="mb-6">

                <label class="block font-semibold mb-2">
                    📚 ภาคเรียนต้นทาง
                </label>

                <select
                    name="from_term"
                    class="w-full border rounded-lg p-3"
                    required
                >

                    <option value="">
                        -- เลือกภาคเรียน --
                    </option>

                    @foreach($terms as $term)

                        <option value="{{ $term->id }}">

                            ปี {{ $term->academic_year }}
                            /
                            ภาคเรียน {{ $term->semester }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- ปลายทาง --}}
            <div class="mb-8">

                <label class="block font-semibold mb-2">
                    📦 ภาคเรียนปลายทาง
                </label>

                <select
                    name="to_term"
                    class="w-full border rounded-lg p-3"
                    required
                >

                    <option value="">
                        -- เลือกภาคเรียน --
                    </option>

                    @foreach($terms as $term)

                        <option value="{{ $term->id }}">

                            ปี {{ $term->academic_year }}
                            /
                            ภาคเรียน {{ $term->semester }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="bg-yellow-50 border border-yellow-300 rounded-xl p-4 mb-8">

                <div class="font-semibold text-yellow-700 mb-2">
                    ⚠️ คำเตือน
                </div>

                <ul class="list-disc ml-5 text-sm text-yellow-700">

                    <li>ระบบจะคัดลอกตารางสอนทั้งหมด</li>

                    <li>หากภาคเรียนปลายทางมีข้อมูลอยู่แล้ว ระบบจะไม่คัดลอกซ้ำ</li>

                    <li>โปรดตรวจสอบภาคเรียนต้นทางและปลายทางให้ถูกต้องก่อนคัดลอก</li>

                </ul>

            </div>

            <div class="flex justify-end gap-3">

                <a
                    href="{{ route('admin.schedules.index') }}"
                    class="px-5 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-xl"
                >
                    ← กลับ
                </a>

                <button
                    type="submit"
                    onclick="return confirm('ยืนยันการคัดลอกตารางสอน ?')"
                    class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold"
                >
                    📋 คัดลอกตารางสอน
                </button>

            </div>

        </form>

    </div>

</div>

@endsection