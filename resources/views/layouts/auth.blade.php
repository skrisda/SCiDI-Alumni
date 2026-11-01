@extends('layouts.public')

@section('title', $title ?? 'บัญชีผู้ใช้')

@section('content')
    <section class="registration-section corporate-auth">
        <div class="landing-container">
            <nav class="registration-breadcrumb" aria-label="เส้นทางนำทาง"><a href="{{ route('home') }}">หน้าแรก</a><span aria-hidden="true">/</span><span aria-current="page">{{ $title ?? 'บัญชีผู้ใช้' }}</span></nav>
            <div class="registration-grid">
                <aside class="registration-intro">
                    <p class="eyebrow">SCIDI–TSU AA / YOUR COMMUNITY</p>
                    <h2>เชื่อมต่อทุกโอกาส<br>ผ่านสายสัมพันธ์เดียวกัน</h2>
                    <p class="registration-lead">พื้นที่สำหรับชาววิทยาศาสตร์และนวัตกรรมดิจิทัล<br>มหาวิทยาลัยทักษิณ</p>
                    <div class="auth-identity"><img src="{{ asset('images/logo.png') }}" alt="ตราสมาคมศิษย์เก่า SciDI-TSU AA" width="200" height="200"><p>Faculty of Science and Digital Innovation<br>Thaksin University Alumni Association</p></div>
                    <a class="text-link" href="{{ route('about') }}">รู้จักสมาคมของเรา <span aria-hidden="true">→</span></a>
                </aside>
                <div class="registration-card auth-card">
                    <span class="registration-kicker">SCIDI–TSU AA / ACCOUNT</span>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </section>
    @fluxScripts
@endsection
