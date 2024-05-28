<?php
namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class PdfService
{
    public function generatePdfFromUrl(string $url): string
    {
        $client = HttpClient::create();
        $pdf = $client->request('GET', 'http://app-microservice:80/url-to-pdf?url=' . $url);

        if ($pdf->getStatusCode() !== 200) {
            throw new \Exception('The PDF could not be generated.');
        }

        $pdfName = uniqid() . '.pdf';
        file_put_contents($pdfName, $pdf->getContent());

        return $pdfName;
    }
}