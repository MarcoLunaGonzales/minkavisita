<?php

ini_set('memory_limit','128M');

 
require("fpdf.php");require("conexion.inc");
$pdf=new FPDF('P','mm','Legal');$pdf->SetMargins(0,0);

$sql = "SELECT gestion, ciclo, territorio, linea, funcionario, supervisor, nro_boleta, direccion1, direccion2, telefono, celular, 
especialidad, fecha_programada_visita, medico, indice, grupo_especial, codbph 
from reporte_cab order by indice";

$resp  = mysql_query($sql);
$indice=0;
while( $dat = mysql_fetch_array($resp) ){
	$gestion          = $dat[0];
	$sqlGestion       = "SELECT nombre_gestion from gestiones where codigo_gestion='$gestion'";
	$respGestion      = mysql_query($sqlGestion);
	$datGestion       = mysql_fetch_array($respGestion);
	$nombreGestion    = $datGestion[0];
	$ciclo            = $dat[1];
	$territorio       = $dat[2];
	$linea            = $dat[3];
	$funcionario      = $dat[4];
	$supervisor       = $dat[5];
	$nro_boleta       = $dat[6];
	$direccion1       = $dat[7];
	$direccion2       = $dat[8];
	$telefono         = $dat[9];
	$celular          = $dat[10];
	$especialidad     = $dat[11];
	$fecha_programada = $dat[12];
	$medico           = $dat[13];
	$codigo           = $dat[14];
	$grupoEspecial    = $dat[15];
	$codBPH           = $dat[16];

	if($indice%3==0){	$y=5; $pdf->AddPage(); $pdf->SetFont('Arial','B',7); 	}
	if($indice%3==1){	$y=113;}
	if($indice%3==2){	$y=221;}	

	$pdf->SetXY(23,$y+5);		$pdf->Cell(0,0,$nombreGestion);
	$pdf->SetXY(23,$y+9);		$pdf->Cell(0,0,$territorio);
	$pdf->SetXY(25,$y+16);		$pdf->Cell(0,0,$funcionario);
	$pdf->SetXY(25,$y+20);		$pdf->Cell(0,0,$supervisor);
	$pdf->SetXY(35,$y+24);		$pdf->Cell(0,0,$nro_boleta);
	
	$pdf->SetXY(57,$y+5);		$pdf->Cell(0,0,$ciclo);
	$pdf->SetXY(57,$y+9);		$pdf->Cell(0,0,$linea);
		
	$pdf->SetXY(100,$y+5);		$pdf->Cell(0,0,$medico);
	
	$pdf->SetFont('Arial','B',5);
	$pdf->SetXY(195,$y+5);		$pdf->Cell(0,0,$codBPH);
	$pdf->SetFont('Arial','B',7);

	
	$pdf->SetXY(104,$y+8);		$pdf->Cell(0,0,$direccion1);
	$pdf->SetXY(104,$y+12);		$pdf->Cell(0,0,$telefono);
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY(104,$y+16);		$pdf->Cell(0,0,"$especialidad - $grupoEspecial");
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(120,$y+19);		$pdf->Cell(0,0,$fecha_programada);
	$pdf->SetXY(145,$y+12);		$pdf->Cell(0,0,$celular);		
	
	$sqlDetalle = "SELECT muestra, cantidad_muestra, material, cantidad_material, tipo from reporte_detalle where indice = $codigo and orden_promocional <= 17 order by orden_promocional";
	$respDetalle=mysql_query($sqlDetalle);
	
	$valorY=38;
	
	while( $datDetalle=mysql_fetch_array($respDetalle) ){
		$muestra          = $datDetalle[0];
		$muestra = substr($muestra,0,40);
		$cantidadMuestra  = $datDetalle[1];
		$material         = $datDetalle[2];
		if($material=="Sin Material Apoyo"){
			$material="- -";
		}
		$material=substr($material,0,30);
		$cantidadMaterial = $datDetalle[3];
		$tipo=$datDetalle[4];
		
		$pdf->SetFont('Arial','B',6);
		$pdf->SetXY(18,$valorY+$y);		$pdf->Cell(0,0,$muestra);
		$pdf->SetXY(115,$valorY+$y);	$pdf->Cell(0,0,$material);
		
		if($tipo==1){
			$pdf->SetXY(84,$valorY+$y);		$pdf->Cell(0,0,$cantidadMuestra);
			$pdf->SetXY(160,$valorY+$y);	$pdf->Cell(0,0,$cantidadMaterial);
		}
		if($tipo==2){
			$pdf->SetXY(95,$valorY+$y);		$pdf->Cell(0,0,$cantidadMuestra);
			$pdf->SetXY(172,$valorY+$y);	$pdf->Cell(0,0,$cantidadMaterial);
		}
		if($tipo==3){
			$pdf->SetXY(105,$valorY+$y);		$pdf->Cell(0,0,$cantidadMuestra);
			$pdf->SetXY(182,$valorY+$y);	$pdf->Cell(0,0,$cantidadMaterial);
		}
		$valorY=$valorY+3.6;
		
		$pdf->SetFont('Arial','B',7);

	}
			
	$indice++;
	
}

$pdf->Output();

?>