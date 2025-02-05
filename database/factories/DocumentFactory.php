<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\DocType;
use App\Enums\DocStatus;
use App\Models\Document;
use App\Enums\DocUrgency;
use App\Models\ExternalDocInitiator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition()
    {
        $docPath = $this->generateDocPath();

        return [
            'created_by' => User::first()->id,
            'should_be_expedited' => $this->faker->boolean,
            'doc_type' => $this->faker->randomElement(DocType::getValues()),
            'doc_urgency' => $this->faker->randomElement(DocUrgency::getValues()),
            'name' => $this->faker->unique()->word,
            'object' => $this->faker->text,
            'status' => DocStatus::DRAFT,
            'doc_path' => $docPath,
            'doc_created_at' => $this->faker->dateTimeThisMonth,
        ];
    }

    private function generateDocPath()
    {
        $documentId = $this->faker->unique()->randomNumber();
        $documentName = $this->faker->word;
        $docPath = "public/doc-attachments/{$documentId}/{$documentName}.docx";

        // Copy the template from public/storage/templates to the generated path
        Storage::copy('public/storage/templates/template.docx', $docPath);

        return $docPath;
    }
}
