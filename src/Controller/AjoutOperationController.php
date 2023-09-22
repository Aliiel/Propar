<?php

namespace App\Controller;

use App\Entity\Gerer;
use App\Entity\Operation;
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
        $roles = $user -> getRoles(); 
        var_dump($roles);
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


   
   // Vérifiez si l'utilisateur a déjà 3 opérations avec état = 1 enregistrées
   $errorMessage = '';

   if (in_array('EXPERT', $roles) && $userOperationCount >= 5) {
       $errorMessage = 'Vous avez atteint votre limite de 5 opérations en cours.';
   } elseif (in_array('SENIOR', $roles) && $userOperationCount >= 3) {
       $errorMessage = 'Vous avez atteint votre limite de 3 opérations en cours.';
   } elseif (in_array('APPRENTI', $roles) && $userOperationCount > 1) {
       $errorMessage = 'Avec le rôle APPRENTI, vous avez atteint votre limite de 1 opération en cours.';
   }
   
   if (!empty($errorMessage)) {
       $this->addFlash('danger', $errorMessage);
   }
// if ($this->isGranted('ROLE_SENIOR') && $userOperationCount >= 3) {
//     $this->addFlash('danger', 'Vous avez déjà enregistré 3 opérations avec état = 1. Vous ne pouvez pas enregistrer plus.');
//     var_dump($userOperationCount);
// }
// if ($this->isGranted('ROLE_APPRENTI') && $userOperationCount >= 1) {
//     $this->addFlash('danger', 'Vous avez déjà enregistré 1 opération avec état = 1. Vous ne pouvez pas enregistrer plus.');
//     var_dump($userOperationCount);
// }

        $operation = new Operation();

         

//créer le formulaire
        $operationForm = $this-> createForm(OperationFormType::class, $operation);



        //on traite la requête du formulaire 

        $operationForm -> handleRequest($request);

        // dd($operationForm);

        //On verifie si le form est soumis Et valide

        if($operationForm ->isSubmitted() && $operationForm->isValid()){
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

        // return $this->renderForm('ajout_operation/index.html.twig', [
        //     'operationForm' => $operationForm -> createView()
        // ]);

        // return $this->renderForm('ajout_operation/index.html.twig', 
        //     compact('operationForm'));

           return $this->render('ajout_operation/index.html.twig', [
        'operationForm' => $operationForm->createView()]);
    }
 //['operationForm' => $operationForm] = compact()
   

}
