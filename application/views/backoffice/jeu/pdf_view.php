<?php
require_once('assets/tcpdf/tcpdf.php');

$pdf = new TCPDF('P','mm','A4');

$pdf->AddPage();

$pdf->SetFont('helvetica','',18);


foreach ($unseul as $jeu){

    $pdf->setY(20);
    $pdf->writeHTML("<h1>".$jeu->jeu_nom."</h1>",true,false,false,false,"C");
    $pdf->setY(40);
    $pdf->writeHTML("<p>Date de sortie : ".$jeu->jeu_date."</p>",true,false,false,false,"C");
    $pdf->writeHTML("<p>Age minimum : ".$jeu->jeu_age."</p>",true,false,false,false,"C");
    $pdf->Image('http://chart.apis.google.com/chart?cht=qr&amp;chs=150x150&amp;chl='.base_url().'jeu/Game/produit/'.$jeu->jeu_id,80,70,50,50);
}

$pdf->Output('jeu.pdf');

?>
