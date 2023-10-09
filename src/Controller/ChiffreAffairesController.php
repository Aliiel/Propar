<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\JsonResponse;


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

 // Ajoutez l'action pour filtrer les données
 #[Route('/chiffre/affaires/filtre', name: 'app_chiffre_affaires_filtre', methods: ['GET'])]
 public function chiffreAffairesFiltre(Request $request, EntityManagerInterface $em): JsonResponse
 {
     // Récupérez les paramètres de filtrage du formulaire
     $annee = $request->query->get('date_realisation');
     $typeOperation = $request->query->get('type');
     $utilisateurId = $request->query->get('utilisateur');

     // Construisez votre requête DQL en fonction des filtres
     $queryBuilder = $em->createQueryBuilder()
     ->select('o.dateRealisation AS date_realisation, SUM(o.montant) AS total')
     ->from(Operation::class, 'o')
     ->where('o.etat = 2');

     if ($annee) {
         $queryBuilder->andWhere('YEAR(o.date_realisation) = :annee')
             ->setParameter('annee', $annee);
     }

     if ($typeOperation) {
         $queryBuilder->andWhere('o.type = :typeOperation')
             ->setParameter('typeOperation', $typeOperation);
     }

     if ($utilisateurId) {
         $queryBuilder->join('o.utilisateur', 'u')
             ->andWhere('u.id = :utilisateurId')
             ->setParameter('utilisateurId', $utilisateurId);
     }

     $queryBuilder->groupBy('o.dateRealisation');
     $queryBuilder->orderBy('o.date_realisation');
     $query = $queryBuilder->getQuery();

     $results = $query->getResult();

     // Construisez le tableau de résultats au format JSON
     $data = [];
     foreach ($results as $result) {
         $data[] = [
             'date_realisation' => $result['date_realisation']->format('Y-m-d'),
             'total' => $result['total'],
         ];
     }

     return $this->json($data);
 }
}


