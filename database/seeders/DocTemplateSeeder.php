<?php

namespace Database\Seeders;

use App\Enums\DocType;
use App\Models\DocTemplate;
use Illuminate\Database\Seeder;

class DocTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DocTemplate::create([
            'name' => 'Note de Service',
            'doc_type' => DocType::MEMORANDUM,
            'file_path' => 'memorandum.docx'
        ]);

        DocTemplate::create([
            'name' => 'Lettre',
            'doc_type' => DocType::LETTER,
            'file_path' => 'letter.docx'
        ]);

        DocTemplate::create([
            'name' => 'Contrat',
            'doc_type' => DocType::CONTRACT,
            'file_path' => 'contract.docx'
        ]);

        DocTemplate::create([
            'name' => 'PV',
            'doc_type' => DocType::TRANSCRIPT,
            'file_path' => 'transcript.docx'
        ]);
    }
}
