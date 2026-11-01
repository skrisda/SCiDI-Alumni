<x-layouts::app :title="__('พื้นที่ผู้ใช้')">
                <div class="dashboard-content">
                    <section class="dashboard-welcome"><span class="registration-kicker">CONNECTED THROUGH SCIDI</span><h2>สายสัมพันธ์เดียวกัน<br>โอกาสที่ก้าวไปด้วยกัน</h2><p>จัดการบัญชีของคุณ และติดตามความเคลื่อนไหวจากสมาคม</p><a class="text-link" href="{{ route('profile.edit') }}">แก้ไขข้อมูลส่วนตัว <span aria-hidden="true">→</span></a></section>
                    <div class="dashboard-status-grid">
                        <section class="dashboard-panel"><p class="eyebrow">ACCOUNT STATUS</p><h2>สถานะบัญชีผู้ใช้</h2><span class="dashboard-badge">{{ auth()->user()->hasVerifiedEmail() ? 'ยืนยันอีเมลแล้ว' : 'ยังไม่ยืนยันอีเมล' }}</span><p>อีเมลสำหรับเข้าสู่ระบบ</p><strong class="dashboard-email">{{ auth()->user()->email }}</strong></section>
                        <section class="dashboard-panel"><p class="eyebrow">ASSOCIATION MEMBERSHIP</p><h2>สมาชิกสมาคม</h2><span class="dashboard-badge dashboard-badge-neutral">ยื่นคำขอได้แล้ว</span><p>บัญชีผู้ใช้แยกจากสมาชิกภาพสมาคม การสร้างบัญชียังไม่ถือว่าได้รับอนุมัติเป็นสมาชิก</p><a class="text-link" href="{{ route('membership.application') }}">สมัคร / ดูคำขอของฉัน <span aria-hidden="true">→</span></a></section>
                    </div>
                    <section class="dashboard-panel dashboard-shortcuts"><div class="section-heading"><div><p class="eyebrow">EXPLORE THE COMMUNITY</p><h2>เชื่อมต่อกับสมาคม</h2></div></div><div class="dashboard-shortcut-grid"><a href="{{ route('news') }}"><span>01 / NEWS</span><strong>ข่าวประชาสัมพันธ์ ↗</strong><p>ติดตามข่าวสารและประกาศ</p></a><a href="{{ route('events') }}"><span>02 / EVENTS</span><strong>กิจกรรมสมาคม ↗</strong><p>พบปะและแลกเปลี่ยนประสบการณ์</p></a><a href="{{ route('documents') }}"><span>03 / DOCUMENTS</span><strong>เอกสารสมาคม ↗</strong><p>ข้อบังคับและรายงานประชุม</p></a></div></section>
                </div>
</x-layouts::app>
