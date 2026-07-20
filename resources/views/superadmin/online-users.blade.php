@extends('layouts.superadmin')

@section('title', 'ผู้ใช้ออนไลน์')

@section('content')

<div class="p-6">

    <div class="bg-white rounded-xl shadow p-6">

        <h1 class="text-2xl font-bold mb-6">
            👨‍💻 ผู้ใช้ออนไลน์
        </h1>

        @include('components.dashboard.online-users')

    </div>

</div>

@endsection