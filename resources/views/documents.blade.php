@extends('layouts.public')
@section('title', 'เอกสารและรายงานประชุม')
@section('content')
<div class="landing-container breadcrumbs"><a href="{{ route('home') }}">หน้าแรก</a><span>/</span><span>เอกสารและรายงานประชุม</span></div>
    <section id="documents" class="section landing-container"><div class="section-heading"><div><p class="eyebrow">05 / DOCUMENTS</p><h1 class="page-heading">เอกสารและความโปร่งใส</h1></div></div><div class="document-list"><a href="{{ asset('documents/ข้อบังคับสมาคมศิษย์เก่า SCIDI TSU AA ฉบับที่ 1-2568.docx') }}" download><span class="document-icon">DOCX</span><div><h3>ข้อบังคับสมาคมศิษย์เก่า SciDI-TSU AA</h3><p>ฉบับที่ 1/2568 · ดาวน์โหลดเอกสาร</p></div><span class="document-arrow">↓</span></a><div class="document-pending"><span class="document-icon">PDF</span><div><h3>รายงานการประชุมสมาคม</h3><p>ยังไม่มีรายงานการประชุมที่เผยแพร่</p></div><span class="status-note">เร็ว ๆ นี้</span></div></div></section>
@endsection
