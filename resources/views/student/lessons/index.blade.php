@extends('layouts.app')

@section('content')

<div class="flex min-h-screen bg-gray-100">

   @include('student.sidebar')

    {{-- ================= CONTENT ================= --}}
    <div class="flex-1">

        {{-- Topbar --}}
        <div class="bg-white shadow px-6 py-4 flex justify-between items-center">

            <div>
                <h1 class="text-2xl font-bold">
                    📚 บทเรียน
                </h1>

                <p class="text-gray-500">
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

        <div class="p-6">

            {{-- Progress --}}
            <div class="bg-white rounded-xl shadow p-6 mb-6">

                <div class="flex justify-between mb-2">

                    <h2 class="font-semibold text-lg">
                        📊 ความคืบหน้าการเรียน
                    </h2>

                    <span class="font-bold text-indigo-700">
                        {{ $percent }}%
                    </span>

                </div>

                <div class="w-full bg-gray-200 rounded-full h-4">

                    <div
                        class="bg-green-500 h-4 rounded-full"
                        style="width: {{ $percent }}%">
                    </div>

                </div>

            </div>

            {{-- Summary --}}
            <div class="grid md:grid-cols-3 gap-5 mb-6">

                <div class="bg-white rounded-xl shadow p-5">

                    <div class="text-gray-500">
                        บทเรียนทั้งหมด
                    </div>

                    <div class="text-3xl font-bold mt-2">
                        {{ $lessons->count() }}
                    </div>

                </div>

                <div class="bg-white rounded-xl shadow p-5">

                    <div class="text-gray-500">
                        เรียนแล้ว
                    </div>

                    <div class="text-3xl font-bold text-green-600 mt-2">
                        {{ count($completedLessons) }}
                    </div>

                </div>

                <div class="bg-white rounded-xl shadow p-5">

                    <div class="text-gray-500">
                        คงเหลือ
                    </div>

                    <div class="text-3xl font-bold text-red-500 mt-2">
                        {{ $lessons->count() - count($completedLessons) }}
                    </div>

                </div>

            </div>

            {{-- Lesson List --}}
            <div class="bg-white rounded-xl shadow">

                <div class="px-6 py-4 border-b">

                    <h2 class="text-lg font-semibold">
                        รายการบทเรียน
                    </h2>

                </div>

                @forelse($lessons as $lesson)

                    <div class="flex justify-between items-center px-6 py-4 border-b">

                        <div>

                            <h3 class="font-semibold">
                                {{ $lesson->title }}
                            </h3>

                            @if(!empty($lesson->description))
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $lesson->description }}
                                </p>
                            @endif

                        </div>

                        <div>

                            @if(in_array($lesson->id, $completedLessons))

                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                    ✔ เรียนแล้ว
                                </span>

                            @else

                                <button
                                    onclick="markRead({{ $lesson->id }})"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">

                                    📘 เรียนบทนี้

                                </button>

                            @endif

                        </div>

                    </div>

                @empty

                    <div class="p-8 text-center text-gray-500">

                        ยังไม่มีบทเรียน

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

<script>
function markRead(lessonId) {

    fetch(`/lesson/${lessonId}/read`, {

        method: 'POST',

        headers: {

            'X-CSRF-TOKEN': '{{ csrf_token() }}',

            'Content-Type': 'application/json'

        }

    })
    .then(response => response.json())
    .then(data => {

        if (data.success) {

            location.reload();

        }

    });

}
</script>

@endsection