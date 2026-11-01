<?php

namespace Database\Seeders;

use App\Models\MembershipApplication;
use Illuminate\Database\Seeder;

class MembershipApplicationSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            return;
        }

        MembershipApplication::factory()->count(3)->create();
    }
}
