@extends('layouts.superadmin')

@section('title','Super Admin Dashboard')

@section('content')

{{-- Hero --}}
@include('components.dashboard.hero')

{{-- Statistics --}}
@include('components.dashboard.statistics')

{{-- Charts --}}
@include('components.dashboard.charts')

{{-- Evaluation Matrix --}}
@include('components.dashboard.evaluation-matrix')

{{-- Evaluation Summary --}}
@include('components.dashboard.evaluation-summary')

{{-- Online Users --}}
@include('components.dashboard.online-users')

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <div>
        @include('components.dashboard.quick-actions')
    </div>

    <div class="xl:col-span-2">
        @include('components.dashboard.activities')
    </div>

</div>

@include('components.dashboard.system-status')

@endsection