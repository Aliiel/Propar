<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailsUtilisateurController extends AbstractController
{
    #[Route('/details/utilisateur/{id}', name: 'app_details_utilisateur')]
    
    public function update(Request $request, UtilisateurRepository $utilisateur, EntityManagerInterface $entityManager): Response
    {
        // Vérifiez si l'utilisateur a le droit de mettre à jour son profil (par exemple, vérifiez les autorisations ici).

$utilisateurs = $utilisateur->findAll();

dd($utilisateurs);

        // // Récupérez les données du formulaire.
        // $newEmail = $request->request->get('email');
        // $newPassword = $request->request->get('password');
        // $confirmPassword = $request->request->get('confirm_password');

        // // Effectuez les validations nécessaires (par exemple, vérifiez que le nouvel email est unique).

        // // Mettez à jour l'email de l'utilisateur.
        // $utilisateur->setEmail($newEmail);

        // // Si un nouveau mot de passe est fourni et qu'il correspond à la confirmation.
        // if ($newPassword && $newPassword === $confirmPassword) {
        //     // Hasher et mettre à jour le mot de passe.
        //     $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        //     $utilisateur->setPassword($hashedPassword);
        // }

        // // Enregistrez les modifications dans la base de données.
        // $entityManager->flush();

        // return $this->render('details_utilisateur/index.html.twig', [
        
        // ]);
    }
}
