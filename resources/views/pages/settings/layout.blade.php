@props(['showSidebar' => true])

@if (! $showSidebar)
    <div class="settings-card">
        <div class="settings-card-heading"><flux:heading level="2">{{ $heading ?? '' }}</flux:heading><flux:subheading>{{ $subheading ?? '' }}</flux:subheading></div>
        <div class="settings-card-content">{{ $slot }}</div>
    </div>
@else
<div class="settings-grid">
    <aside class="dashboard-sidebar">
        <div class="dashboard-person"><span class="dashboard-avatar" aria-hidden="true">{{ auth()->user()->initials() }}</span><strong>{{ auth()->user()->name }}</strong><span>{{ auth()->user()->email }}</span></div>
        <nav aria-label="ตั้งค่าบัญชี">
            @foreach (['dashboard' => 'ภาพรวมบัญชี', 'profile.edit' => 'ข้อมูลส่วนตัว', 'security.edit' => 'ความปลอดภัย', 'appearance.edit' => 'การแสดงผล'] as $routeName => $label)
                <a href="{{ route($routeName) }}" @if(request()->routeIs($routeName)) aria-current="page" @endif>{{ $label }} <span aria-hidden="true">→</span></a>
            @endforeach
        </nav>
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" data-test="logout-button">ออกจากระบบ <span aria-hidden="true">↗</span></button></form>
    </aside>
    <div class="settings-card">
        <div class="settings-card-heading"><flux:heading level="2">{{ $heading ?? '' }}</flux:heading><flux:subheading>{{ $subheading ?? '' }}</flux:subheading></div>
        <div class="settings-card-content">{{ $slot }}</div>
    </div>
</div>
@endif
