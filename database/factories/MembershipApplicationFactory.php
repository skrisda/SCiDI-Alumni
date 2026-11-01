<?php

namespace Database\Factories;

use App\Models\MembershipApplication;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<MembershipApplication> */
class MembershipApplicationFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $digits = fake()->unique()->numerify('1###########');
        $sum = 0;
        for ($index = 0; $index < 12; $index++) {
            $sum += (int) $digits[$index] * (13 - $index);
        }
        $nationalId = $digits.((11 - ($sum % 11)) % 10);

        return [
            'user_id' => User::factory(),
            'membership_type' => 'ordinary',
            'ordinary_category' => 'graduate',
            'prefix' => 'นาย',
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'program' => 'วิทยาการคอมพิวเตอร์',
            'national_id' => $nationalId,
            'national_id_hash' => hash_hmac('sha256', $nationalId, config('app.key')),
            'address' => fake()->address(),
            'phone' => '0812345678',
            'email' => fake()->safeEmail(),
            'fee_amount' => 100,
            'status' => 'pending_review',
            'payment_status' => 'awaiting_payment',
        ];
    }
}
