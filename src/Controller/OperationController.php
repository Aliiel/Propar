<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Entity\Client;
use App\Entity\Gerer;
use App\Entity\Utilisateur;
use App\Form\OperationType;
use App\Repository\GererRepository;
use App\Repository\OperationRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/operation')]

class OperationController extends AbstractController

{
    #[Route('/{id}', name: 'app_operation_show', methods: ['GET'])]
    public function show(Operation $operation): Response

    {

        $client = $operation->getClient();

        $nomClient = $client->getNom();
        $prenomClient = $client->getPrenom();


        $etatOperation = $operation->getEtat();

        $typeOperation = $operation->getType();

        if ($typeOperation == 1000) {

            $typeOperation = "Petite opération - Coût : 1 000 €";

        } elseif ($typeOperation == 2500) {

            $typeOperation = "Moyenne opération - Coût : 2 500 €";

        } else {

            $typeOperation = "Grosse opération - Coût : 5 000 €";
        }

        if ($etatOperation == 1) {

            $etatOperation = "En cours";

        } else {

            $etatOperation = "Terminée";
        }

        return $this->render('operation/show.html.twig', [
            'operation' => $operation,
            'nomClient' => $nomClient,
            'prenomClient' => $prenomClient,
            'etatOperation' => $etatOperation,
            'typeOperation' => $typeOperation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_operation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Operation $operation, EntityManagerInterface $entityManager): Response

    {
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('operation/edit.html.twig', [
            'operation' => $operation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/closed', name: 'app_operation_closed', methods: ['GET'])]

    public function closed(Request $request, Operation $operation, EntityManagerInterface $entityManager): Response

    {
        
        $operation->setEtat(2);
        $entityManager->persist($operation);
        $entityManager->flush();

        return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);

    }   

    #[Route('/{id}', name: 'app_operation_delete', methods: ['POST'])]
    public function delete(Request $request, Operation $operation, EntityManagerInterface $entityManager): Response

    {
        if ($this->isCsrfTokenValid('delete'.$operation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($operation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/mes_operations/{id}', name: 'app_my_operation', methods: ['GET'])]
    public function myOperation(int $id, EntityManagerInterface $entityManager, Operation $operation): Response
    {

        $etatOperation = $operation->getEtat();

            $typeOperation = $operation->getType();
    
            if ($typeOperation == 1000) {
    
                $typeOperation = "Petite opération - Coût : 1 000 €";
    
            } elseif ($typeOperation == 2500) {
    
                $typeOperation = "Moyenne opération - Coût : 2 500 €";
    
            } else {
    
                $typeOperation = "Grosse opération - Coût : 5 000 €";
            }
    
            if ($etatOperation === 1) {
    
                $etatOperation = "En cours";
    
            } else {
    
                $etatOperation = "Terminée";
            }

        // Récupérez toutes les relations Gerer liées à cet utilisateur
        $gerers = $entityManager->getRepository(Gerer::class)->findBy(['utilisateur_key' => $id]);
        
        // Créez un tableau pour stocker les opérations liées à chaque relation Gerer
        $operations = [];
    
        foreach ($gerers as $gerer) {
            $operation = $gerer->getOperationKey();
            if ($operation) {
                $operations[] = $operation;
            }
        }
    
        return $this->render('operation/myoperation.html.twig', [
            'operations' => $operations,
            'etatOperation' => $etatOperation,
            'typeOperation' => $typeOperation,
        ]);
    }
}