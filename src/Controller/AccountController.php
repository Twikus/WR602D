<?php

namespace App\Controller;

use App\Form\AccountFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Persistence\ManagerRegistry;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function account(Request $request, UserInterface $user, ManagerRegistry $doctrine)
    {
        $form = $this->createForm(AccountFormType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Vos informations ont été mises à jour.');
        }

        return $this->render('account/index.html.twig', [
            'accountForm' => $form->createView(),
        ]);
    }
}

