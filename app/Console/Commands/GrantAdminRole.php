<?php

namespace App\Console\Commands;

use App\AdminRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GrantAdminRole extends Command
{
    protected $signature = 'admin:grant {email : อีเมลบัญชีที่มีอยู่} {--role=admin : admin, registrar, treasurer หรือ member เพื่อถอนทุกสิทธิ์} {--revoke : ถอนเฉพาะสิทธิ์ที่ระบุ}';

    protected $description = 'เพิ่มหรือถอนสิทธิ์ผู้ดูแลโดยคงสิทธิ์อื่นไว้ พร้อมบันทึกประวัติ';

    public function handle(): int
    {
        $role = AdminRole::tryFrom((string) $this->option('role'));
        if ($role === null || ($role === AdminRole::Member && $this->option('revoke'))) {
            $this->error('บทบาทไม่ถูกต้อง ใช้ admin, registrar, treasurer หรือ member เพื่อถอนทุกสิทธิ์');

            return self::FAILURE;
        }

        return DB::transaction(function () use ($role): int {
            $availableRoles = Role::orderBy('id')->lockForUpdate()->get();
            $user = User::where('email', mb_strtolower(trim((string) $this->argument('email'))))->lockForUpdate()->first();
            if ($user === null) {
                $this->error('ไม่พบบัญชีผู้ใช้ กรุณาสร้างบัญชีก่อน');

                return self::FAILURE;
            }
            $isRemoval = $role === AdminRole::Member || (bool) $this->option('revoke');
            if (! $isRemoval && ! $user->hasVerifiedEmail()) {
                $this->error('บัญชีนี้ยังไม่ยืนยันอีเมล กรุณายืนยันก่อนแต่งตั้ง');

                return self::FAILURE;
            }
            $selectedRole = $availableRoles->firstWhere('name', $role->value);
            if ($role !== AdminRole::Member && $selectedRole === null) {
                $this->error('ไม่พบสิทธิ์ในระบบ กรุณาตรวจสอบ migration');

                return self::FAILURE;
            }
            $currentRoles = $user->roles()->get();
            $hasSelectedRole = $currentRoles->contains('name', $role->value);
            if ((! $isRemoval && $hasSelectedRole) || ($isRemoval && $role !== AdminRole::Member && ! $hasSelectedRole) || ($role === AdminRole::Member && $currentRoles->isEmpty())) {
                $this->info('บัญชีนี้มีสิทธิ์ตรงตามที่ระบุอยู่แล้ว');

                return self::SUCCESS;
            }
            if ($isRemoval && ($role === AdminRole::Member || $role === AdminRole::Admin) && $user->hasRole(AdminRole::Admin)) {
                $adminRole = $availableRoles->firstWhere('name', AdminRole::Admin->value);
                if ($adminRole === null || $adminRole->users()->count() <= 1) {
                    $this->error('ไม่สามารถถอนสิทธิ์ผู้ดูแลระบบคนสุดท้ายได้ กรุณาแต่งตั้งผู้ดูแลคนอื่นก่อน');

                    return self::FAILURE;
                }
            }
            if ($role === AdminRole::Member) {
                $user->roles()->detach();
                foreach ($currentRoles as $currentRole) {
                    $this->recordChange($user, $currentRole->name, 'member', 'revoked');
                }
            } elseif ($isRemoval) {
                $user->roles()->detach($selectedRole->id);
                $this->recordChange($user, $role->value, 'member', 'revoked');
            } else {
                $user->roles()->syncWithoutDetaching([$selectedRole->id]);
                $this->recordChange($user, 'member', $role->value, 'granted');
            }
            $this->info(($isRemoval ? 'ถอนสิทธิ์เรียบร้อยแล้ว: ' : 'เพิ่มสิทธิ์ '.$role->label().' เรียบร้อยแล้ว: ').$user->email);

            return self::SUCCESS;
        });
    }

    private function recordChange(User $user, string $previousRole, string $newRole, string $action): void
    {
        DB::table('admin_role_changes')->insert([
            'user_id' => $user->id,
            'previous_role' => $previousRole,
            'new_role' => $newRole,
            'action' => $action,
            'source' => 'artisan',
            'created_at' => now(),
        ]);
    }
}
