<?php
require_once('assets/tcpdf/tcpdf.php');

$pdf = new TCPDF('P','mm','A4');

$pdf->AddPage();

$pdf->SetFont('helvetica','',18);

$h = 0;
foreach ($tous as $jeu){
    $y = $pdf->GetY();
    $pdf->setY($y +10);
    $pdf->writeHTML("<p>".$jeu->jeu_nom."</p>",true,false,false,false,"C");
    $pdf->setY($y + 20);
    $pdf->writeHTML("<p>Date de sortie : ".$jeu->jeu_date."</p>",true,false,false,false,"C");
    $pdf->writeHTML("<p>Age minimum : ".$jeu->jeu_age."</p>",true,false,false,false,"C");
    $pdf->Image('http://chart.apis.google.com/chart?cht=qr&amp;chs=150x150&amp;chl='.base_url().'jeu/Game/produit/'.$jeu->jeu_id,0,$y,40,40);
    $h += $y;
    if($h > 297){
        $h = 0;
        $pdf->addPage();
    }
}

$pdf->Output('jeu.pdf');

?>
