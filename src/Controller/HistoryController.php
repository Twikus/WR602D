<?php

namespace App\Controller;

use App\Repository\PdfHistoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HistoryController extends AbstractController
{
    #[Route('/history', name: 'app_history')]
    public function index(PdfHistoryRepository $pdfHistoryRepository): Response
    {
        $pdfHistories = $pdfHistoryRepository->findBy([], ['created_at' => 'DESC']);

        // Check if the file exists, if not, set the title to 'expired'
        foreach ($pdfHistories as $pdfHistory) {
            $pdf = $pdfHistory->getPdf();
            if (!file_exists($pdf->getTitle())) {
                $pdf->setTitle('expired');
            }
        }

        return $this->render('history/index.html.twig', [
            'controller_name' => 'HistoryController',
            'pdf_histories' => $pdfHistories,
        ]);
    }
}
