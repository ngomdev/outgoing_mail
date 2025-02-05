<?php

namespace App\Livewire\DocumentModule;

use Livewire\Component;
use App\Models\Document;
use App\Services\DocManipulationService;
use PhpOffice\PhpWord\TemplateProcessor;

class ExportDocument extends Component
{
    public Document $record;

    public $name;

    public $email;

    public function mount()
    {
        $docPath = public_path('storage/' . $this->record->doc_path);
        $templateProcessor = new TemplateProcessor($docPath);
        // Remove placeholder in word file
        $templateProcessor->setValue('signature', '');
        // Save the modified Word document
        $fileName = pathinfo($this->docPath, PATHINFO_FILENAME);
        $newWordFilePath = public_path('/storage/doc-attachments/' . $fileName . '-no-signature.docx');
        $templateProcessor->saveAs($newWordFilePath);

        // TODO GET GENERATED PDF FILE
        $pdfFilePath = (new DocManipulationService())->convertDocToPdf($newWordFilePath, $fileName,"doc-attachments/{$this->record->id}");

        // TODO DELETE TEMPORARY WORD FILE WITHOUT SIGNATURE PLACEHOLDER
        unlink($newWordFilePath);

        // TODO ADD VALIDATORS PARAPHS TO PDF
        (new DocManipulationService())->addParaphsToPdf($fileName, $pdfFilePath, $this->validatorsParaphs, $this->record->id);
    }

    public function render()
    {
        return view('livewire.document-module.export-document');
    }
}
