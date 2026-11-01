<?php

use App\AdminRole;
use App\Models\MembershipApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('admin pages reject guests unverified accounts and ordinary members', function () {
    $application = MembershipApplication::factory()->create();
    $urls = [route('admin.dashboard'), route('admin.memberships.index'), route('admin.memberships.show', $application), route('admin.memberships.slip', $application)];
    foreach ($urls as $url) {
        $this->get($url)->assertRedirect(route('login'));
    }
    $this->actingAs(User::factory()->unverified()->withRole(AdminRole::Admin)->create());
    foreach ($urls as $url) {
        $this->get($url)->assertRedirect(route('verification.notice'));
    }
    $this->actingAs(User::factory()->create());
    foreach ($urls as $url) {
        $this->get($url)->assertForbidden();
    }
});

test('staff can search filter paginate and inspect applications', function (AdminRole $role) {
    $staff = User::factory()->withRole($role)->create();
    $application = MembershipApplication::factory()->create(['first_name' => 'สมชาย', 'last_name' => 'ทดสอบ']);
    MembershipApplication::factory()->count(16)->create(['first_name' => 'คนอื่น', 'last_name' => 'รายการอื่น']);
    $this->actingAs($staff)->get(route('admin.dashboard'))->assertOk()->assertSee($role->label());
    $this->get(route('admin.memberships.index'))->assertOk()->assertViewHas('applications', fn ($applications): bool => $applications->count() === 15 && $applications->total() === 17);
    $this->get(route('admin.memberships.index', ['search' => 'สมชาย', 'type' => 'ordinary', 'payment' => 'awaiting_payment']))->assertOk()->assertSee('สมชาย')->assertDontSee('คนอื่น');
    $this->get(route('admin.memberships.index', ['type' => 'associate']))->assertOk()->assertSee('ไม่พบคำขอ');
    $this->get(route('admin.memberships.show', $application))->assertOk()->assertSee('สมชาย')->assertDontSee($application->national_id);
})->with([AdminRole::Admin, AdminRole::Registrar, AdminRole::Treasurer]);

test('registrars see applicant details but cannot download payment evidence', function () {
    $application = MembershipApplication::factory()->create(['address' => 'ที่อยู่เฉพาะสำหรับนายทะเบียน', 'slip_path' => 'private/slip.pdf']);
    $this->actingAs(User::factory()->withRole(AdminRole::Registrar)->create())
        ->get(route('admin.memberships.show', $application))->assertOk()->assertSee($application->address)->assertDontSee('ดาวน์โหลดหลักฐานการโอน');
    $this->get(route('admin.memberships.slip', $application))->assertForbidden();
});

test('treasurers see payment details without exposing applicant identity and contact data', function () {
    Storage::fake('local');
    $application = MembershipApplication::factory()->create(['address' => 'ที่อยู่ห้ามแสดงฝ่ายการเงิน', 'slip_path' => 'private/slip.pdf']);
    Storage::disk('local')->put($application->slip_path, 'private evidence');
    $this->actingAs(User::factory()->withRole(AdminRole::Treasurer)->create())
        ->get(route('admin.memberships.show', $application))->assertOk()->assertSee('ข้อมูลสำหรับฝ่ายการเงิน')->assertDontSee($application->address)->assertDontSee($application->email)->assertDontSee($application->phone);
    $this->get(route('admin.memberships.slip', $application))->assertOk()->assertDownload();
});

test('admins can download evidence while missing files return not found', function () {
    Storage::fake('local');
    $application = MembershipApplication::factory()->create(['slip_path' => 'private/slip.pdf']);
    $this->actingAs(User::factory()->withRole(AdminRole::Admin)->create());
    $this->get(route('admin.memberships.slip', $application))->assertNotFound();
    Storage::disk('local')->put($application->slip_path, 'evidence');
    $this->get(route('admin.memberships.slip', $application))->assertOk()->assertDownload();
});

test('grant command assigns roles to existing verified accounts and records changes', function () {
    $user = User::factory()->create();
    $this->artisan('admin:grant', ['email' => $user->email])->assertSuccessful();
    expect($user->hasRole(AdminRole::Admin))->toBeTrue();
    $this->assertDatabaseHas('admin_role_changes', ['user_id' => $user->id, 'previous_role' => 'member', 'new_role' => 'admin']);
    $this->artisan('admin:grant', ['email' => $user->email])->assertSuccessful();
    $this->assertDatabaseCount('admin_role_changes', 1);
});

test('grant command rejects missing accounts invalid roles and unverified accounts', function () {
    $user = User::factory()->unverified()->create();
    $this->artisan('admin:grant', ['email' => 'missing@example.com'])->assertFailed();
    $this->artisan('admin:grant', ['email' => $user->email, '--role' => 'invalid'])->assertFailed();
    $this->artisan('admin:grant', ['email' => $user->email])->assertFailed();
    expect($user->roles()->count())->toBe(0);
    $this->assertDatabaseCount('admin_role_changes', 0);
});

test('last administrator cannot be demoted but other staff roles can be revoked', function () {
    $admin = User::factory()->withRole(AdminRole::Admin)->create();
    $this->artisan('admin:grant', ['email' => $admin->email, '--role' => 'member'])->assertFailed();
    expect($admin->hasRole(AdminRole::Admin))->toBeTrue();
    User::factory()->withRole(AdminRole::Admin)->create();
    $this->artisan('admin:grant', ['email' => $admin->email, '--role' => 'registrar'])->assertSuccessful();
    expect($admin->hasRole(AdminRole::Registrar))->toBeTrue()->and($admin->hasRole(AdminRole::Admin))->toBeTrue();
    $this->artisan('admin:grant', ['email' => $admin->email, '--role' => 'member'])->assertSuccessful();
    expect($admin->roles()->count())->toBe(0);
});

test('public registration cannot grant administrative privileges', function () {
    $this->post(route('register.store'), ['name' => 'Public User', 'email' => 'public@example.com', 'password' => 'password', 'password_confirmation' => 'password', 'admin_role' => 'admin'])->assertSessionHasNoErrors();
    expect(User::where('email', 'public@example.com')->firstOrFail()->roles()->count())->toBe(0);
    $this->assertDatabaseCount('admin_role_changes', 0);
});

test('one account can hold multiple roles and access both applicant and financial information', function () {
    $user = User::factory()->withRole(AdminRole::Registrar, AdminRole::Treasurer)->create();
    $application = MembershipApplication::factory()->create();
    $this->actingAs($user)->get(route('admin.memberships.show', $application))->assertOk()->assertSee('ข้อมูลสำหรับนายทะเบียน')->assertSee('ข้อมูลสำหรับฝ่ายการเงิน');
    $this->get(route('admin.dashboard'))->assertOk()->assertSee('นายทะเบียน / เหรัญญิก');
    expect($user->roles()->count())->toBe(2);
});

test('granting additional roles keeps existing roles and prevents duplicate assignments', function () {
    $user = User::factory()->withRole(AdminRole::Admin)->create();
    $this->artisan('admin:grant', ['email' => $user->email, '--role' => 'registrar'])->assertSuccessful();
    $this->artisan('admin:grant', ['email' => $user->email, '--role' => 'treasurer'])->assertSuccessful();
    $this->artisan('admin:grant', ['email' => $user->email, '--role' => 'treasurer'])->assertSuccessful();
    expect($user->roles()->count())->toBe(3);
    $this->assertDatabaseCount('admin_role_changes', 2);
});

test('revoking a single role preserves other permissions and records the revocation', function () {
    $user = User::factory()->withRole(AdminRole::Registrar, AdminRole::Treasurer)->create();
    $this->artisan('admin:grant', ['email' => $user->email, '--role' => 'treasurer', '--revoke' => true])->assertSuccessful();
    expect($user->hasRole(AdminRole::Registrar))->toBeTrue()->and($user->hasRole(AdminRole::Treasurer))->toBeFalse();
    $this->assertDatabaseHas('admin_role_changes', ['user_id' => $user->id, 'previous_role' => 'treasurer', 'action' => 'revoked']);
    $this->actingAs($user)->get(route('admin.memberships.slip', MembershipApplication::factory()->create()))->assertForbidden();
});

test('revoking the last administrator explicitly fails even with additional roles', function () {
    $user = User::factory()->withRole(AdminRole::Admin, AdminRole::Registrar)->create();
    $this->artisan('admin:grant', ['email' => $user->email, '--role' => 'admin', '--revoke' => true])->assertFailed();
    expect($user->roles()->count())->toBe(2);
});

test('role migration preserves legacy administrators and removes the legacy column', function () {
    $migration = require database_path('migrations/2026_11_01_123002_move_admin_roles_to_roles_table.php');
    $migration->down();
    $user = User::factory()->create();
    DB::table('users')->where('id', $user->id)->update(['admin_role' => 'admin']);
    $migration->up();
    expect($user->hasRole(AdminRole::Admin))->toBeTrue()
        ->and(Schema::hasColumn('users', 'admin_role'))->toBeFalse();
});
