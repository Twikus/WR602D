<?php
namespace App\Tests\Entity;

use App\Entity\Pdf;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class PdfTest extends TestCase
{
    public function testGetterAndSetter()
    {
        // Création d'une instance de l'entité User
        $pdf = new Pdf();

        // Définition de données de test
        $title = 'title';
        $created_at = new \DateTimeImmutable();
        $user = new User();

        // Utilisation des setters
        $pdf->setTitle($title);
        $pdf->setCreatedAt($created_at);
        $pdf->setUserId($user);

        // Vérification des getters
        $this->assertEquals($title, $pdf->getTitle());
        $this->assertEquals($created_at, $pdf->getCreatedAt());
        $this->assertEquals($user, $pdf->getUserId());

    }
}