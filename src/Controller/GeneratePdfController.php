<?php

namespace App\Controller;

use App\Service\PdfService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class GeneratePdfController extends AbstractController
{
    private $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    #[Route('/generate-pdf', name: 'generate_pdf')]
    public function generatePdf(Request $request, PdfService $pdfService): Response
    {
        // If the form was submitted
        if ($request->isMethod('POST')) {
            // Get the URL from the form data
            $url = $request->request->get('url');

            // Generate the PDF and save it to a file
            $pdfName = $pdfService->generatePdfFromUrl($url);

            // Render the view with the path to the PDF
            return $this->render('generate_pdf/index.html.twig', ['pdfName' => $pdfName]);
        }

        // If the form was not submitted, display the form
        return $this->render('generate_pdf/index.html.twig');
    }
}
