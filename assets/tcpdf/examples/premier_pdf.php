<?php
	require_once('../tcpdf.php');

	$pdf = new TCPDF('P','mm','A4');

	$pdf->AddPage();

	$montexte = '<h1>Bienvenue</h1>';

	$pdf->writeHTML($montexte);

	$pdf->Output('stpavut.pdf');
?>
