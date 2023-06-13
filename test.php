<?php 
include "./fpdf/fpdf.php";
include "./vendor/autoload.php";
use Tets\Oop\ChiffreEnLettre;
{
    $lettre=new ChiffreEnLettre();
    $pdf = new PDF('P','mm','A4');
    $pdf->AddPage();
    $pdf->SetFont('Helvetica','',9);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Helvetica','',12);
    $pdf->Text(10,75,iconv("UTF-8", "CP1252//TRANSLIT", "Réf : REF-MS-001"));
    $pdf->Text(160,75,iconv("UTF-8", "CP1252//TRANSLIT", "Oujda le : 23/02/2023"));
    $pdf->SetFont('Helvetica','b',20);
    $pdf->Text(27,100,iconv("UTF-8", "CP1252//TRANSLIT", "DEMANDE DE REMBOURSEMENT DES FRAIS"));
    $pdf->Text(75,112,iconv("UTF-8", "CP1252//TRANSLIT", "DE DÉPLACEMENT"));
    $pdf->SetFont('Helvetica','b',12);
    $pdf->Text(10,135,"Collaborateur : ");
    $pdf->SetFont('Helvetica','',12);
    $pdf->Text(70,135,"KHALID Loubna");
    $pdf->SetFont('Helvetica','b',12);
    $pdf->Text(10,150,iconv("UTF-8", "CP1252//TRANSLIT", "Lieu de déplacement : "));
    $pdf->SetFont('Helvetica','',12);
    $pdf->Text(70,150,"Oujda");
    $pdf->SetFont('Helvetica','b',12);
    $pdf->Text(10,165,"Objet de la mission : ");
    $pdf->SetFont('Helvetica','',12);
    $pdf->Text(70,165,iconv("UTF-8", "CP1252//TRANSLIT","Séminaire"));
    $pdf->SetFont('Helvetica','b',12);
    $pdf->Text(10,180,iconv("UTF-8", "CP1252//TRANSLIT", "Date : "));
    $pdf->SetFont('Helvetica','',12);
    $pdf->Text(70,180,"23/02/2023");
    $pdf->SetFont('Helvetica','b',12);
    $pdf->Text(15,195,"*");
    $pdf->Text(20,195,iconv("UTF-8", "CP1252//TRANSLIT", "Indemnités : "));
    $pdf->Text(80,195,iconv("UTF-8", "CP1252//TRANSLIT", "2300 DH"));
    $pdf->SetFont('Helvetica','',12);
    $pdf->SetXY(80,205);
    $pdf->MultiCell(100,6,iconv("UTF-8", "CP1252//TRANSLIT", ucfirst($lettre->Conversion(2300))." Dirhams"),0,1,0,false);
    //$pdf->Text(90,205,iconv("UTF-8", "CP1252//TRANSLIT", "$text"));
    $pdf->Text(120,240,"Signature");
    $pdf->Text(120,250,"Salah-dine KHLIFI");
    $pdf->AddPage();
    $pdf->SetMargins(0, 10,5);

    $pdf->SetFont('Helvetica','',12);
    $pdf->Text(10,75,iconv("UTF-8", "CP1252//TRANSLIT", "Réf : REF-MS-0001"));
    $pdf->Text(160,75,iconv("UTF-8", "CP1252//TRANSLIT", "Oujda le : 23/02/2023"));
    $pdf->SetFont('Helvetica','b',20);
    $pdf->Text(88,100,iconv("UTF-8", "CP1252//TRANSLIT", "DECHARGE"));
    $pdf->SetFont('Helvetica','',12);
    $pdf->Ln('30');
    $pdf->SetX('10');
    $pdf->MultiCell(190,9,iconv("UTF-8", "CP1252//TRANSLIT", "Je soussiné Mlle KHALID Loubna titulaire de la CIN n° F1678333 reconnais avoir reçu de la société exen consulting sarl la somme de 2300 DH ".ucfirst($lettre->Conversion(2300))."Dirhams par virement bancaire au titre des remboursements de frais de mon deplacement à Oujda durant le mois de juin 2023"),0,'J');
    $pdf->Output('',"PDF/Demande_Remboursement/Demande_Remboursement_23.pdf",true);
}
?>