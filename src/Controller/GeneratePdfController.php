<?php

namespace App\Controller;

use App\Service\PdfService;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GeneratePdfController extends AbstractController
{
    private $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    #[Route('/url-to-pdf', name: 'url_to_pdf')]
    public function generatePdf(Request $request, PdfService $pdfService, ManagerRegistry $doctrine): Response
    {   
        // Si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            // Obtenez l'URL à partir des données du formulaire
            $url = $request->request->get('url');

            $user = $this->getUser();
            $user_credits = $user->getUserCredits();

            if($user_credits < 1){
                // renvoyer une erreur si l'utilisateur n'a pas de crédits
                return $this->render('url_to_pdf/index.html.twig', [
                    'error' => 'Vous n\'avez plus assez de crédits pour générer un PDF, vous pouvez passer sur un abonnement supérieur pour obtenir plus de crédits.'
                ]);
            }

            //Générez le PDF et enregistrez-le dans un fichier
            $pdf = $pdfService->generatePdfFromUrl($url);
            
            // décrémenter le nombre de crédits de l'utilisateur
            $user->setUserCredits($user_credits - 1);

            $pdfPath = $this->getParameter('kernel.project_dir') . '/public/' . $pdf->getTitle();
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
