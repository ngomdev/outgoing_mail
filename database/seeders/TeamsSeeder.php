<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('teams')->insert([
            [
                'user_id' => '9bd62abb-fc48-4f07-b4ed-e4627f14e2fd',
                'name' => 'Direction GUCE',
                'description' => 'Direction GUCE',
                'created_at' => '2024-04-18 16:22:25',
                'updated_at' => '2024-04-18 16:22:25',
            ],
            [
                'user_id' => '9bd62abb-9509-4ff9-8778-5ef6ffc9940e',
                'name' => 'Factory infrastructure et digitale',
                'description' => 'Factory infrastructure et digitale',
                'created_at' => '2024-04-18 16:29:19',
                'updated_at' => '2024-04-18 16:29:19',
            ],
            [
                'user_id' => '9bd68a45-bf85-4976-923e-1f1ca98972a5',
                'name' => 'Factory Solution',
                'description' => 'Factory Solution',
                'created_at' => '2024-04-18 16:29:38',
                'updated_at' => '2024-04-18 16:29:38',
            ],
            [
                'user_id' => '9bd62abb-fc48-4f07-b4ed-e4627f14e2fd',
                'name' => 'CAG',
                'description' => 'CELLULE DE L\'ADMINISTRATION GENERALE',
                'created_at' => '2024-04-18 17:04:54',
                'updated_at' => '2024-04-18 17:04:54',
            ],
        ]);
    }
}
