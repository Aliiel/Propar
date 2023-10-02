<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use App\Entity\Utilisateur;

class ChiffreAffairesController extends AbstractController
{
    #[Route('/chiffre/affaires', name: 'app_chiffre_affaires')]
    
    public function chiffreAffaires(Request $request, EntityManagerInterface $em, ChartBuilderInterface $chartBuilder): Response
{
    // Construisez votre requête pour récupérer toutes les opérations finies
    $query = $em->createQuery(
        'SELECT o.date_realisation, o.type AS total
        FROM App\Entity\Operation o
        WHERE o.etat = 2
        ORDER BY o.date_realisation'
    );

    $results = $query->getResult();

     // Récupérez la liste des utilisateurs depuis la base de données
     $utilisateurs = $em->getRepository(Utilisateur::class)->findAll();

    // Calcul du chiffre d'affaires total pour toutes les opérations finies
    $resultss = array_sum(array_map(fn($result) => $result['total'], $results));

    // Construisez le diagramme en bâton
    $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
    $chart->setData([
        'labels' => array_map(fn($result) => $result['date_realisation']->format('Y-m-d'), $results),
        'datasets' => [
            [
                'label' => 'Chiffre d\'affaires',
                'backgroundColor' => 'rgb(255, 99, 132)',
                'borderColor' => 'rgb(255, 99, 132)',
                'data' => array_map(fn($result) => $result['total'], $results),
            ],
        ],
    ]);

    $chart->setOptions([]);

    return $this->render('chartjs/index.html.twig', [
        'chart' => $chart,
        'results' => $results,
        'resultss' => $resultss,
        'utilisateurs' => $utilisateurs
    ]);
}
}
