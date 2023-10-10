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

        $pdfOptions = new Options();
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $pdfOptions->set('isPhpEnabled', true);
        
        $dompdf = new Dompdf($pdfOptions);
        
            // Générez le contenu HTML de la facture (vous devez implémenter cette fonction)
            $invoiceHtml = $this->renderView('operation/facture.html.twig', [
                'operation' => $operation,
            ]);
        
            // Chargez le contenu HTML dans Dompdf
            $dompdf->loadHtml($invoiceHtml);
        
            $pdfFileName = tempnam(sys_get_temp_dir(), 'invoice_');
            file_put_contents($pdfFileName, $dompdf->output());
        
            // Retournez le chemin du fichier temporaire
            return $pdfFileName;
        
    }
}
