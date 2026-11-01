<?php

namespace Database\Seeders;

use App\AdminRole;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([AdminRole::Admin, AdminRole::Registrar, AdminRole::Treasurer] as $role) {
            Role::firstOrCreate(['name' => $role->value], ['label' => $role->label()]);
        }
    }
}
