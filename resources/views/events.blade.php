@extends('layouts.public')
@section('title', 'กิจกรรม')
@section('content')
<div class="landing-container breadcrumbs"><a href="{{ route('home') }}">หน้าแรก</a><span>/</span><span>กิจกรรม</span></div>
    @include('partials.public-events')
@endsection
