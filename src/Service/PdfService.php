<?php
namespace App\Service;

use App\Entity\Pdf;
use App\Entity\PdfHistory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PdfService
{
    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function removePdf(string $pdf): void
    {
        // set is_deleted to true
        $pdf = $this->entityManager->getRepository(Pdf::class)->findOneBy(['title' => $pdf]);
        $pdf->setIsDeleted(true);
        $this->entityManager->flush();

        // remove the file
        unlink(__DIR__ . '/../../public/' . $pdf->getTitle());
    }

    public function savePdf(Pdf $pdf): void
    {
        $user = $this->security->getUser();

        // Check if the user is logged in
        if (!$user) {
            throw new \Exception('No user is currently logged in.');
        }

        $pdfName = uniqid() . '.pdf';
        file_put_contents($pdfName, $pdf->getContent());

        $pdf->setTitle($pdfName);
        $pdf->setUserId($user);

        $this->entityManager->persist($pdf);
        $this->entityManager->flush();

        $this->logPdfCreation($pdf);
    }
    
    public function generatePdfFromUrl(string $url): Pdf
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://app-microservice:80/url-to-pdf?url=' . $url);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('The PDF could not be generated.');
        }

        $pdf = new Pdf();
        $pdf->setContent($response->getContent());
        $this->savePdf($pdf);

        return $pdf;
    }

    public function generatePdfFromHtml(UploadedFile $file): Pdf
    {
        $client = HttpClient::create();
        $html = file_get_contents($file->getPathname());

        $response = $client->request('POST', 'http://app-microservice:80/html-to-pdf', [
            'body' => [
                'html' => $html
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('The PDF could not be generated.');
        }

        $pdf = new Pdf();
        $pdf->setContent($response->getContent());
        $this->savePdf($pdf);

        return $pdf;
    }

    public function logPdfCreation(Pdf $pdf): void
    {
        $pdfHistory = new PdfHistory();
        $pdfHistory->setPdf($pdf);
        $pdfHistory->setUser($pdf->getUserId());

        $this->entityManager->persist($pdfHistory);
        $this->entityManager->flush();
    }
}