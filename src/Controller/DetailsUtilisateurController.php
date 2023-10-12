<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailsUtilisateurController extends AbstractController
{
    #[Route('/details/utilisateur/{id}', name: 'app_details_utilisateur', methods: ['GET', 'POST'])]
    
    public function update(Request $request, UtilisateurRepository $utilisateurRepository, EntityManagerInterface $entityManager, int $id): Response
    {
      

        return $this->render('details_utilisateur/index.html.twig', [
            // Ajoutez ici les variables à passer au template Twig, le cas échéant.
        ]);
    }
}
