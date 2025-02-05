<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->withoutLogs(function () {
            $faker = Factory::create();

            // super admin
            $superAdminRole = Role::whereName(RoleEnum::SUPER_ADMIN->getLabel())->first();
            User::firstOrCreate(
                ['email' => 'super@test.mail'],
                [
                    'name' => 'Super Admin',
                    'registration_number' => $faker->unique()->randomNumber(),
                    'email_verified_at' => now(),
                    'password' => bcrypt('super')
                ]
            )->roles()->sync($superAdminRole);

            // admin
            $adminRole = Role::whereName(RoleEnum::ADMIN->getLabel())->first();
            $courserRole = Role::whereName(RoleEnum::COURSER->getLabel())->first();
            $resSuiviRole = Role::whereName(RoleEnum::RES_SUIVI->getLabel())->first();

            $rhRole = Role::whereName(RoleEnum::RH->getLabel())->first();
            User::firstOrCreate(['email' => 'rh@test.mail'], [
                'name' => 'Res Humaine',
                'phone' => $faker->phoneNumber(),
                'registration_number' => $faker->unique()->randomNumber(),
                'email_verified_at' => now(),
                'password' => bcrypt('rh'),
            ])->roles()->sync($rhRole);

            // RS
            $resSuiviRole = Role::whereName(RoleEnum::RES_SUIVI->getLabel())->first();
            User::firstOrCreate(['email' => 'ngonendiaye.laye@gmail.com'], [
                'name' => 'Res Suivi',
                'phone' => $faker->phoneNumber(),
                'registration_number' => $faker->unique()->randomNumber(),
                'email_verified_at' => now(),
                'password' => bcrypt('ressuivi'),
            ])->roles()->sync($resSuiviRole);

            User::firstOrCreate(['email' => 'kdiakhate@gainde2000.sn'], [
                'id' => '9bd62abb-9509-4ff9-8778-5ef6ffc9940e',
                'name' => 'Khady DIAKHATE',
                'registration_number' => '862',
                'phone' => null,
                'email_verified_at' => '2024-04-18 11:57:02',
                'password' => '$2y$12$D16dvVbaDweIuo1DXKbzxeHTr54vVLWNSncrCNctm6b8NWebAlAse',
                'signature' => null,
                'is_active' => 1,
                'avatar_url' => null,
                'password_changed_at' => null,
                'remember_token' => null,
                'created_at' => '2024-04-18 11:57:02',
                'updated_at' => '2024-04-18 11:57:02',
                'user_function_id' => null
            ])->roles()->sync($adminRole);

            User::firstOrCreate(['email' => 'akasse@gainde2000.sn'], [
                'id' => '9bd62abb-fc48-4f07-b4ed-e4627f14e2fd',
                'name' => 'Aissatou KASSE',
                'registration_number' => '755',
                'phone' => null,
                'email_verified_at' => '2024-04-18 11:57:02',
                'password' => '$2y$12$E1TvjG0u0i7tDA7695BkkuEhF07BHcWaNxYYFDvbeDLe/Xx.z7LyO',
                'signature' => null,
                'is_active' => 1,
                'avatar_url' => null,
                'password_changed_at' => null,
                'remember_token' => null,
                'created_at' => '2024-04-18 11:57:02',
                'updated_at' => '2024-04-18 11:57:02',
                'user_function_id' => null
            ])->roles()->sync($adminRole);

            User::firstOrCreate(['email' => 'fzndiaye@gainde2000.sn'], [
                'id' => '9bd688d7-945c-4e9d-a859-e5757f3fed34',
                'name' => 'Fatima Zahra NDIAYE',
                'registration_number' => 'G2/138',
                'phone' => '+221778965412',
                'email_verified_at' => '2024-04-18 16:20:11',
                'password' => '$2y$12$40fJHCz9bJSYmc0xp2wx7up4H3Lq7MeDQ1aRD/g/dKFs9lePk6qYq',
                'signature' => null,
                'is_active' => 1,
                'avatar_url' => null,
                'password_changed_at' => null,
                'remember_token' => null,
                'created_at' => '2024-04-18 16:20:11',
                'updated_at' => '2024-04-22 11:39:52',
                'user_function_id' => 2
            ])->roles()->sync($adminRole);

            User::firstOrCreate(['email' => 'fniass@gainde2000.sn'], [
                'id' => '9bd68a45-bf85-4976-923e-1f1ca98972a5',
                'name' => 'Fatoumata Zahra NIASS',
                'registration_number' => 'G2/11',
                'phone' => '+221784563214',
                'email_verified_at' => '2024-04-18 16:24:11',
                'password' => '$2y$12$KNg/.CKa7zYzsAF1RIDGsO6zJ/C47rLPcR1HO4aAYiWV6vEAsnAH6',
                'signature' => null,
                'is_active' => 1,
                'avatar_url' => null,
                'password_changed_at' => null,
                'remember_token' => null,
                'created_at' => '2024-04-18 16:24:11',
                'updated_at' => '2024-04-18 16:24:11',
                'user_function_id' => 4
            ])->roles()->sync($adminRole);

            User::firstOrCreate(['email' => 'adiallo@gainde2000.sn'], [
                'id' => '9bd68d4d-6092-478d-b9fe-72d9fa246b62',
                'name' => 'Aminata DIALLO',
                'registration_number' => 'G2/182',
                'phone' => '+221765842354',
                'email_verified_at' => '2024-04-18 16:32:39',
                'password' => '$2y$12$xiW3/rxaAxpIzMLJK1BxKe8a5YEMLZ7siB0lI3KUBugnLodRVk.I2',
                'signature' => null,
                'is_active' => 1,
                'avatar_url' => null,
                'password_changed_at' => null,
                'remember_token' => null,
                'created_at' => '2024-04-18 16:32:39',
                'updated_at' => '2024-04-18 16:40:21',
                'user_function_id' => 5
            ])->roles()->sync($resSuiviRole);

            User::firstOrCreate(['email' => 'Talla@yopmail.com'], [
                'id' => '9bd68e4c-f869-4886-bae4-a8f74a2d09e9',
                'name' => 'Mor Talla DIOP',
                'registration_number' => 'G2/20',
                'phone' => '+221776584265',
                'email_verified_at' => '2024-04-18 16:35:27',
                'password' => '$2y$12$CQwvc71Zk7QrrXS17O/0u.8efiREoshzOgNQSvQHY38gt0Ak.GcAq',
                'signature' => null,
                'is_active' => 1,
                'avatar_url' => null,
                'password_changed_at' => null,
                'remember_token' => null,
                'created_at' => '2024-04-18 16:35:27',
                'updated_at' => '2024-04-22 10:06:51',
                'user_function_id' => 1
            ])->roles()->sync($resSuiviRole);

            User::firstOrCreate(['email' => 'idiatta@gainde2000.sn'], [
                'id' => '9bd68f63-9c4d-4251-89be-33198943ce96',
                'name' => 'Isabel DIATTA',
                'registration_number' => 'G2/51',
                'phone' => '+221778965231',
                'email_verified_at' => '2024-04-18 16:38:29',
                'password' => '$2y$12$nh0Jobn6jyDKobYT.jaJ8ORI1hEo0vQV7aOC7vTybjsYKPttUC.YO',
                'signature' => null,
                'is_active' => 1,
                'avatar_url' => null,
                'password_changed_at' => null,
                'remember_token' => null,
                'created_at' => '2024-04-18 16:38:29',
                'updated_at' => '2024-04-22 17:01:04',
                'user_function_id' => 3
            ])->roles()->sync($resSuiviRole);

            User::firstOrCreate(['email' => 'nssarr@gainde2000.sn'], [
                'id' => '9bd6942d-1cbb-4507-903f-9f2f47b07f61',
                'name' => 'Ndeye Sokhna  SARR',
                'registration_number' => 'G2/90',
                'phone' => '+221774589621',
                'email_verified_at' => '2024-04-18 16:38:29',
                'password' => '$2y$12$hA14302i6OcQjjj.fm7Une169EdXAMsby.rgmo7jc2NX3COlw4OMq',
                'signature' => null,
                'is_active' => 1,
                'avatar_url' => null,
                'password_changed_at' => null,
                'remember_token' => null,
                'created_at' => '2024-04-18 16:51:52',
                'updated_at' => '2024-04-22 11:38:19',
                'user_function_id' => 6
            ])->roles()->sync($resSuiviRole);

            User::firstOrCreate(['email' => 'coursier@yopmail.com'], [
                'id' => '9bd6959c-aff1-47e7-a6c9-d6abb84afc04',
                'name' => 'Alpha Coursier',
                'registration_number' => 'G2/001',
                'phone' => '+221778745214',
                'email_verified_at' => '2024-04-18 16:55:53',
                'password' => bcrypt('coursier'),
                'signature' => NULL,
                'is_active' => 1,
                'avatar_url' => NULL,
                'password_changed_at' => NULL,
                'remember_token' => null,
                'created_at' => '2024-04-18 16:55:53',
                'updated_at' => '2024-04-22 16:35:10',
                'user_function_id' => 4,
            ])->roles()->sync($courserRole);

            User::firstOrCreate(['email' => 'modoucoursier@yopmail.com'], [
                'id' => '9bd695fc-23ea-41fe-97fd-b22b63622a44',
                'name' => 'Modou DIOP Coursier',
                'registration_number' => 'G2/48',
                'phone' => '+221772563214',
                'email_verified_at' => '2024-04-18 16:56:56',
                'password' => '$2y$12$3AOps3RZB8ZgPSty2Hd8WeUgc7h4JJdcS1EIXi3iMuhqcQEcg7m8e',
                'signature' => NULL,
                'is_active' => 1,
                'avatar_url' => NULL,
                'password_changed_at' => NULL,
                'remember_token' => null,
                'created_at' => '2024-04-18 16:56:56',
                'updated_at' => '2024-04-18 16:58:48',
                'user_function_id' => 3,
            ])->roles()->sync($courserRole);

            User::firstOrCreate(['email' => 'kane@yopmail.com'], [
                'id' => '9bde0956-4ffa-42a0-b856-87ea178f04c2',
                'name' => 'Mame ousmane KANE',
                'registration_number' => 'G2/88',
                'phone' => '+221778964127',
                'email_verified_at' => '2024-04-22 09:50:16',
                'password' => '$2y$12$PvMwgiRUXJ9Z5MGR0Y3Gre.Y6SA7yqKF04BS1V/Ieq0Kihy9yoz3m',
                'signature' => NULL,
                'is_active' => 1,
                'avatar_url' => NULL,
                'password_changed_at' => NULL,
                'remember_token' => null,
                'created_at' => '2024-04-22 09:50:16',
                'updated_at' => '2024-04-22 09:54:38',
                'user_function_id' => 2,
            ])->roles()->sync($resSuiviRole);

            User::firstOrCreate(['email' => 'respSuivi@yopmail.com'], [
                'id' => '9be00ffb-a804-495d-98ac-f4a0db54975d',
                'name' => 'Fama Responsable Suivi',
                'registration_number' => 'G2/898',
                'phone' => '+221778965400',
                'email_verified_at' => '2024-04-23 10:00:31',
                'password' => '$2y$12$zfosvrpYHO.l9wppnpKu0.MY044GUugZavFUR9B4ZvtwKKgvT/vU2',
                'signature' => NULL,
                'is_active' => 1,
                'avatar_url' => NULL,
                'password_changed_at' => NULL,
                'remember_token' => null,
                'created_at' => '2024-04-23 10:00:31',
                'updated_at' => '2024-04-23 10:01:31',
                'user_function_id' => 6,
            ])->roles()->sync($resSuiviRole);

            User::firstOrCreate(['email' => 'testrs@yopmail.com'], [
                'id' => '9be04a53-2002-4e7c-97d7-e41b23d5805a',
                'name' => 'User Test RS',
                'registration_number' => 'G2/144',
                'phone' => NULL,
                'email_verified_at' => '2024-04-23 12:43:39',
                'password' => '$2y$12$Dw8uqAFJdgIs1F2SJUuIc.rQcQYNleF.afxlx0KKiNcyv2hcIB1lK',
                'signature' => NULL,
                'is_active' => 1,
                'avatar_url' => NULL,
                'password_changed_at' => NULL,
                'remember_token' => null,
                'created_at' => '2024-04-23 12:43:39',
                'updated_at' => '2024-04-23 12:44:44',
                'user_function_id' => 2,
            ])->roles()->sync($resSuiviRole);
        });
    }
}
