<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CourierRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate([
            'name' => RoleEnum::PARAPHEUR->getLabel(),
            'guard_name' => 'web',
            'is_role_courier' => true
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::SIGN_MAIN->getLabel(),
            'guard_name' => 'web',
            'is_role_courier' => true
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::SIGN_ORDER->getLabel(),
            'guard_name' => 'web',
            'is_role_courier' => true
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::SIGN_INTERIM->getLabel(),
            'guard_name' => 'web',
            'is_role_courier' => true
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::SIGN_DELEGATION->getLabel(),
            'guard_name' => 'web',
            'is_role_courier' => true
        ]);
    }
}
