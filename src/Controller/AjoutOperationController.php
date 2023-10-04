<?php

namespace App\Controller;

use App\Entity\Gerer;
use App\Entity\Operation;
use App\Entity\Utilisateur;
use App\Form\OperationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjoutOperationController extends AbstractController
{
    #[Route('/ajout/operation', name: 'app_ajout_operation')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {

        $user = $this->getUser(); // récupère l'utilisateur de la session
        $roles = $user->getRoles(); 
        
        //On crée une "nouvelle operation"

       // Comptez le nombre d'opérations associées à l'utilisateur connecté avec état = 1
       $userOperationCount = $em->createQuery('
       SELECT COUNT(o.id)
       FROM App\Entity\Operation o
       JOIN o.gerers g
       WHERE g.utilisateur_key = :user
       AND o.etat = 1
   ')
   ->setParameter('user', $user)
   ->getSingleScalarResult();



   $userRole = $roles[0]; // Le rôle de l'utilisateur
   
   $errorMessage = '';
   
   $allowedRoles = ['EXPERT', 'SENIOR', 'APPRENTI']; // Les rôles autorisés
   
   if (in_array($userRole, $allowedRoles)) {
       // Vérifiez les limites en fonction du rôle
       $maxOperations = [
           'EXPERT' => 5,
           'SENIOR' => 3,
           'APPRENTI' => 1,
       ];
   
       if ($userOperationCount >= $maxOperations[$userRole]) {
           $errorMessage = sprintf(
               'Vous avez atteint votre limite de %d opérations en cours.',
               $maxOperations[$userRole]
           );
           return $this->redirectToRoute('app_accueil');
       }
   }
   
   if (!empty($errorMessage)) {
       $this->addFlash('danger', $errorMessage);
   }



        $operation = new Operation();

         

//créer le formulaire
        $operationForm = $this-> createForm(OperationFormType::class, $operation);



        //on traite la requête du formulaire 

        $operationForm -> handleRequest($request);

        // dd($operationForm);

        //On verifie si le form est soumis Et valide

        if($operationForm ->isSubmitted() && $operationForm->isValid()){

                $operation->setEtat(1);
                $em->persist($operation);
                $em->flush();

               // Créez une nouvelle instance de Gerer pour enregistrer la relation
               $gerer = new Gerer();
               $gerer->setOperationKey($operation);
               $gerer->setUtilisateurKey($user);
               

               $em->persist($gerer);
               $em->flush();
               $this->addFlash('success', 'Opération ajoutée avec succès');
        }



           return $this->render('ajout_operation/index.html.twig', [
        'operationForm' => $operationForm->createView()]);
    }
   

}


// if ($operationForm->isSubmitted() && $operationForm->isValid()) {
//     // Récupérez l'utilisateur existant depuis la base de données en utilisant son ID
//     $entityManager = $this->getDoctrine()->getManager();
//     $utilisateurRepository = $entityManager->getRepository(Utilisateur::class);
//     $utilisateur = $utilisateurRepository->find($userId);

//     if (!$utilisateur) {
//         // Traitez le cas où l'utilisateur n'existe pas (par exemple, affichez un message d'erreur)
//         // Vous pouvez également rediriger l'utilisateur ou prendre d'autres mesures en conséquence
//     } else {
//         // Vérifiez s'il existe déjà une instance de Gerer pour cette opération
//         $gererRepository = $entityManager->getRepository(Gerer::class);
//         $gerer = $gererRepository->findOneBy([
//             'operationKey' => $operation,
//             'utilisateurKey' => $utilisateur,
//         ]);

//         if (!$gerer) {
//             // S'il n'existe pas de relation Gerer, créez-en une nouvelle
//             $gerer = new Gerer();
//             $gerer->setOperationKey($operation);
//             $gerer->setUtilisateurKey($utilisateur);
//             $entityManager->persist($gerer);
//         }

//         // Continuez avec la persistance de l'opération
//         $entityManager->persist($operation);
//         $entityManager->flush();

//         // Reste du code pour traiter le formulaire et persister l'opération, si nécessaire
//     }
// }