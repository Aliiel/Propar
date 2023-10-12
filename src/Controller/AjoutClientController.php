<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Utilisateur;
use App\Form\AjoutClientFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjoutClientController extends AbstractController
{
    #[Route('/ajout/client', name: 'app_ajout_client')]

    public function ajouter(Request $request, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();

        $client = new Client();
        $client->setUtilisateur($user); 
        $form = $this->createForm(AjoutClientFormType::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        
            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash('success', 'Le client a été ajouté avec succès.');

            return $this->redirectToRoute('app_ajout_operation'); // Redirigez vers la liste des clients ou une autre page
        }

        return $this->render('ajout_client/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
