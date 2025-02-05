<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Recipient;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use App\Models\ExternalDocInitiator;
use Database\Seeders\DocTemplateSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        activity()->withoutLogs(function () {
            $this->call(RoleSeeder::class);
            $this->call(UserSeeder::class);
            $this->call(DocTemplateSeeder::class);
            $this->call(SettingSeeder::class);
            $this->call(ShieldSeeder::class);
            $this->call(UserFunctionSeeder::class);
            $this->call(TeamsSeeder::class);

            Recipient::factory(3)->create()->each(fn($r) => $r->contacts()->saveMany(Contact::factory()->count(1)->create()));

            ExternalDocInitiator::factory(5)->create();
        });

    }
}
