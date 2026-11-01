@extends('layouts.public')
@section('title', 'หน้าแรก')
@section('content')
    <section class="hero">
        <div class="landing-container hero-grid">
            <div class="hero-copy"><p class="eyebrow"><span></span> ONCE A SciDI, ALWAYS A SciDI</p><h1>เชื่อมสายใยศิษย์เก่า<br>สร้างโอกาส<br><em>แห่งอนาคต<span>.</span></em></h1><p class="hero-description">พื้นที่ของคนวิทยาศาสตร์และนวัตกรรมดิจิทัล<br>ร่วมสานสัมพันธ์ แบ่งปันความรู้ และเติบโตไปด้วยกัน<br>กับสมาคมศิษย์เก่า มหาวิทยาลัยทักษิณ</p><div class="button-row"><a class="button button-blue" href="{{ route('membership') }}">ร่วมเป็นสมาชิก <span>↗</span></a><a class="text-link" href="{{ route('about') }}">รู้จักสมาคมของเรา <span>→</span></a></div><div class="hero-note"><span class="sun-mark">✳</span><span>ต่างรุ่น ต่างเส้นทาง<br><strong>ความผูกพันเดียวกัน</strong></span></div></div>
            <div class="hero-art"><div class="orbit orbit-one"></div><div class="orbit orbit-two"></div><span class="art-label">SCIENCE · DIGITAL · CONNECTION</span><div class="logo-stage"><img src="{{ asset('images/logo.png') }}" alt="โลโก้สมาคม รูปวงโคจรอะตอม ดอกทานตะวัน และขวดวิทยาศาสตร์" width="440" height="440" fetchpriority="high"></div><div class="art-caption"><span class="caption-dot"></span><span>จุดเริ่มต้นของมิตรภาพ<br><strong>ที่ไม่สิ้นสุดเมื่อเรียนจบ</strong></span><span class="caption-arrow">↗</span></div><span class="art-star">✳</span></div>
        </div>
    </section>
    <div class="values-strip"><div class="landing-container"><span>สายใยแห่งความผูกพัน</span><i>✳</i><span>เครือข่ายแห่งโอกาส</span><i>✳</i><span>พลังแห่งการแบ่งปัน</span><i>✳</i><span>SciDI COMMUNITY</span></div></div>

    @include('partials.public-news')
    @include('partials.public-events')
@endsection
