@extends('layouts.app')

@section('title', 'แบบประเมิน PSPP Platform')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                📋 แบบประเมิน PSPP Platform
            </h1>

            <p class="text-gray-500 mt-1">
                แบบประเมินความเหมาะสมของรูปแบบการใช้เทคโนโลยีเพื่อการศึกษา
            </p>
        </div>

    </div>

    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if($surveys->count())

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="min-w-full">

            <thead class="bg-gray-100">

                <tr>

                    <th class="px-6 py-3 text-left">
                        ชื่อแบบประเมิน
                    </th>

                    <th class="px-6 py-3 text-center">
                        สถานะ
                    </th>

                    <th class="px-6 py-3 text-center">
                        เริ่ม
                    </th>

                    <th class="px-6 py-3 text-center">
                        สิ้นสุด
                    </th>

                    <th class="px-6 py-3 text-center">
                        ดำเนินการ
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach($surveys as $survey)

                    <tr class="border-t hover:bg-gray-50">

                        <td class="px-6 py-4">

                            <div class="font-semibold text-gray-800">

                                {{ $survey->title }}

                            </div>

                            <div class="text-sm text-gray-500">

                                {{ $survey->description }}

                            </div>

                        </td>

                        <td class="text-center">

                            @if($survey->status=='published')

                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">

                                    เปิดใช้งาน

                                </span>

                            @elseif($survey->status=='draft')

                                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm">

                                    Draft

                                </span>

                            @else

                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm">

                                    ปิด

                                </span>

                            @endif

                        </td>

                        <td class="text-center text-sm">

                            {{ optional($survey->start_at)->format('d/m/Y') }}

                        </td>

                        <td class="text-center text-sm">

                            {{ optional($survey->end_at)->format('d/m/Y') }}

                        </td>

                        <td class="text-center">

                            <a href="{{ route('survey.show',$survey) }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">

                                เริ่มตอบแบบประเมิน

                            </a>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <div class="mt-6">

        {{ $surveys->links() }}

    </div>

    @else

    <div class="bg-white rounded-xl shadow p-10 text-center">

        <div class="text-6xl mb-4">

            📋

        </div>

        <h2 class="text-2xl font-semibold mb-2">

            ยังไม่มีแบบประเมิน

        </h2>

        <p class="text-gray-500">

            ขณะนี้ยังไม่มีแบบประเมินที่เปิดให้ตอบ

        </p>

    </div>

    @endif

</div>

@endsection