<?php
require("conexion.inc");
require("funciones.php");
require("estilos_reportes.inc");
$rpt_territorio=$_GET["rpt_territorio"];
$rpt_nombreTerritorio=$_GET['rpt_nombreTerritorio'];
$rpt_espe=$_GET['rpt_espe'];
$rpt_nombreEspe=$_GET['rpt_espe1'];
$rpt_espe1=str_replace("`","'",$rpt_espe);
$rpt_cat=$_GET['rpt_cat'];
$rpt_nombreCat1=$_GET['rpt_cat1'];
$rpt_cat1=str_replace("`","'",$rpt_cat);

echo "<table border='0' class='textotit' align='center'><tr><th>Productos Mas Utilizados segun Encuesta Resumen<br>
Territorio: $rpt_nombreTerritorio  Especialidad: $rpt_nombreEspe Categoria: $rpt_cat1
</th></tr></table></center><br>";

echo "<table border='0' class='textomini' align='center'>";
echo "<tr><td>Leyenda:</td><th bgcolor='#ff0000'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th align='Left'>Productos no incluidos en encuesta</th></td></tr>";
echo "</table>";



for($i=1;$i<=4;$i++){
	echo "<table border=1 class='texto' cellspacing='0' id='main' align='center' width='55%'>";
	if($i==1){
		$nombreFrecuencia="Alta";
	}
	if($i==2){
		$nombreFrecuencia="Media";
	}
	if($i==3){
		$nombreFrecuencia="Baja";
	}
	if($i==4){
		$nombreFrecuencia="No Utiliza";
	}
	echo "<tr><th colspan=5>$nombreFrecuencia</th></tr>";
	echo "<tr><th>&nbsp;</th><th>Producto</th><th>Cantidad</th><th>Total</th><th>%</th></tr>";
	$sql="select (select concat(mm.descripcion,' ',mm.presentacion) from muestras_medicas mm where mm.codigo =  e.cod_prod), 
				e.`cod_prod`, count(e.`frecuencia`) from `encuestamedicos2` e, `medicos_a_encuestar2` me, 
				medicos m where e.`cod_med` = me.`cod_med` and me.`cod_espe` in ($rpt_espe1) 
				and e.cod_med = m.cod_med and me.`cod_cat` in ($rpt_cat1) and
				me.`cod_med` = m.cod_med and m.`cod_ciudad` in ($rpt_territorio) and e.`frecuencia` = $i group by e.`cod_prod` order by 3 desc;";
	$resp=mysql_query($sql);
	$indice=1;
	$sumaFrecuencia=0;
	while($dat=mysql_fetch_array($resp)){
		$nombreProducto=$dat[0];
		$codProducto=$dat[1];
		
		$sqlVeriProducto="select * from `producto_especialidad` p where p.`codigo_linea`=1021 and p.`cod_especialidad` in ($rpt_espe1)
			and p.`codigo_mm`='$codProducto'";
		$respVeriProducto=mysql_query($sqlVeriProducto);
		$numFilasVeri=mysql_num_rows($respVeriProducto);
		if($numFilasVeri>0){
			$colorFila="";
		}else{
			$colorFila="#ff0000";
		}
		$frecuencia=$dat[2];
		$sumaFrecuencia=$sumaFrecuencia+$frecuencia;
		
		$sqlTotalMed="select count(*) from `medicos_a_encuestar2` me, `medicos` m 
			where m.`cod_med`=me.`cod_med` and me.`cod_espe` in ($rpt_espe1) and 
				me.`cod_cat` in ($rpt_cat1) and m.`cod_ciudad` in ($rpt_territorio)";
		$respTotalMed=mysql_query($sqlTotalMed);
		$totalMedicos=mysql_result($respTotalMed,0,0);
		
		$porcentaje=($frecuencia/$totalMedicos)*100;
		$porcentaje=redondear2($porcentaje);
		
		echo "<tr bgcolor='$colorFila'><td>$indice</td><td>$nombreProducto</td><td align='center'>$frecuencia</td>
				<td align='center'>$totalMedicos</td><td align='center'>$porcentaje</td></tr>";
		$indice++;
	}	
	echo "<tr><th colspan=2>Total</th><th>$sumaFrecuencia</th></tr>";
	echo "</table><br>";
}



?>