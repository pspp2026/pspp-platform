@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')

<div class="min-h-screen bg-gray-100">

    {{-- Top Navigation --}}
    @include('partials.navbar')

    <div class="flex">

        {{-- Left Sidebar --}}
        @include('partials.sidebar')

        {{-- Main Content --}}
        <main class="flex-1 overflow-x-hidden">

            <div class="p-6 space-y-6">

                {{-- Hero Section --}}
                @include('components.dashboard.hero')

                {{-- Statistics --}}
                @include('components.dashboard.statistics')

                {{-- Charts --}}
                @include('components.dashboard.charts')

                {{-- Quick Actions + Activities --}}
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                    <div>
                        @include('components.dashboard.quick-actions')
                    </div>

                    <div class="xl:col-span-2">
                        @include('components.dashboard.activities')
                    </div>

                </div>

                {{-- System Status --}}
                @include('components.dashboard.system-status')

            </div>

        </main>

    </div>

</div>

@endsection