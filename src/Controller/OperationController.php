<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OperationController extends AbstractController
{
    
    #[Route('/operations', name:'afficher_operations')]

    public function afficherOperations()
    {
        return $this->render('operation/afficher_operations.html.twig');
    }
}
