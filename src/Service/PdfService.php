<?php
namespace App\Service;

use App\Entity\Pdf;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpClient\HttpClient;

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
    
    public function generatePdfFromUrl(string $url): Pdf
    {
        $client = HttpClient::create();
        $pdf = $client->request('GET', 'http://app-microservice:80/url-to-pdf?url=' . $url);

        if ($pdf->getStatusCode() !== 200) {
            throw new \Exception('The PDF could not be generated.');
        }

        $user = $this->security->getUser();

        // Check if the user is logged in
        if (!$user) {
            throw new \Exception('No user is currently logged in.');
        }

        $pdfName = uniqid() . '.pdf';
        file_put_contents($pdfName, $pdf->getContent());

        $pdf = new Pdf();
        $pdf->setTitle($pdfName);
        $pdf->setUserId($user);

        $this->entityManager->persist($pdf);
        $this->entityManager->flush();

        return $pdf;
    }
}