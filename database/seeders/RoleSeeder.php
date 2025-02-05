<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate([
            'name' => RoleEnum::SUPER_ADMIN->getLabel(),
            'display_name' => RoleEnum::SUPER_ADMIN->getLabel(),
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::ADMIN->getLabel(),
            'display_name' => RoleEnum::ADMIN->getLabel(),
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::RES_SUIVI->getLabel(),
            'display_name' => RoleEnum::RES_SUIVI->getLabel(),
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::RES_JURI->getLabel(),
            'display_name' => RoleEnum::RES_JURI->getLabel(),
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::RES_ACHAT->getLabel(),
            'display_name' => RoleEnum::RES_ACHAT->getLabel(),
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::AG->getLabel(),
            'display_name' => RoleEnum::AG->getLabel(),
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::RH->getLabel(),
            'display_name' => RoleEnum::RH->getLabel(),
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::COURSER->getLabel(),
            'display_name' => RoleEnum::COURSER->getLabel(),
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::INITIATOR->getLabel(),
            'display_name' => RoleEnum::INITIATOR->getLabel(),
            'is_role_courier' => true,
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::PARAPHEUR->getLabel(),
            'display_name' => RoleEnum::PARAPHEUR->getLabel(),
            'guard_name' => 'web',
            'is_role_courier' => true
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::SIGN_MAIN->getLabel(),
            'display_name' => RoleEnum::SIGN_MAIN->getLabel(),
            'guard_name' => 'web',
            'is_role_courier' => true
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::SIGN_ORDER->getLabel(),
            'display_name' => RoleEnum::SIGN_ORDER->getLabel(),
            'guard_name' => 'web',
            'is_role_courier' => true
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::SIGN_INTERIM->getLabel(),
            'display_name' => RoleEnum::SIGN_INTERIM->getLabel(),
            'guard_name' => 'web',
            'is_role_courier' => true
        ]);

        Role::firstOrCreate([
            'name' => RoleEnum::SIGN_DELEGATION->getLabel(),
            'display_name' => RoleEnum::SIGN_DELEGATION->getLabel(),
            'guard_name' => 'web',
            'is_role_courier' => true
        ]);
    }
}
