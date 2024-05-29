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

    #[Route('/url-to-pdf', name: 'url_to_pdf')]
    public function generatePdf(Request $request, PdfService $pdfService): Response
    {
        // Si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            // Obtenez l'URL à partir des données du formulaire
            $url = $request->request->get('url');

            // Générez le PDF et enregistrez-le dans un fichier
            $pdf = $pdfService->generatePdfFromUrl($url);

            // ouvrir le pdf dans le navigateur
            $pdfPath = $this->getParameter('kernel.project_dir') . '/public/' . $pdf->getTitle();
            return $this->file($pdfPath);
        }

        // Si le formulaire n'a pas été soumis, affichez le formulaire
        return $this->render('url_to_pdf/index.html.twig');
    }

}
