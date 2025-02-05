<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\DocSigningService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class DocSigningServiceTest extends TestCase
{
    protected DocSigningService $docSigningService;

    private $filePath = __DIR__ . '/test.pdf';

    protected function setUp(): void
    {
        parent::setUp();

        $this->docSigningService = new DocSigningService();

        Config::set('app.doc_signing_url', 'https://example.com/sign_document');
        Config::set('app.doc_signing_worker_id', '1234');
    }

    public function testSignDocumentSuccess()
    {
        // Simulate a binary PDF file response as a string
        $binaryContent = '%PDF-1.4 ... binary content of a PDF ... %%EOF';

        Http::fake([
            'https://example.com/sign_document' => Http::response($binaryContent, 200, [
                'Content-Type' => 'application/pdf',
            ]),
        ]);

        $response = $this->docSigningService->signDocument('30', $this->filePath);

        // Assert that the response is a success and the binary data is correctly returned
        $this->assertEquals('success', $response['status']);
        $this->assertEquals($binaryContent, $response['data']);
    }

    public function testSignDocumentError()
    {
        Http::fake([
            'https://example.com/sign_document' => Http::response('Unauthorized access', 403),
        ]);

        $response = $this->docSigningService->signDocument('30', $this->filePath);

        $this->assertEquals('error', $response['status']);
        $this->assertStringContainsString('Unexpected HTTP status: 403', $response['message']);
    }

    public function testSignDocumentExceptionHandling()
    {
        Http::fake(function () {
            throw new \Exception('Connection timeout');
        });

        $response = $this->docSigningService->signDocument('30', $this->filePath);

        $this->assertEquals('error', $response['status']);
        $this->assertStringContainsString('Error: Connection timeout', $response['message']);
    }
}
