<?php

namespace App\Controller;

use App\Entity\Operation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGeneratorController extends AbstractController
{
    #[Route('/pdf/generator', name: 'app_pdf_generator')]
    public function generateInvoicePdf(Operation $operation): string
    {
        $client = $operation->getClient();

        $nomClient = $client->getNom();
        $prenomClient = $client->getPrenom();
        $adresseClient = $client->getAdresse();
        $emailClient = $client->getEmail();

        $dateOperation = $operation->getDateRealisation();
        $typeOperation = $operation->getType();

        if ($typeOperation == 1000) {

            $typeOperation = "Petite opération - Coût : 1 000 €";

        } elseif ($typeOperation == 2500) {

            $typeOperation = "Moyenne opération - Coût : 2 500 €";

        } else {

            $typeOperation = "Grosse opération - Coût : 5 000 €";
        }

        $pdfOptions = new Options();
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $pdfOptions->set('isPhpEnabled', true);
        
        $dompdf = new Dompdf($pdfOptions);
        
            // Générez le contenu HTML de la facture (vous devez implémenter cette fonction)
            $invoiceHtml = $this->renderView('operation/facture.html.twig', [
            'operation' => $operation,
            'nomClient' => $nomClient,
            'prenomClient' => $prenomClient,
            'typeOperation' => $typeOperation,
            'adresseClient' => $adresseClient,
            'emailClient' => $emailClient,
            'dateOperation' => $dateOperation,
            ]);
        
            // Chargez le contenu HTML dans Dompdf
            $dompdf->loadHtml($invoiceHtml);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
        
            $pdfFileName = tempnam(sys_get_temp_dir(), 'invoice_');
            file_put_contents($pdfFileName, $dompdf->output());
        
            // Retournez le chemin du fichier temporaire
            return $pdfFileName;
        
    }
}
