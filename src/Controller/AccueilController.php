<?php

namespace App\Controller;

use App\Entity\Gerer;
use App\Entity\Operation;
use App\Repository\GererRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function bilan(GererRepository $gererRepository, UtilisateurRepository $utilisateurRepository): Response
    {

        $experts = $utilisateurRepository->findBy(['roles' => 'EXPERT']);
        $seniors = $utilisateurRepository->findBy(['roles' => 'SENIOR']);
        $apprentis = $utilisateurRepository->findBy(['roles' => 'APPRENTI']);
        
        // Récupérez tous les enregistrements de Gerer
        $gerers = $gererRepository->findAll();

        // Créez un tableau pour stocker les informations complètes
        $bilan = [];

        // Parcourez les enregistrements de Gerer
        foreach ($gerers as $gerer) {
            // Obtenez l'objet Operation associé
            $operation = $gerer->getOperationKey();
        
            // Obtenez l'objet Utilisateur associé
            $utilisateur = $gerer->getUtilisateurKey();
        
            // Vérifiez si l'opération et l'utilisateur existent
            if ($operation && $utilisateur) {
                // Extrayez les informations nécessaires
                $bilan[] = [
                    'operation' => [
                        'id' => $operation->getId(),
                        'type' => $operation->getType(),
                        'etat' => $operation->getEtat(),
                        // Ajoutez d'autres propriétés de l'opération au besoin
                    ],
                    'utilisateur' => [
                        'id' => $utilisateur->getId(),
                        'nom' => $utilisateur->getNom(),
                        'prenom' => $utilisateur->getPrenom(),
                        'roles' => $utilisateur->getRoles(),
                        // Ajoutez d'autres propriétés de l'utilisateur au besoin
                    ],
                ];
            }
        }

        usort($bilan, function ($a, $b) {
            return strcmp($a['utilisateur']['nom'], $b['utilisateur']['nom']);
        });


        // Passez le tableau $bilan à la vue pour l'affichage
        return $this->render('accueil/index.html.twig', [
            'bilan' => $bilan,
            'experts' => $experts,
            'seniors' => $seniors,
            'apprentis' => $apprentis,
        ]);
    }
}


