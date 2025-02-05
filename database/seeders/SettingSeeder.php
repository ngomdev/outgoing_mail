<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Enums\SettingKeys;
use App\Enums\SettingModule;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => SettingKeys::PASSWORD_EXPIRATION_DELAY],
            [
                'display_name' => 'Délais expiration mot de passe',
                'value' => '60',
                'description' => 'Temps de validité d\'un mot de passe utilisateur. PAssé ce délai, l\'utilisateur devra renouveller son mot de passe pour accéder aux resources de l\'application.',
                'unit' => 'jours',
                'default_value' => '60',
                'module' => SettingModule::SYSTEM,
                'is_active' => false,
            ]
        );

        Setting::updateOrCreate(
            ['key' => SettingKeys::COURIER_RECOVERY_DELAY],
            [
                'display_name' => 'Délais récupération courrier',
                'value' => '48',
                'description' => 'Délais après lequel un courrier est marqué comme en retard s\'il n\'a pas été récupéré par le coursier affecté',
                'unit' => 'heures',
                'default_value' => '48',
                'module' => SettingModule::COURIER,
                'is_active' => false,
            ]
        );

        Setting::updateOrCreate(
            ['key' => SettingKeys::DOC_URGENCY_NORMAL],
            [
                'display_name' => 'Document normal',
                'value' => '168',
                'description' => 'Délais de validation d\'un document après lequel le tour de validation passe automatiquement au validateur suivant.',
                'unit' => 'heures',
                'default_value' => '168',
                'module' => SettingModule::DOCUMENT,
                'is_active' => false,
            ]
        );

        Setting::updateOrCreate(
            ['key' => SettingKeys::DOC_URGENCY_URGENT],
            [
                'display_name' => 'Document urgent',
                'value' => '120',
                'description' => 'Délais de validation d\'un document après lequel le tour de validation passe automatiquement au validateur suivant.',
                'unit' => 'heures',
                'default_value' => '120',
                'module' => SettingModule::DOCUMENT,
                'is_active' => false,
            ]
        );

        Setting::updateOrCreate(
            ['key' => SettingKeys::DOC_URGENCY_CRITICAL],
            [
                'display_name' => 'Document Très urgent',
                'value' => '48',
                'description' => 'Délais de validation d\'un document après lequel le tour de validation passe automatiquement au validateur suivant.',
                'unit' => 'heures',
                'default_value' => '48',
                'module' => SettingModule::DOCUMENT,
                'is_active' => false,
            ]
        );
    }
}
