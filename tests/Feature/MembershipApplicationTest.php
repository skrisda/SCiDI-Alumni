<?php

use App\Models\MembershipApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function membershipApplicationData(): array
{
    return [
        'membership_type' => 'ordinary',
        'ordinary_category' => 'graduate',
        'prefix' => 'นาย',
        'first_name' => 'ทดสอบ',
        'last_name' => 'ระบบ',
        'program' => 'วิทยาการคอมพิวเตอร์',
        'national_id' => '1101700207030',
        'address' => '222 จังหวัดพัทลุง',
        'phone' => '0812345678',
        'acknowledged' => '1',
    ];
}

test('membership application requires a verified account', function () {
    $this->get(route('membership.application'))->assertRedirect(route('login'));
    $this->actingAs(User::factory()->unverified()->create())
        ->get(route('membership.application'))->assertRedirect(route('verification.notice'));
});

test('verified users can submit encrypted membership applications with server controlled fees', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get(route('membership.application'))->assertOk()->assertSee('สมัครสมาชิกสมาคม');
    $this->post(route('membership.application.store'), array_merge(membershipApplicationData(), [
        'fee_amount' => 0, 'status' => 'approved', 'email' => 'forged@example.com',
    ]))->assertSessionHasNoErrors()->assertRedirect(route('membership.application'));

    $application = MembershipApplication::sole();
    expect($application->fee_amount)->toBe(100)
        ->and($application->status)->toBe('pending_review')
        ->and($application->email)->toBe($user->email)
        ->and($application->national_id)->toBe('1101700207030')
        ->and(DB::table('membership_applications')->value('national_id'))->not->toBe('1101700207030');
    $this->get(route('membership.application'))->assertOk()->assertSee('รับคำขอสมัครแล้ว')->assertDontSee('1101700207030');
});

test('membership fees follow the selected type without guessing associate fees', function (string $type, ?int $fee) {
    $this->actingAs(User::factory()->create())->post(route('membership.application.store'), array_replace(membershipApplicationData(), ['membership_type' => $type]))->assertSessionHasNoErrors();
    expect(MembershipApplication::sole()->fee_amount)->toBe($fee);
})->with([['extraordinary', 300], ['associate', null]]);

test('invalid identities and invitation only types are rejected', function () {
    $this->actingAs(User::factory()->create())->post(route('membership.application.store'), array_replace(membershipApplicationData(), [
        'national_id' => '1101700207031', 'membership_type' => 'honorary',
    ]))->assertSessionHasErrors(['national_id', 'membership_type']);
    $this->assertDatabaseCount('membership_applications', 0);
});

test('each account and national identity can submit only one application', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->post(route('membership.application.store'), membershipApplicationData())->assertSessionHasNoErrors();
    $this->post(route('membership.application.store'), membershipApplicationData())->assertForbidden();
    $this->actingAs(User::factory()->create())->post(route('membership.application.store'), membershipApplicationData())->assertSessionHasErrors('national_id');
    $this->assertDatabaseCount('membership_applications', 1);
});

test('payment evidence is stored privately and never activates membership', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    $application = MembershipApplication::factory()->for($user)->create();
    $this->actingAs($user)->post(route('membership.slip.store'), [
        'slip' => UploadedFile::fake()->create('slip.pdf', 100, 'application/pdf'),
    ])->assertSessionHasNoErrors()->assertRedirect(route('membership.application'));
    $application->refresh();
    Storage::disk('local')->assertExists($application->slip_path);
    expect($application->payment_status)->toBe('pending_verification')->and($application->status)->toBe('pending_review');
    $this->get(route('membership.slip.download'))->assertOk();
    $this->post(route('membership.slip.store'), ['slip' => UploadedFile::fake()->create('again.pdf', 100, 'application/pdf')])->assertForbidden();
    $this->actingAs(User::factory()->create())->get(route('membership.slip.download'))->assertNotFound();
});

test('slips reject unsupported files and oversized files', function () {
    Storage::fake('local');
    $user = User::factory()->create();
    MembershipApplication::factory()->for($user)->create();
    $this->actingAs($user)->post(route('membership.slip.store'), ['slip' => UploadedFile::fake()->create('script.html', 10, 'text/html')])->assertSessionHasErrors('slip');
    $this->post(route('membership.slip.store'), ['slip' => UploadedFile::fake()->create('large.pdf', 5121, 'application/pdf')])->assertSessionHasErrors('slip');
    expect(Storage::disk('local')->allFiles())->toBeEmpty();
});

test('associate applications cannot submit payments before fees are confirmed', function () {
    $user = User::factory()->create();
    MembershipApplication::factory()->for($user)->create(['membership_type' => 'associate', 'fee_amount' => null]);
    $this->actingAs($user)->post(route('membership.slip.store'), ['slip' => UploadedFile::fake()->create('slip.pdf', 100, 'application/pdf')])->assertForbidden();
});

test('custom prefixes are required when other is selected and saved as the actual title', function () {
    $this->actingAs(User::factory()->create());
    $this->post(route('membership.application.store'), array_replace(membershipApplicationData(), ['prefix' => 'other']))->assertSessionHasErrors('prefix_other');
    $this->post(route('membership.application.store'), array_replace(membershipApplicationData(), ['prefix' => 'other', 'prefix_other' => 'ดร.']))->assertSessionHasNoErrors();
    expect(MembershipApplication::sole()->prefix)->toBe('ดร.');
});

test('predefined prefixes ignore custom input and unsupported selections are rejected', function () {
    $this->actingAs(User::factory()->create());
    $this->post(route('membership.application.store'), array_replace(membershipApplicationData(), ['prefix' => 'unsupported']))->assertSessionHasErrors('prefix');
    $this->post(route('membership.application.store'), array_replace(membershipApplicationData(), ['prefix' => 'นางสาว', 'prefix_other' => 'ignored']))->assertSessionHasNoErrors();
    expect(MembershipApplication::sole()->prefix)->toBe('นางสาว');
});

test('application form prefills account names while keeping multipart surnames', function () {
    $this->actingAs(User::factory()->create(['name' => '  สมชาย   นามสกุล เพิ่มเติม  ']))
        ->get(route('membership.application'))
        ->assertOk()
        ->assertSee('value="สมชาย"', false)
        ->assertSee('value="นามสกุล เพิ่มเติม"', false);
});

test('application form keeps corrected names after validation errors', function () {
    $this->actingAs(User::factory()->create(['name' => 'ชื่อเดิม นามสกุลเดิม']))
        ->withSession(['_old_input' => ['first_name' => 'ชื่อแก้ไข', 'last_name' => 'นามสกุลแก้ไข']])
        ->get(route('membership.application'))
        ->assertOk()
        ->assertSee('value="ชื่อแก้ไข"', false)
        ->assertSee('value="นามสกุลแก้ไข"', false);
});

test('single account names leave the surname empty for the applicant to complete', function () {
    $this->actingAs(User::factory()->create(['name' => 'สมชาย']))
        ->get(route('membership.application'))
        ->assertOk()
        ->assertViewHas('defaultFirstName', 'สมชาย')
        ->assertViewHas('defaultLastName', '');
});

test('ordinary employees can apply without academic fields and forged academic values are discarded', function (string $category) {
    $data = membershipApplicationData();
    $data['ordinary_category'] = $category;
    $data['student_code'] = 'should-not-be-saved';
    unset($data['program']);
    $this->actingAs(User::factory()->create())->post(route('membership.application.store'), $data)->assertSessionHasNoErrors();
    $application = MembershipApplication::sole();
    expect($application->ordinary_category)->toBe($category)->and($application->program)->toBeNull()->and($application->student_code)->toBeNull();
})->with(['lecturer', 'staff']);

test('ordinary graduates must provide a program and all ordinary applicants need a valid category', function () {
    $this->actingAs(User::factory()->create());
    $data = membershipApplicationData();
    unset($data['program']);
    $this->post(route('membership.application.store'), $data)->assertSessionHasErrors('program');
    $data = membershipApplicationData();
    $data['ordinary_category'] = 'invalid';
    $this->post(route('membership.application.store'), $data)->assertSessionHasErrors('ordinary_category');
    unset($data['ordinary_category']);
    $this->post(route('membership.application.store'), $data)->assertSessionHasErrors('ordinary_category');
});

test('non ordinary members cannot use employee category to bypass program requirements', function () {
    $this->actingAs(User::factory()->create());
    $data = membershipApplicationData();
    $data['membership_type'] = 'extraordinary';
    $data['ordinary_category'] = 'lecturer';
    unset($data['program']);
    $this->post(route('membership.application.store'), $data)->assertSessionHasErrors('program');
    $data['program'] = 'หลักสูตรทดสอบ';
    $this->post(route('membership.application.store'), $data)->assertSessionHasNoErrors();
    expect(MembershipApplication::sole()->ordinary_category)->toBeNull();
});

test('removed qualification field is not displayed or accepted from forged submissions', function () {
    $this->actingAs(User::factory()->create())->get(route('membership.application'))->assertOk()->assertDontSee('name="qualification"', false);
    $this->post(route('membership.application.store'), array_replace(membershipApplicationData(), ['qualification' => 'ข้อมูลที่ไม่ควรรับ']))->assertSessionHasNoErrors();
    expect(Schema::hasColumn('membership_applications', 'qualification'))->toBeFalse();
    expect(MembershipApplication::sole()->getAttributes())->not->toHaveKey('qualification');
});
