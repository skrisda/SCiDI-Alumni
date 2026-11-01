<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'หน้าแรก') | SciDI-TSU AA</title>
    <meta name="description" content="เชื่อมสายใยศิษย์เก่าคณะวิทยาศาสตร์และนวัตกรรมดิจิทัล มหาวิทยาลัยทักษิณ ร่วมติดตามข่าวสาร กิจกรรม และสนับสนุนสมาคม">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mitr:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('appearance')
</head>
<body class="alumni-landing">
<a class="skip-link" href="#main">ข้ามไปยังเนื้อหา</a>
<div class="topline"><div class="landing-container">สายใยวิทยาศาสตร์ สู่สังคมแห่งนวัตกรรม <div class="font-controls" role="group" aria-label="ปรับขนาดตัวอักษร"><button type="button" data-font-action="decrease" aria-label="ลดขนาดตัวอักษร">ก−</button><button type="button" data-font-action="reset" aria-label="คืนขนาดตัวอักษรปกติ">ก</button><button type="button" data-font-action="increase" aria-label="เพิ่มขนาดตัวอักษร">ก+</button><output data-font-status aria-live="polite" aria-atomic="true">100%</output></div><span>มหาวิทยาลัยทักษิณ · THAKSIN UNIVERSITY</span></div></div>
<header class="landing-header">
    <div class="landing-container header-inner">
        <a class="brand" href="{{ route('home') }}" aria-label="SciDI-TSU AA หน้าแรก"><img src="{{ asset('images/logo.png') }}" alt="ตราสมาคมศิษย์เก่า SciDI-TSU AA" width="64" height="64"><span><strong>สศ.วท.มทษ.</strong><small>สมาคมศิษย์เก่าคณะวิทยาศาสตร์และนวัตกรรมดิจิทัล มหาวิทยาลัยทักษิณ</small></span></a>
        <nav class="desktop-nav" aria-label="เมนูหลัก">@foreach (['home' => 'หน้าแรก', 'about' => 'รู้จักสมาคม', 'news' => 'ข่าวสาร', 'events' => 'กิจกรรม', 'gallery' => 'คลังภาพ', 'membership' => 'สมาชิก', 'support' => 'บริจาค', 'documents' => 'เอกสาร'] as $routeName => $label)
<a href="{{ route($routeName) }}" @if(request()->routeIs($routeName)) aria-current="page" @endif>{{ $label }}</a>
@endforeach</nav>
        @auth
            <a class="button button-blue header-login" href="{{ route('dashboard') }}">พื้นที่สมาชิก ↗</a>
        @else
            <a class="button button-outline header-login" href="{{ route('login') }}">เข้าสู่ระบบ ↗</a>
        @endauth
        <details class="mobile-nav"><summary>เมนู ☰</summary><nav aria-label="เมนูมือถือ">@foreach (['home' => 'หน้าแรก', 'about' => 'รู้จักสมาคม', 'news' => 'ข่าวสาร', 'events' => 'กิจกรรม', 'gallery' => 'คลังภาพ', 'membership' => 'สมาชิก', 'support' => 'บริจาค', 'documents' => 'เอกสาร'] as $routeName => $label)
<a href="{{ route($routeName) }}" @if(request()->routeIs($routeName)) aria-current="page" @endif>{{ $label }}</a>
@endforeach</nav></details>
    </div>
</header>
<main id="main">
    @yield('content')
</main>
<footer class="landing-footer"><div class="landing-container footer-grid"><div><a class="brand" href="{{ route('home') }}"><img src="{{ asset('images/logo.png') }}" alt="SciDI-TSU AA" width="64" height="64" loading="lazy"><span><strong>SciDI-TSU AA</strong><small>เชื่อมสายใย สร้างโอกาส ส่งต่ออนาคต</small></span></a><p>สมาคมศิษย์เก่าคณะวิทยาศาสตร์และนวัตกรรมดิจิทัล<br>มหาวิทยาลัยทักษิณ</p></div><div><h3>ติดต่อสมาคม</h3><p>ห้อง SC1505 อาคารวิทยาศาสตร์ 1<br>มหาวิทยาลัยทักษิณ วิทยาเขตพัทลุง<br>222 หมู่ 2 ต.บ้านพร้าว อ.ป่าพะยอม จ.พัทลุง 93210</p></div><div><h3>สำรวจเว็บไซต์</h3><a href="{{ route('about') }}">เกี่ยวกับสมาคม</a><a href="{{ route('membership') }}">ประเภทสมาชิก</a><a href="{{ route('documents') }}">ข้อบังคับและเอกสาร</a></div></div><div class="landing-container footer-bottom"><span>© {{ date('Y') }} SciDI-TSU AA. สงวนลิขสิทธิ์</span><span>Faculty of Science and Digital Innovation Alumni Association</span></div></footer>
</body>
</html>
