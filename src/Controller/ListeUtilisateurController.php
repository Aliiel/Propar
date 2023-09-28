<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListeUtilisateurController extends AbstractController
{
    #[Route('/liste/utilisateur', name: 'app_liste_utilisateur')]
    public function index(ManagerRegistry $doctrine): Response
    {

        $utilisateurs = $doctrine
        -> getRepository(Utilisateur::class)
        -> findBy([],['nom' => 'ASC']);
     


        return $this->render('liste_utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }
}
