@extends('layouts.public')

@section('title', 'PSPP Platform | ระบบบริหารโรงเรียนพระปริยัติธรรม')

@section('content')

    {{-- ==========================================
        Hero Section
    =========================================== --}}
    @include('partials.public.hero')


    @include('partials.public.statistics')

    @include('partials.public.about')


    {{-- ==========================================
        Features
    =========================================== --}}
    @include('partials.public.features')


    {{-- ==========================================
        Core Modules
    =========================================== --}}
    @include('partials.public.modules')


    {{-- ==========================================
        Schools
    =========================================== --}}
    @include('partials.public.schools')

    @include('partials.public.cta')

    {{-- ==========================================
        Footer
    =========================================== --}}
    @include('partials.public.footer')

@endsection