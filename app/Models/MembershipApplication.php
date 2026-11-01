<?php

namespace App\Models;

use Database\Factories\MembershipApplicationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'membership_type', 'ordinary_category', 'prefix', 'first_name', 'last_name', 'student_code', 'program', 'nickname', 'national_id', 'national_id_hash', 'address', 'phone', 'email', 'fee_amount', 'status', 'slip_path', 'payment_status'])]
#[Hidden(['national_id', 'national_id_hash', 'slip_path'])]
class MembershipApplication extends Model
{
    /** @use HasFactory<MembershipApplicationFactory> */
    use HasFactory;

    /** @var array<string, string> */
    public const TYPES = ['ordinary' => 'สมาชิกสามัญ', 'extraordinary' => 'สมาชิกวิสามัญ', 'associate' => 'สมาชิกสมทบ'];

    /** @var array<string, string> */
    public const ORDINARY_CATEGORIES = ['graduate' => 'ผู้สำเร็จการศึกษา', 'lecturer' => 'อาจารย์', 'staff' => 'บุคลากร'];

    protected function casts(): array
    {
        return ['national_id' => 'encrypted', 'fee_amount' => 'integer'];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
