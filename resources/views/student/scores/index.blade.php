@extends('layouts.app')

@section('content')

<div class="flex min-h-screen bg-gray-100">

    {{-- Sidebar --}}
    @include('student.sidebar')

    {{-- Main Content --}}
    <div class="flex-1">

        {{-- Topbar --}}
        <div class="bg-white shadow px-6 py-4 flex justify-between items-center">

            <div>
                <h1 class="text-2xl font-bold">
                    📊 คะแนนผลการเรียน
                </h1>

                <p class="text-sm text-gray-500">
                    ยินดีต้อนรับ {{ auth()->user()->display_name }}
                </p>
            </div>

            <div class="flex items-center gap-3">

                <img
                    src="{{ auth()->user()->profile_image
                        ? asset('storage/' . auth()->user()->profile_image)
                        : 'https://i.pravatar.cc/40' }}"
                    class="w-10 h-10 rounded-full border object-cover">

                <span class="font-medium">
                    {{ auth()->user()->name }}
                </span>

            </div>

        </div>

        {{-- Content --}}
        <div class="p-6">

            {{-- Student Info --}}
            <div class="bg-white rounded-xl shadow p-6 mb-6">

                <h2 class="text-lg font-semibold mb-4">
                    👨‍🎓 ข้อมูลนักเรียน
                </h2>

                <div class="grid md:grid-cols-2 gap-4">

                    <div>
                        <strong>รหัสนักเรียน :</strong>
                        {{ $student->student_code ?? '-' }}
                    </div>

                    <div>
                        <strong>ชื่อ :</strong>
                        {{ $student->full_name ?? auth()->user()->name }}
                    </div>

                </div>

            </div>

            {{-- Score Table --}}
            <div class="bg-white rounded-xl shadow">

                <div class="px-6 py-4 border-b">

                    <h2 class="text-lg font-semibold">
                        📋 ตารางคะแนน
                    </h2>

                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full text-sm">

                        <thead class="bg-gray-100">

                            <tr>

                                <th class="px-4 py-3 text-left">#</th>

                                <th class="px-4 py-3 text-left">
                                    วิชา
                                </th>

                                <th class="px-4 py-3 text-center">
                                    คะแนน
                                </th>

                                <th class="px-4 py-3 text-center">
                                    เกรด
                                </th>

                                <th class="px-4 py-3 text-center">
                                    ครูผู้สอน
                                </th>

                                <th class="px-4 py-3 text-center">
                                    ภาคเรียน
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                        @forelse($scores as $score)

                            <tr class="border-b hover:bg-gray-50">

                                <td class="px-4 py-3">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $score->schedule?->subject?->subject_name ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-center font-semibold">

                                    {{ number_format($score->total_score, 2) }}

                                </td>

                                <td class="px-4 py-3 text-center">

                                    {{ $score->grade?->grade ?? '-' }}

                                </td>

                                <td class="px-4 py-3 text-center">

                                    {{ $score->schedule?->teacher?->name ?? '-' }}

                                </td>

                                <td class="px-4 py-3 text-center">

                                        @if($score->schedule && $score->schedule->academicTerm)

                                            {{ $score->schedule->academicTerm->semester }}
                                            /
                                            {{ $score->schedule->academicTerm->academic_year }}

                                        @else

                                            -

                                        @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    class="text-center py-10 text-gray-500">

                                    ยังไม่มีข้อมูลคะแนน

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection