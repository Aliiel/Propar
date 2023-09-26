<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChiffreAffairesController extends AbstractController
{
    #[Route('/chiffre/affaires', name: 'app_chiffre_affaires')]
    
    public function chiffreAffaires(EntityManagerInterface $em, ChartBuilderInterface $chartBuilder): Response
    {
      

        // // Exécutez une requête pour calculer le total de chaque type d'opération
        $query = $em->createQuery(
            'SELECT SUM(o.type)
            FROM App\Entity\Operation o
            WHERE o.etat = 2'
        );

        // Obtenez les résultats de la requête
        $resultss = $query->getSingleScalarResult();

        // var_dump($resultss);

        // Maintenant, $results contient le total de chaque type d'opération
        // Vous pouvez les afficher, les manipuler ou les transmettre à la vue
             // Récupérez les données depuis la base de données (dates et chiffres d'affaires)
        $query = $em->createQuery(
            'SELECT o.date_realisation, o.type AS total
            FROM App\Entity\Operation o
            WHERE o.etat = 2
            ORDER BY o.date_realisation'
        );

        $results = $query->getResult();

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
            'resultss' => $resultss
        ]);
    }
}
