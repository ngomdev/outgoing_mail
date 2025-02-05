<?php
namespace App\Services;


use PhpOffice\PhpWord\IOFactory;

class DocxToHtmlConverter
{
    public function convertToHtml($docxFilePath)
    {
        $phpWord = IOFactory::load($docxFilePath);
        $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');

        // Save HTML content to a variable
        ob_start();
        $htmlWriter->save(public_path('storage/result.docx'));
        $htmlContent = ob_get_clean();

        return $htmlContent;
    }
}
