<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InterfaceUtilisateurController extends AbstractController
{
    #[Route('/interface/utilisateur', name: 'app_interface_utilisateur')]
    public function index(): Response
    {
        return $this->render('interface_utilisateur/index.html.twig', [
            'controller_name' => 'InterfaceUtilisateurController',
        ]);
    }
}
