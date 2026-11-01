<x-layouts::app :title="__('ภาพรวมผู้ดูแล')">
    <div class="dashboard-content">
        <section class="dashboard-welcome"><span class="registration-kicker">SCIDI–TSU AA / ADMINISTRATION</span><h2>ภาพรวมผู้ดูแล</h2><p>สิทธิ์ปัจจุบัน: {{ auth()->user()->roles()->pluck('label')->implode(' / ') }}</p><a class="text-link" href="{{ route('admin.memberships.index') }}">เปิดรายการคำขอสมัครสมาชิก →</a></section>
        <div class="dashboard-status-grid">
            <section class="dashboard-panel"><h2>คำขอทั้งหมด</h2><strong class="admin-total">{{ number_format($totalApplications) }}</strong><p>จำนวนคำขอในระบบ</p></section>
            <section class="dashboard-panel"><h2>รอพิจารณา</h2><strong class="admin-total">{{ number_format($pendingApplications) }}</strong><p>รอตรวจสอบและดำเนินการตามขั้นตอนของสมาคม</p></section>
            @can('view-membership-payments')<section class="dashboard-panel"><h2>หลักฐานรอตรวจสอบ</h2><strong class="admin-total">{{ number_format($pendingPayments) }}</strong><a class="text-link" href="{{ route('admin.memberships.index', ['payment' => 'pending_verification']) }}">ดูรายการ →</a></section>@endcan
        </div>
        <div class="application-notice">ระบบระยะแรกเปิดให้ค้นหาและตรวจข้อมูลเท่านั้น การบันทึกผลรับรอง มติ และยืนยันการชำระเงินจะเพิ่มในขั้นถัดไป</div>
    </div>
</x-layouts::app>
