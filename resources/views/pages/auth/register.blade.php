@extends('layouts.public')

@section('title', 'สร้างบัญชีผู้ใช้')

@section('content')
    <section class="registration-section">
        <div class="landing-container">
            <nav class="registration-breadcrumb" aria-label="เส้นทางนำทาง"><a href="{{ route('home') }}">หน้าแรก</a><span aria-hidden="true">/</span><span aria-current="page">สร้างบัญชีผู้ใช้</span></nav>
            <div class="registration-grid">
                <aside class="registration-intro">
                    <p class="eyebrow">SCIDI–TSU AA / GET CONNECTED</p>
                    <h2>เริ่มต้นสายสัมพันธ์<br>ที่เติบโตไปด้วยกัน</h2>
                    <p class="registration-lead">สร้างบัญชีเพื่อเริ่มใช้งานเว็บไซต์สมาคมศิษย์เก่า<br>คณะวิทยาศาสตร์และนวัตกรรมดิจิทัล<br>มหาวิทยาลัยทักษิณ</p>
                    <div class="registration-steps">
                        <div><span>01</span><div><h3>สร้างบัญชีของคุณ</h3><p>ใช้ชื่อและอีเมลสำหรับเข้าสู่ระบบ</p></div></div>
                        <div><span>02</span><div><h3>ยืนยันอีเมล</h3><p>ตรวจสอบกล่องจดหมายเพื่อยืนยันบัญชี</p></div></div>
                        <div><span>03</span><div><h3>เข้าสู่พื้นที่ผู้ใช้</h3><p>จัดการบัญชีและข้อมูลส่วนตัวของคุณ</p></div></div>
                    </div>
                    <div class="registration-notice"><strong>บัญชีผู้ใช้และสมาชิกสมาคม</strong><p>การสร้างบัญชียังไม่ถือว่าได้รับอนุมัติเป็นสมาชิกสมาคม หลังยืนยันอีเมล คุณสามารถยื่นคำขอสมัครสมาชิกสมาคมจากพื้นที่ผู้ใช้ได้</p><a href="{{ route('membership') }}">ดูประเภทสมาชิกและค่าบำรุง <span aria-hidden="true">→</span></a></div>
                </aside>
                <div class="registration-card">
                    <div class="registration-card-heading"><span class="registration-kicker">CREATE YOUR ACCOUNT</span><h1>สร้างบัญชีผู้ใช้</h1><p>กรอกข้อมูลด้านล่างเพื่อเริ่มต้นใช้งาน<br>ช่องที่มีเครื่องหมาย <span aria-hidden="true">*</span> จำเป็นต้องกรอก</p></div>
                    @if (session('status'))
                        <p class="registration-status" role="status">{{ session('status') }}</p>
                    @endif
                    @if ($errors->any())
                        <div class="registration-errors" role="alert"><strong>ยังไม่สามารถสร้างบัญชีได้</strong><p>กรุณาตรวจสอบและแก้ไขข้อมูลที่ระบุด้านล่าง</p></div>
                    @endif
                    <form method="POST" action="{{ route('register.store') }}" class="registration-form">
                        @csrf
                        <div class="registration-field"><label for="register-name">ชื่อ–นามสกุล <span aria-hidden="true">*</span></label><input id="register-name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="กรอกชื่อและนามสกุล" @error('name') aria-invalid="true" aria-describedby="register-name-error" @enderror>@error('name')<p id="register-name-error" class="field-error">{{ $message }}</p>@enderror</div>
                        <div class="registration-field"><label for="register-email">อีเมล <span aria-hidden="true">*</span></label><input id="register-email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" placeholder="name@example.com" aria-describedby="register-email-help @error('email') register-email-error @enderror" @error('email') aria-invalid="true" @enderror><p id="register-email-help" class="field-hint">ใช้อีเมลนี้สำหรับเข้าสู่ระบบและยืนยันบัญชี</p>@error('email')<p id="register-email-error" class="field-error">{{ $message }}</p>@enderror</div>
                        <div class="registration-field"><label for="register-password">รหัสผ่าน <span aria-hidden="true">*</span></label><input id="register-password" name="password" type="password" required autocomplete="new-password" placeholder="กำหนดรหัสผ่านของคุณ" @error('password') aria-invalid="true" aria-describedby="register-password-error" @enderror>@error('password')<p id="register-password-error" class="field-error">{{ $message }}</p>@enderror</div>
                        <div class="registration-field"><label for="register-password-confirmation">ยืนยันรหัสผ่าน <span aria-hidden="true">*</span></label><input id="register-password-confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="กรอกรหัสผ่านอีกครั้ง" @error('password_confirmation') aria-invalid="true" aria-describedby="register-password-confirmation-error" @enderror>@error('password_confirmation')<p id="register-password-confirmation-error" class="field-error">{{ $message }}</p>@enderror</div>
                        <button type="submit" class="button button-blue registration-submit" data-test="register-user-button">สร้างบัญชีผู้ใช้ <span aria-hidden="true">↗</span></button>
                    </form>
                    <div class="registration-login">มีบัญชีผู้ใช้อยู่แล้ว? <a href="{{ route('login') }}">เข้าสู่ระบบ <span aria-hidden="true">→</span></a></div>
                </div>
            </div>
        </div>
    </section>
@endsection
