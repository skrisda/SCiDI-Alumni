<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 20)->unique();
            $table->string('label');
            $table->timestamps();
        });
        Schema::create('role_user', function (Blueprint $table): void {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->primary(['role_id', 'user_id']);
            $table->index('user_id');
        });
        foreach (['admin' => 'ผู้ดูแลระบบ', 'registrar' => 'นายทะเบียน', 'treasurer' => 'เหรัญญิก'] as $name => $label) {
            $roleId = DB::table('roles')->insertGetId(['name' => $name, 'label' => $label, 'created_at' => now(), 'updated_at' => now()]);
            DB::table('users')->where('admin_role', $name)->orderBy('id')->chunkById(500, function ($users) use ($roleId): void {
                foreach ($users as $user) {
                    DB::table('role_user')->insert(['role_id' => $roleId, 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()]);
                }
            });
        }
        Schema::table('admin_role_changes', function (Blueprint $table): void {
            $table->string('action', 20)->default('replaced');
        });
        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex(['admin_role']);
        });
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('admin_role');
        });
    }

    public function down(): void
    {
        if (DB::table('role_user')->select('user_id')->groupBy('user_id')->havingRaw('COUNT(*) > 1')->exists()) {
            throw new LogicException('ไม่สามารถย้อนกลับเป็นสิทธิ์เดียวได้ เนื่องจากมีบัญชีที่มีหลายสิทธิ์');
        }
        Schema::table('users', function (Blueprint $table): void {
            $table->string('admin_role', 20)->default('member')->index();
        });
        foreach (DB::table('roles')->get() as $role) {
            DB::table('users')->whereIn('id', DB::table('role_user')->where('role_id', $role->id)->select('user_id'))->update(['admin_role' => $role->name]);
        }
        Schema::table('admin_role_changes', function (Blueprint $table): void {
            $table->dropColumn('action');
        });
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
    }
};
