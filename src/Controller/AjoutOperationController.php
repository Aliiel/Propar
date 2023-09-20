<?php

namespace App\Controller;

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
        // $this-> denyAccessUnlessGranted()

        //On crée une "nouvelle operation"

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
            $this -> addFlash('succes', 'Operation ajouté avec succes');
            // On redirige
            // return $this-> redirectToRoute('index');
            return $this->redirectToRoute('app_home');
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
