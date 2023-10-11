<?php

namespace App\Controller;

use App\Entity\Gerer;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/utilisateur')]
class UtilisateurController extends AbstractController

{
    
    #[Route('/{id}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(UtilisateurRepository $utilisateurRepository, int $id): Response
    {
        $utilisateur = $utilisateurRepository->find($id);

        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, $id, UtilisateurRepository $utilisateurRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {

        $utilisateur = $utilisateurRepository->find($id);

        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $utilisateur->setPassword(
                $userPasswordHasher->hashPassword(
                    $utilisateur,
                    $form->get('plainPassword')->getData()
                    )
                );
            $entityManager->flush();

            return $this->redirectToRoute('app_liste_utilisateur', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }



    #[Route('/{id}/closed', name: 'app_utilisateur_delete', methods: ['DELETE'])]
    public function delete(Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {

        $gererRepository = $entityManager->getRepository(Gerer::class);
        $relatedGererRecords = $gererRepository->findBy(['utilisateur_key' => $utilisateur]);
        foreach ($relatedGererRecords as $relatedGererRecord) {
        $entityManager->remove($relatedGererRecord);
        }
      
        return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
    }
}
