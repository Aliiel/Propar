<?php

namespace App\Controller;

use App\Entity\Operation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(EntityManagerInterface $em, ManagerRegistry $doctrine): Response
    {


        $operations = $doctrine
        ->getRepository(Operation::class)
        ->findBy(['etat' => 1]);

        $operationsEnds = $doctrine
        ->getRepository(Operation::class)
        ->findBy(['etat' => 2]);

        return $this->render('accueil/index.html.twig', [
            'operations' => $operations,'operationsEnds' => $operationsEnds,
        ]);
    }
}
