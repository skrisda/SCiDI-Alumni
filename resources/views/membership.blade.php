@extends('layouts.public')
@section('title', 'สมาชิกสมาคม')
@section('content')
<div class="landing-container breadcrumbs"><a href="{{ route('home') }}">หน้าแรก</a><span>/</span><span>สมาชิกสมาคม</span></div>
    <section id="membership" class="membership-section"><div class="landing-container membership-grid"><div><p class="eyebrow">BE PART OF OUR COMMUNITY</p><h1 class="page-heading">สายสัมพันธ์ที่ดี<br>เริ่มต้นที่<span>การมีส่วนร่วม</span></h1><p>ร่วมเป็นส่วนหนึ่งของเครือข่ายศิษย์เก่า<br>และส่งต่อพลังดี ๆ ให้กับชุมชนของเรา</p><a class="button button-gold" href="{{ route('register') }}">สร้างบัญชีผู้ใช้ <span>↗</span></a><small class="membership-note">แบบสมัครสมาชิกสมาคมและการแนบสลิปอยู่ระหว่างพัฒนา</small></div><div class="membership-types"><div><span>01</span><h3>สมาชิกสามัญ</h3><p>ค่าบำรุงตลอดชีพ</p><strong>100 <small>บาท</small></strong></div><div><span>02</span><h3>สมาชิกวิสามัญ</h3><p>ค่าบำรุงตลอดชีพ</p><strong>300 <small>บาท</small></strong></div><p class="types-note">สมาคมมีสมาชิก 4 ประเภท รวมสมาชิกกิตติมศักดิ์และสมาชิกสมทบ การรับสมาชิกเป็นไปตามข้อบังคับสมาคม</p></div></div></section>
@endsection
