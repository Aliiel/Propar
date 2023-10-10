<?php

namespace App\Controller;


use App\Entity\Gerer;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListeOperationController extends AbstractController
{
    #[Route('/liste/operation', name: 'app_liste_operation')]
    public function index(Gerer $gerer, UtilisateurRepository $utilisateurRepository): Response
    {
            // Récupérez tous les enregistrements de Gerer
        $gerers = $gerer->getId();

        // Créez un tableau pour stocker les informations complètes
        $bilan = [];

        // Parcourez les enregistrements de Gerer
        foreach ($gerer as $gerers) {
            // Obtenez l'objet Operation associé
            $operation = $gerers->getOperationKey();

            // Obtenez l'objet Utilisateur associé
            $utilisateur = $gerers->getUtilisateurKey();

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



        return $this->render('liste_operation/index.html.twig', [
            'bilan' => $bilan,
        ]);
    }
}
