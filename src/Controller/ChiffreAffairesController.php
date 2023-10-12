<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use App\Entity\Utilisateur;

class ChiffreAffairesController extends AbstractController
{
    #[Route('/chiffre/affaires', name: 'app_chiffre_affaires')]
    public function chiffreAffaires(Request $request, EntityManagerInterface $em, ChartBuilderInterface $chartBuilder): Response
    {
        // Récupérez la liste des utilisateurs depuis la base de données
        $utilisateurs = $em->getRepository(Utilisateur::class)->findAll();

        $dateDebut = $request->query->get('dateDebut');
        $dateFin = $request->query->get('dateFin');


        if ($dateDebut && $dateFin) {
        // Construisez votre requête pour récupérer toutes les opérations finies
        $query = $em->createQuery(
            'SELECT o.date_realisation, o.type AS total
            FROM App\Entity\Operation o
            WHERE o.etat = 2
            AND o.date_realisation >= :dateDebut
            AND o.date_realisation <= :dateFin
            ORDER BY o.date_realisation'
        );

        $query->setParameters([
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
        ]);

        $results = $query->getResult();

        // Calcul du chiffre d'affaires total pour toutes les opérations finies
        $resultss = array_sum(array_map(fn($result) => $result['total'], $results));
        } else {
        $results = [];
        $resultss = 0;
        }


        return $this->render('chartjs/index.html.twig', [
            'results' => $results,
            'resultss' => $resultss,
            'utilisateurs' => $utilisateurs,
        ]);
    }
}