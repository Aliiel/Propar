<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\AjoutClientFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjoutClientController extends AbstractController
{
    #[Route('/ajout/client', name: 'app_ajout_client')]
    public function ajouter(Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(AjoutClientFormType::class, $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash('success', 'Le client a été ajouté avec succès.');

            return $this->redirectToRoute('/ajout/operation'); // Redirigez vers la liste des clients ou une autre page
        }

        return $this->render('ajout_client/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
