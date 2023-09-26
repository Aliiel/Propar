<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartjsController extends AbstractController
{
    /**
     * @Route("/chartjs", name="chartjs")
     */
    public function index(ChiffreAffairesController $chiffreAffairesController, ChartBuilderInterface $chartBuilder): Response
    {
        // Utilisez la méthode chiffreAffaires de ChiffreAffairesController pour récupérer les données
        $response = $this->forward(ChiffreAffairesController::class . '::chiffreAffaires');
    //     $doctrine = $this->getDoctrine();
    // $response = $chiffreAffairesController->chiffreAffaires($doctrine);

        // Décodez les données JSON renvoyées par chiffreAffaires
        $chiffreAffairesData = json_decode($response->getContent(), true);

        $labels = [];
    $data = [];

    if ($chiffreAffairesData !== null) {
    $labels = $chiffreAffairesData['labels'];
    $data = $chiffreAffairesData['values'];
    }

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Chiffre d\'affaires',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $data,
                ],
            ],
        ]);

        $chart->setOptions([]);

        return $this->render('chartjs/index.html.twig', [
            'controller_name' => 'ChartjsController',
            'chart' => $chart,
        ]);
    }
}
