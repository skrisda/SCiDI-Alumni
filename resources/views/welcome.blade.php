@extends('layouts.public')
@section('title', 'หน้าแรก')
@section('content')
    <section class="hero corporate-hero">
        <div class="landing-container hero-grid">
            <div class="hero-copy">
                <p class="eyebrow">SCIDI–TSU ALUMNI ASSOCIATION</p>
                <h1>เครือข่ายแห่งความรู้<br>สายสัมพันธ์<span class="blue-text">ที่ยั่งยืน</span></h1>
                <p class="corporate-intro">สมาคมศิษย์เก่าคณะวิทยาศาสตร์และนวัตกรรมดิจิทัล<br>มหาวิทยาลัยทักษิณ</p>
                <p class="hero-description">เชื่อมโยงศิษย์เก่าทุกรุ่น ส่งต่อประสบการณ์ และร่วมสร้างโอกาส<br>เพื่อขับเคลื่อนคณะ ชุมชน และสังคมไปข้างหน้าด้วยกัน</p>
                <div class="button-row">
                    <a class="button button-blue" href="{{ route('membership') }}">ร่วมเป็นสมาชิก <span aria-hidden="true">↗</span></a>
                    <a class="button button-outline" href="{{ route('about') }}">รู้จักสมาคม</a>
                </div>
                <div class="hero-affiliation"><span></span> SCIENCE & DIGITAL INNOVATION · THAKSIN UNIVERSITY</div>
            </div>
            <div class="corporate-identity">
                <span class="identity-kicker">OUR IDENTITY</span>
                <img src="{{ asset('images/logo.png') }}" alt="ตราสมาคมศิษย์เก่า SciDI-TSU AA" width="360" height="360" fetchpriority="high">
                <div class="identity-caption"><strong>เชื่อมความสัมพันธ์ สร้างคุณค่าร่วมกัน</strong><span>Faculty of Science and Digital Innovation<br>Thaksin University Alumni Association</span></div>
            </div>
        </div>
    </section>
    <section class="corporate-services landing-container" aria-label="บริการและข้อมูลสมาคม">
        <a href="{{ route('membership') }}"><span class="service-number">01 / MEMBERSHIP</span><strong>เป็นส่วนหนึ่งของสมาคม <span aria-hidden="true">↗</span></strong><p>ประเภทสมาชิกและการเข้าร่วมเครือข่าย</p></a>
        <a href="{{ route('events') }}"><span class="service-number">02 / CONNECTION</span><strong>เชื่อมต่อผ่านกิจกรรม <span aria-hidden="true">↗</span></strong><p>พบปะ แลกเปลี่ยน และส่งต่อประสบการณ์</p></a>
        <a href="{{ route('documents') }}"><span class="service-number">03 / TRANSPARENCY</span><strong>ข้อมูลและเอกสารสมาคม <span aria-hidden="true">↗</span></strong><p>ข้อบังคับและรายงานการประชุม</p></a>
    </section>
    @include('partials.public-news')
    @include('partials.public-events')
@endsection
