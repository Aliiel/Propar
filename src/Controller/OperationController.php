<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Entity\Client;
use App\Entity\Gerer;
use App\Entity\Utilisateur;
use App\Form\OperationType;
use App\Controller\PdfGeneratorController;
use App\Repository\GererRepository;
use App\Repository\OperationRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\DBAL\Driver\Mysqli\Initializer\Options as InitializerOptions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/operation')]

class OperationController extends AbstractController

{
    private $pdfGeneratorController;

    public function __construct(PdfGeneratorController $pdfGeneratorController)
    {
        $this->pdfGeneratorController = $pdfGeneratorController;
    }


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
    public function edit(Request $request, Operation $operation, EntityManagerInterface $entityManager, int $id, UtilisateurRepository $utilisateurRepository): Response

    {
        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            


            return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
        }
        $utilisateurs = $utilisateurRepository->findAll();
        // ajouter une liste ici des utlisateurs avec un bouton 'modifier' pour utiliser setUtilisateurKey() et modifier la ligne Gerer de l'opération avec l'id de l'URL

        return $this->render('operation/edit.html.twig', [
            'operation' => $operation,
            'utilisateurs' => $utilisateurs,
            'form' => $form,
        ]);
    }

    

    #[Route('/{id}/closed', name: 'app_operation_closed', methods: ['GET'])]
    
    public function closed(Operation $operation, EntityManagerInterface $entityManager, MailerInterface $mailer): Response

    {


        $operation->setEtat(2);
        $entityManager->persist($operation);
        $entityManager->flush();

        $pdfFilePath = $this->pdfGeneratorController->generateInvoicePdf($operation);

        $email = (new Email())
            ->from('no-reply@propar.com')
            ->to($operation->getClient()->getEmail())
            ->subject('Time for Symfony Mailer!')
            ->text('Votre opération de nettoyage vient d\'être terminée ! Merci de l\'avoir effectué auprès de nos services. Vous pouvez trouver ci-joint la facture récapitulative de notre prestation.')
            ->attachFromPath($pdfFilePath, 'facture.pdf', 'application/pdf');

        $mailer->send($email);

        unlink($pdfFilePath);


        return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
    }   

 
    #[Route('/{id}/changed', name: 'app_id_changed', methods: ['POST'])]
public function changed(
    Request $request,
    Operation $operation,
    EntityManagerInterface $entityManager,
    GererRepository $gererRepository
): Response {
    // Récupérez l'ID de l'utilisateur sélectionné depuis le formulaire
    $nouvelUtilisateurId = $request->request->get('utilisateur'); // Assurez-vous que le nom correspond à l'input de la liste déroulante
    var_dump($nouvelUtilisateurId);
    // Récupérez la relation Gerer associée à cette opération
    $gerer = $gererRepository->findOneBy(['operation_key' => $operation]);

    // Si la relation Gerer n'existe pas, créez-la
    if (!$gerer) {
        $gerer = new Gerer();
        $gerer->setOperationKey($operation);
    }

    // Récupérez l'entité Utilisateur correspondant à cet ID
    $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($nouvelUtilisateurId);

    if (!$utilisateur) {
        throw $this->createNotFoundException('Utilisateur non trouvé pour cet ID');
    }

    // Mettez à jour la relation utilisateur_key de l'entité Gerer avec l'entité Utilisateur
    $gerer->setUtilisateurKey($utilisateur);
    $entityManager->persist($gerer); // Vous devez peut-être ajouter cette ligne si vous créez un nouvel objet Gerer
    $entityManager->flush();

    return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
}

    

    #[Route('/{id}', name: 'app_operation_delete', methods: ['POST'])]
    public function delete(Operation $operation, Gerer $gerer, EntityManagerInterface $entityManager): Response

    {

            $operation->getId();
            $gerer->getId();

            var_dump($gerer);
            $entityManager->remove($gerer);
            $entityManager->remove($operation);
            $entityManager->flush();

        return $this->redirectToRoute('app_accueil', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/mes_operations/{id}', name: 'app_my_operation', methods: ['GET'])]
public function myOperation(int $id, EntityManagerInterface $entityManager): Response
{
    // Récupérez toutes les relations Gerer liées à cet utilisateur
    $gerers = $entityManager->getRepository(Gerer::class)->findBy(['utilisateur_key' => $id]);
    
    // Créez un tableau pour stocker les opérations liées à chaque relation Gerer
    $operations = [];
    $etatOperation = null;
    $typeOperation = null;

    foreach ($gerers as $gerer) {
        $operation = $gerer->getOperationKey();
        if ($operation) {
            $operations[] = $operation;
            $etatOperation = $operation->getEtat();
            $typeOperation = $operation->getType();
        }
    }

    // Maintenant, vous avez l'état et le type de la première opération associée à cet utilisateur

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

    return $this->render('operation/myoperation.html.twig', [
        'operations' => $operations,
        'etatOperation' => $etatOperation,
        'typeOperation' => $typeOperation,
    ]);
}

}



// public function generateInvoicePdf(Operation $operation)
// {
//     // Créez une instance de Dompdf
//    

// }

