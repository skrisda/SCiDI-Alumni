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
            $table->string('ordinary_category', 20)->nullable();
            $table->string('program')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (DB::table('membership_applications')->whereNull('program')->exists()) {
            throw new LogicException('ไม่สามารถบังคับหลักสูตรได้ ขณะที่มีคำขอที่ไม่ต้องระบุหลักสูตร');
        }
        Schema::table('membership_applications', function (Blueprint $table): void {
            $table->dropColumn('ordinary_category');
            $table->string('program')->nullable(false)->change();
        });
    }
};
