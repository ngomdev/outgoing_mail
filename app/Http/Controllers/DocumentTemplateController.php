<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DocumentTemplateController extends Controller
{
    public function getDocument(Request $request)
    {
        $filePath = resource_path('views/vendor/filament-tinyeditor/template.html');

        // Check if the file exists
        if (File::exists($filePath)) {
            $content = File::get($filePath);

            // Return the raw HTML content
            return response($content)->header('Content-Type', 'text/html');
        }

        return response()->json(['error' => 'File not found'], 404);
    }
}
