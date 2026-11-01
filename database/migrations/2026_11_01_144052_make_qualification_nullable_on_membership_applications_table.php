<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_applications', function (Blueprint $table): void {
            $table->text('qualification')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (DB::table('membership_applications')->whereNull('qualification')->exists()) {
            throw new LogicException('ไม่สามารถบังคับคุณสมบัติได้ ขณะที่มีคำขอที่ไม่ได้ระบุข้อมูลนี้');
        }
        Schema::table('membership_applications', function (Blueprint $table): void {
            $table->text('qualification')->nullable(false)->change();
        });
    }
};
