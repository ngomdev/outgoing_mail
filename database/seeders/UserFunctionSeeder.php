<?php

namespace Database\Seeders;

use App\Models\UserFunction;
use Illuminate\Database\Seeder;

class UserFunctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Management and Leadership
        UserFunction::create([
            "name" => "CEO",
            "description" => "Chief Executive Officer",
        ]);

        UserFunction::create([
            "name" => "CFO",
            "description" => "Chief Financial Officer",
        ]);

        UserFunction::create([
            "name" => "CTO",
            "description" => "Chief Technology Officer",
        ]);

        UserFunction::create([
            "name" => "COO",
            "description" => "Chief Operating Officer",
        ]);

        UserFunction::create([
            "name" => "CMO",
            "description" => "Chief Marketing Officer",
        ]);

        UserFunction::create([
            "name" => "CIO",
            "description" => "Chief Information Officer",
        ]);

    }
}
