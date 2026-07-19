@extends('layouts.app')

@section('title', 'ผู้ใช้ออนไลน์')

@section('content')

<div class="min-h-screen bg-gray-100">

    {{-- Top Navigation --}}
    @include('partials.navbar')

    <div class="flex">

        {{-- Sidebar --}}
        @include('partials.sidebar')

        <main class="flex-1 p-6">

            <div class="bg-white rounded-xl shadow p-6">

                <h1 class="text-2xl font-bold mb-6">
                    👨‍💻 ผู้ใช้ออนไลน์
                </h1>

                {{-- ตารางผู้ใช้ออนไลน์ --}}
                @include('components.dashboard.online-users')

            </div>

        </main>

    </div>

</div>

@endsection