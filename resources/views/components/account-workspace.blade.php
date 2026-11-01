    <section class="member-dashboard">
        <div class="landing-container">
            <div class="dashboard-heading"><div><p class="eyebrow">SCIDI–TSU AA / YOUR ACCOUNT</p><h1>พื้นที่ผู้ใช้</h1><p>ยินดีต้อนรับ คุณ{{ auth()->user()->name }}</p></div><a class="button button-outline" href="{{ route('home') }}">กลับหน้าเว็บไซต์ <span aria-hidden="true">↗</span></a></div>
            <div class="dashboard-grid">
    <aside class="dashboard-sidebar">
        <div class="dashboard-person"><span class="dashboard-avatar" aria-hidden="true">{{ auth()->user()->initials() }}</span><strong>{{ auth()->user()->name }}</strong><span>{{ auth()->user()->email }}</span></div>
        <p class="sidebar-group-heading" id="account-menu-heading">บัญชีผู้ใช้</p>
        <nav aria-labelledby="account-menu-heading">
            @foreach (['dashboard' => 'ภาพรวมบัญชี', 'membership.application' => 'สมัครสมาชิกสมาคม', 'profile.edit' => 'ข้อมูลส่วนตัว', 'security.edit' => 'ความปลอดภัย', 'appearance.edit' => 'การแสดงผล'] as $routeName => $label)
                <a href="{{ route($routeName) }}" @if(request()->routeIs($routeName)) aria-current="page" @endif>{{ $label }} <span aria-hidden="true">→</span></a>
            @endforeach
        </nav>
        @can('access-admin')
            <div class="sidebar-admin-group">
                <p class="sidebar-group-heading" id="admin-menu-heading">สำหรับผู้ดูแล</p>
                <nav aria-labelledby="admin-menu-heading">
                <a href="{{ route('admin.dashboard') }}" @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>ภาพรวมผู้ดูแล <span aria-hidden="true">→</span></a>
                <a href="{{ route('admin.memberships.index') }}" @if(request()->routeIs('admin.memberships.*')) aria-current="page" @endif>คำขอสมัครสมาชิก <span aria-hidden="true">→</span></a>
                </nav>
            </div>
        @endcan
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" data-test="logout-button">ออกจากระบบ <span aria-hidden="true">↗</span></button></form>
    </aside>
                <div class="workspace-content">{{ $slot }}</div>
            </div>
        </div>
    </section>
