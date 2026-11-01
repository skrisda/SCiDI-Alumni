<x-layouts::app :title="__('รายละเอียดคำขอสมาชิก')">
    <section class="settings-card">
        <div class="settings-card-heading"><h2>คำขอเลขที่ {{ str_pad((string) $application->id, 6, '0', STR_PAD_LEFT) }}</h2><a class="text-link" href="{{ route('admin.memberships.index') }}">← กลับรายการคำขอ</a></div>
        <div class="application-notice"><strong>รอพิจารณาจากสมาคม</strong><p>หน้านี้ใช้ตรวจสอบข้อมูล ยังไม่มีการอนุมัติสมาชิกหรือยืนยันยอดชำระ</p></div>
        <dl class="application-summary"><div><dt>ผู้สมัคร</dt><dd>{{ $application->prefix }} {{ $application->first_name }} {{ $application->last_name }}</dd></div><div><dt>ประเภทสมาชิก</dt><dd>{{ \App\Models\MembershipApplication::TYPES[$application->membership_type] }}</dd></div><div><dt>วันที่ยื่นคำขอ</dt><dd>{{ $application->created_at?->timezone('Asia/Bangkok')->format('d/m/Y H:i') }}</dd></div></dl>
        @can('view-membership-details')
            <h3>ข้อมูลสำหรับนายทะเบียน</h3>
            @if($application->ordinary_category)<p>ประเภทสมาชิกสามัญ: {{ \App\Models\MembershipApplication::ORDINARY_CATEGORIES[$application->ordinary_category] }}</p>@endif
            <dl class="application-summary"><div><dt>ชื่อเล่น</dt><dd>{{ $application->nickname ?: 'ไม่ระบุ' }}</dd></div><div><dt>รหัสนิสิต</dt><dd>{{ $application->student_code ?: 'ไม่ระบุ' }}</dd></div><div><dt>หลักสูตร</dt><dd>{{ $application->program ?: 'ไม่ระบุ' }}</dd></div><div><dt>เลขประจำตัวประชาชน (ปิดบัง)</dt><dd>•••••••••{{ substr($application->national_id, -4) }}</dd></div><div><dt>อีเมล</dt><dd>{{ $application->email }}</dd></div><div><dt>เบอร์โทรศัพท์</dt><dd>{{ $application->phone }}</dd></div><div><dt>ที่อยู่</dt><dd class="admin-multiline">{{ $application->address }}</dd></div></dl>
        @endcan
        @can('view-membership-payments')
            <h3>ข้อมูลสำหรับฝ่ายการเงิน</h3><dl class="application-summary"><div><dt>ค่าบำรุงตลอดชีพ</dt><dd>{{ $application->fee_amount !== null ? number_format($application->fee_amount).' บาท' : 'รอสมาคมยืนยันอัตราค่าบำรุง' }}</dd></div><div><dt>หลักฐานการโอน</dt><dd>{{ $application->slip_path ? 'ได้รับหลักฐานแล้ว รอตรวจสอบ' : 'ยังไม่มีหลักฐาน' }}</dd></div></dl>
            @if($application->slip_path)<a class="button button-blue" href="{{ route('admin.memberships.slip', $application) }}">ดาวน์โหลดหลักฐานการโอน ↓</a>@endif
        @endcan
    </section>
</x-layouts::app>
