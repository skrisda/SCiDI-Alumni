<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_applications', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->string('membership_type', 30);
            $table->string('prefix', 50);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('student_code', 30)->nullable();
            $table->string('program');
            $table->string('nickname', 100)->nullable();
            $table->text('national_id');
            $table->string('national_id_hash', 64)->unique();
            $table->text('address');
            $table->string('phone', 30);
            $table->string('email');
            $table->text('qualification');
            $table->unsignedInteger('fee_amount')->nullable();
            $table->string('status', 30)->default('pending_review');
            $table->string('payment_status', 30)->default('awaiting_payment');
            $table->string('slip_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_applications');
    }
};
