<?php
require('fpdf.php');
require('conexion.inc');
$pdf=new FPDF('P','mm','Legal');
$pdf->SetMargins(0,0);
$sql="select gestion, ciclo, territorio, linea, funcionario, supervisor, nro_boleta, direccion1, direccion2, telefono, celular, 
	especialidad, fecha_programada_visita, medico, indice from reporte_cab order by indice";
$resp=mysql_query($sql);
$indice=0;
while($dat=mysql_fetch_array($resp)){
	$gestion=$dat[0];
	$ciclo=$dat[1];
	$territorio=$dat[2];
	$linea=$dat[3];
	$funcionario=$dat[4];
	$supervisor=$dat[5];
	$nro_boleta=$dat[6];
	$direccion1=$dat[7];
	$direccion2=$dat[8];
	$telefono=$dat[9];
	$celular=$dat[10];
	$especialidad=$dat[11];
	$fecha_programada=$dat[12];
	$medico=$dat[13];
	$codigo=$dat[14];

	if($indice%3==0){	$y=0; $pdf->AddPage(); $pdf->SetFont('Arial','B',7); 	}
	if($indice%3==1){	$y=113;}
	if($indice%3==2){	$y=226;}	

	$pdf->SetXY(20,$y+5);		$pdf->Cell(0,0,$gestion);
	$pdf->SetXY(20,$y+11);		$pdf->Cell(0,0,$territorio);
	$pdf->SetXY(20,$y+16);		$pdf->Cell(0,0,$funcionario);
	$pdf->SetXY(20,$y+21);		$pdf->Cell(0,0,$supervisor);
	$pdf->SetXY(28,$y+26);		$pdf->Cell(0,0,$nro_boleta);
	$pdf->SetXY(58,$y+5);		$pdf->Cell(0,0,$ciclo);
	$pdf->SetXY(58,$y+11);		$pdf->Cell(0,0,$linea);
		
	$pdf->SetXY(100,$y+5);		$pdf->Cell(0,0,$medico);
	$pdf->SetXY(100,$y+11);		$pdf->Cell(0,0,$direccion1);
	$pdf->SetXY(100,$y+16);		$pdf->Cell(0,0,$direccion2);
	$pdf->SetXY(100,$y+20);		$pdf->Cell(0,0,$telefono);
	$pdf->SetXY(100,$y+24);		$pdf->Cell(0,0,$especialidad);
	$pdf->SetXY(120,$y+29);		$pdf->Cell(0,0,$fecha_programada);
	$pdf->SetXY(145,$y+20);		$pdf->Cell(0,0,$celular);		
	
	$sqlDetalle="select muestra, cantidad_muestra, material, cantidad_material from reporte_detalle where
				indice=$codigo order by orden_promocional";
	$respDetalle=mysql_query($sqlDetalle);
	$valorY=41.5;
	while($datDetalle=mysql_fetch_array($respDetalle)){
		$muestra=$datDetalle[0];
		$cantidadMuestra=$datDetalle[1];
		$material=$datDetalle[2];
		$cantidadMaterial=$datDetalle[3];
		
		$pdf->SetXY(10,$valorY+$y);		$pdf->Cell(0,0,$muestra);
		$pdf->SetXY(88,$valorY+$y);		$pdf->Cell(0,0,$cantidadMuestra);
		$pdf->SetXY(102,$valorY+$y);	$pdf->Cell(0,0,$material);
		$pdf->SetXY(157,$valorY+$y);	$pdf->Cell(0,0,$cantidadMaterial);
		$valorY=$valorY+4.2;
	}
			
	$indice++;
	
}
$pdf->Output();
?>