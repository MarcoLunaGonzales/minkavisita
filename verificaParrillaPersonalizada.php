<?php
require("conexion.inc");
require("estilos_visitador.inc");

$gestion=1013;
$ciclo=7;

$sql="select p.cod_med, p.cod_linea, c.cod_especialidad, c.categoria_med, 
(select m.cod_ciudad from medicos m where m.cod_med=p.cod_med)ciudad,count(*) 
from parrilla_personalizada p, 
	categorias_lineas c where p.cod_gestion=$gestion and p.cod_ciclo=$ciclo and p.cod_med=c.cod_med and 
	p.cod_linea=c.codigo_linea group by cod_med, cod_linea, c.cod_especialidad, c.categoria_med order by 5 desc";
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
	$codMed=$dat[0];
	$codLinea=$dat[1];
	$codEspe=$dat[2];
	$catMed=$dat[3];
	$codCiudad=$dat[4];
	
	$sqlGrilla="select gd.frecuencia from grilla g, grilla_detalle gd 
	where g.codigo_grilla=gd.codigo_grilla and g.codigo_linea=$codLinea and g.agencia=$codCiudad and 
	gd.cod_especialidad='$codEspe' and gd.cod_categoria='$catMed'";
	$respGrilla=mysql_query($sqlGrilla);
	$frecuencia=mysql_result($respGrilla,0,0);
	
	echo "$codMed $codLinea $codEspe $catMed $codCiudad FRECUENCIA: $frecuencia<br>";
	
	for($ii=2; $ii<=$frecuencia; $ii++){
		$sqlInsert="insert into parrilla_personalizada (cod_gestion, cod_ciclo, cod_linea, cod_med, numero_visita, orden_visita, cod_mm, cantidad_mm, 
			cod_ma, cantidad_ma) select cod_gestion, cod_ciclo, cod_linea, cod_med, $ii, orden_visita, cod_mm, cantidad_mm, 
			cod_ma, cantidad_ma from parrilla_personalizada where cod_gestion=$gestion and cod_ciclo=$ciclo and cod_med=$codMed and 
			cod_linea=$codLinea and numero_visita=1";
		$respInsert=mysql_query($sqlInsert);
		echo $sqlInsert;

	}
	
}


?>