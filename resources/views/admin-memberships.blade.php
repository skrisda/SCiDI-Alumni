<x-layouts::app :title="__('คำขอสมัครสมาชิก')">
    <section class="settings-card admin-applications">
        <div class="settings-card-heading"><h2>คำขอสมัครสมาชิก</h2><p class="body-copy">ค้นหาผู้สมัครและตรวจสอบข้อมูลตามสิทธิ์ของคุณ</p></div>
        @if($errors->any())<div class="application-errors" role="alert">{{ $errors->first() }}</div>@endif
        <form method="GET" action="{{ route('admin.memberships.index') }}" class="registration-form admin-filters">
            <div class="registration-field"><label for="search">ชื่อ / นามสกุล</label><input id="search" name="search" value="{{ request('search') }}" maxlength="100" placeholder="ค้นหาผู้สมัคร"></div>
            <div class="registration-field"><label for="type">ประเภทสมาชิก</label><select id="type" name="type"><option value="">ทุกประเภท</option>@foreach(\App\Models\MembershipApplication::TYPES as $value => $label)<option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>@endforeach</select></div>
            <div class="registration-field"><label for="payment">สถานะหลักฐาน</label><select id="payment" name="payment"><option value="">ทุกสถานะ</option>@foreach(['awaiting_payment' => 'ยังไม่มีหลักฐาน', 'pending_verification' => 'รอตรวจสอบ', 'awaiting_fee_confirmation' => 'รอยืนยันค่าบำรุง'] as $value => $label)<option value="{{ $value }}" @selected(request('payment') === $value)>{{ $label }}</option>@endforeach</select></div>
            <div class="button-row"><button class="button button-blue" type="submit">ค้นหา</button><a class="text-link" href="{{ route('admin.memberships.index') }}">ล้างตัวกรอง</a></div>
        </form>
        <p class="field-hint">พบ {{ number_format($applications->total()) }} รายการ</p>
        <div class="admin-table-wrap"><table class="admin-table"><thead><tr><th scope="col">เลขคำขอ</th><th scope="col">ผู้สมัคร</th><th scope="col">ประเภท</th><th scope="col">หลักฐาน</th><th scope="col">การดำเนินการ</th></tr></thead><tbody>
            @forelse($applications as $application)<tr><td>{{ str_pad((string) $application->id, 6, '0', STR_PAD_LEFT) }}</td><td>{{ $application->prefix }} {{ $application->first_name }} {{ $application->last_name }}</td><td>{{ \App\Models\MembershipApplication::TYPES[$application->membership_type] }}</td><td>{{ match($application->payment_status) { 'pending_verification' => 'รอตรวจสอบ', 'awaiting_fee_confirmation' => 'รอยืนยันค่าบำรุง', default => 'ยังไม่มีหลักฐาน' } }}</td><td><a class="text-link" href="{{ route('admin.memberships.show', $application) }}" aria-label="ดูคำขอของ {{ $application->first_name }} {{ $application->last_name }}">ดูรายละเอียด →</a></td></tr>
            @empty<tr><td colspan="5">ไม่พบคำขอที่ตรงกับเงื่อนไข</td></tr>@endforelse
        </tbody></table></div>
        <div class="admin-pagination">{{ $applications->links() }}</div>
    </section>
</x-layouts::app>
