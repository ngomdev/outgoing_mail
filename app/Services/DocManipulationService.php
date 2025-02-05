<?php

namespace App\Services;

use Carbon\Carbon;
use App\Enums\DocType;
use setasign\Fpdi\Fpdi;
use Filament\Notifications\Notification;
use PhpOffice\PhpWord\TemplateProcessor;
use NcJoes\OfficeConverter\OfficeConverter;


class DocManipulationService
{

    //TODO ADD SIGNATURE TO DOCX FILE
    public function addSignature($docPath, $signaturePath, $docType = null)
    {
        $templateProcessor = new TemplateProcessor($docPath);
        // Add user signature to word file
        $templateProcessor->setImageValue(
            'signature',
            array(
                'path' => $signaturePath,
                'width' => 100,
                'height' => 100,
                'ratio' => false,
                'wrappingStyle' => 'infront',
                'alignment' => 'center',
                'positioning' => 'absolute'
            )
        );

        if ($docType === DocType::LETTER) {
            $date = Carbon::now()->locale('fr')->isoFormat('D MMMM YYYY');
            $templateProcessor->setValue('date_signature', $date);
        }
        // Save the modified Word document
        $templateProcessor->saveAs($docPath);
    }

    //TODO CONVERT DOCX FILE TO PDF

    public function convertDocToPdf($docPath, $fileName, $outputDirectory)
    {
        try {
            //TODO windows
            // $converter = new OfficeConverter($docPath, public_path("storage/$outputDirectory"), "soffice", false);
            // TODO Ubuntu
            $converter = new OfficeConverter($docPath, public_path("storage/$outputDirectory"));
            $converter->convertTo("$fileName.pdf");

            $generatedPdfPath = public_path("storage/$outputDirectory/$fileName.pdf");

            return $generatedPdfPath;
        } catch (\Exception $e) {
            // Log or echo the error message for debugging
            echo "Error: " . $e->getMessage();
        }
    }


    //TODO ADD COURIER NUMBER TO DOC
    public function addCourierNo($docPath, $courierNo)
    {
        $templateProcessor = new TemplateProcessor($docPath);
        $templateProcessor->setValue('numero_courrier', 'N°' . $courierNo);
        // Save the modified Word document
        $templateProcessor->saveAs($docPath);
    }

    // ADD VALIDATORS PARAPH  TO PDF FILE
    public function addParaphsToPdf($docFileName, $pdfFilePath, $paraphs, $docId)
    {
        // Set source PDF file
        $pdf = new Fpdi();
        if (file_exists($pdfFilePath)) {
            $pagesCount = $pdf->setSourceFile($pdfFilePath);
        } else {
            return Notification::make()
                ->title('Oups!')
                ->danger()
                ->body("File not found")
                ->persistent()
                ->send();
        }

        // Add paraphs to PDF pages
        for ($pageNo = 1; $pageNo <= $pagesCount; $pageNo++) {
            $template = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($template);
            $pdf->addPage();
            $pdf->useTemplate($template, 1, 1, $size['width'], $size['height'], true);

            // Add paraphs 10px below the top of each page
            $y = 10;
            $imageSpacing = -10;

            // Calculate the total width of all images including spacings
            $totalImageWidth = 0;
            $scaledImageWidths = [];

            foreach ($paraphs as $imagePath) {
                $paraph = public_path('storage/' . $imagePath);
                // Get paraphs images dimensions
                list($imageWidth, $imageHeight) = getimagesize($paraph);
                // Calculate scaling factors
                $scaleX = 20 / $imageWidth; // scaling image width to 20 units
                $scaledImageWidth = $imageWidth * $scaleX;
                $scaledImageWidths[] = $scaledImageWidth;
                $totalImageWidth += $scaledImageWidth + $imageSpacing;
            }

            // Subtract the last image spacing as it's not needed after the last image
            if (count($scaledImageWidths) > 0) {
                $totalImageWidth -= $imageSpacing;
            }

            // Starting x position to align all images to the right
            $x = $size['width'] - $totalImageWidth - 10; // 10px padding from the right edge

            foreach ($paraphs as $index => $imagePath) {
                $paraph = public_path('storage/' . $imagePath);
                list($imageWidth, $imageHeight) = getimagesize($paraph);
                $scaledImageWidth = $scaledImageWidths[$index];
                // Calculate position for the current image
                $currentX = $x;
                $pdf->Image($paraph, $currentX, $y, $scaledImageWidth, 15, 'png');
                // Update the x position for the next image
                $x += $scaledImageWidth + $imageSpacing;
            }
        }

        $outputPath = public_path("storage/doc-attachments/$docId/$docFileName.pdf");

        // Output pdf with paraphs
        $pdf->Output('F', $outputPath);

        // Delete temporary pdf file
        unlink($pdfFilePath);
        return $outputPath;

    }

    public function addCourierNoToPdf($filePath, $courierNo)
    {
        // Create a new FPDI instance
        $pdf = new Fpdi();

        if (file_exists($filePath)) {
            $pagesCount = $pdf->setSourceFile($filePath);

            for ($pageNo = 1; $pageNo <= $pagesCount; $pageNo++) {
                $template = $pdf->importPage($pageNo);
                $pdf->addPage();
                $pdf->useTemplate($template);

                // Add text to the first page
                if ($pageNo == 1) {
                    $pdf->SetFont('Helvetica', '', 12);
                    $pdf->SetTextColor(0, 0, 0);
                    $textX = 20;
                    $textY = 20;
                    $encodedCourierNo = mb_convert_encoding("N°$courierNo", 'ISO-8859-1', 'UTF-8');
                    $pdf->Text($textX, $textY, $encodedCourierNo);
                }
            }

            // Save the PDF with the text on the first page
            $pdf->Output($filePath, 'F');
        } else {
            return Notification::make()
                ->title('Oups!')
                ->danger()
                ->body("File not found")
                ->persistent()
                ->send();
        }
    }

    //TODO ACTIVATE TRACK REVISIONS FOR DOCUMENT
    public function trackRevisions($docPath, $state)
    {
        $template = new CustomTemplateProcessor($docPath);
        $template->setTrackRevisions($state);
        $template->saveAs($docPath);
    }

    public function searchVariables($docPath, $variables)
    {
        $template = new CustomTemplateProcessor($docPath);
        return $template->hasVariables($variables);
    }

}
