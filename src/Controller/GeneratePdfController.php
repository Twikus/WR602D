<?php

namespace App\Controller;

use App\Service\PdfService;
use App\Service\EmailService;
use App\Service\WatermarkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GeneratePdfController extends AbstractController
{
    private PdfService $pdfService;
    private WatermarkService $watermarkService;
    private EmailService $emailService;


    public function __construct(PdfService $pdfService, WatermarkService $watermarkService, EmailService $emailService)
    {
        $this->pdfService = $pdfService;
        $this->watermarkService = $watermarkService;
        $this->emailService = $emailService;
    }

    #[Route('/url-to-pdf', name: 'url_to_pdf')]
    public function generatePdf(Request $request, PdfService $pdfService, ManagerRegistry $doctrine): Response
    {   
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // If form was submitted
        if ($request->isMethod('POST')) {
            // Get the URL from the form data
            $url = $request->request->get('url');

            $user_credits = $user->getUserCredits();

            if($user_credits < 1){
                return $this->render('url_to_pdf/index.html.twig', [
                    'error' => 'Vous n\'avez plus assez de crédits pour générer un PDF, vous pouvez passer sur un abonnement supérieur pour obtenir plus de crédits.'
                ]);
            }

            $pdf = $pdfService->generatePdfFromUrl($url);
        
            // Decrement user credits
            $user->setUserCredits($user_credits - 1);

            // Save the user
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            $pdfPath = $this->getParameter('kernel.project_dir') . '/public/' . $pdf->getTitle();
            
            // If the user's subscription is free, add a watermark
            if ($user->getSubscription()->getTitle() === 'Free') {
                $this->watermarkService->generateWatermark($pdfPath);
            }

            // Send an email to the user
            $this->emailService->sendEmail($user->getEmail(), 'Votre PDF', 'Voici votre PDF', $pdfPath);

            return $this->file($pdfPath);
        }

        // Si le formulaire n'a pas été soumis, affichez le formulaire
        return $this->render('url_to_pdf/index.html.twig', [
            // 'user_credits' => $user_credits,
        ]);
    }

    #[Route('/html-to-pdf', name: 'html_to_pdf')]
    public function generatePdfFromHtml(Request $request, PdfService $pdfService): Response
    {
        // Si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            // Obtenez le fichier à partir des données du formulaire
            $file = $request->files->get('html_file');
            // Vérifiez si un fichier a été téléchargé
            if (!$file) {
                throw new \Exception('No file was uploaded.');
            }
            // Générez le PDF et enregistrez-le dans un fichier
            $pdf = $pdfService->generatePdfFromHtml($file);
            // ouvrir le pdf dans le navigateur
            $pdfPath = $this->getParameter('kernel.project_dir') . '/public/' . $pdf->getTitle();
            return $this->file($pdfPath);
        }
        // Si le formulaire n'a pas été soumis, affichez le formulaire
        return $this->render('url_to_pdf/index.html.twig');
    }

}
