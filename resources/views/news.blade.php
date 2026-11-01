@extends('layouts.public')
@section('title', 'ข่าวสาร')
@section('content')
<div class="landing-container breadcrumbs"><a href="{{ route('home') }}">หน้าแรก</a><span>/</span><span>ข่าวสาร</span></div>
    @include('partials.public-news')
@endsection
