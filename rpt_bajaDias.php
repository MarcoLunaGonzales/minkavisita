<?php
require("conexion.inc");
require("estilos_reportes.inc");
$rpt_gestion=$_GET["rpt_gestion"];
$rpt_ciclo=$_GET["rpt_ciclo"];
$rpt_territorio=$_GET["rpt_territorio"];

$sql_nombreGestion=mysql_query("select nombre_gestion from gestiones where codigo_gestion=$rpt_gestion");
$dat_nombreGestion=mysql_fetch_array($sql_nombreGestion);
$nombreGestion=$dat_nombreGestion[0];
echo "<table border='0' class='textotit' align='center'><tr><th>Baja de Dias<br>
Gestion: $nombreGestion Ciclo: $rpt_ciclo
</th></tr></table></center><br>";

echo "<table border=1 class='texto' cellspacing='0' id='main' align='center'>";
echo "<tr><th>Territorio</th><th>Dia Contacto</th><th>Turno</th><th>Linea</th><th>Motivo</th><th>Visitador</th></tr>";
$sql="select c.`descripcion`, b.`dia_contacto`, b.`turno`, l.`nombre_linea`, m.`descripcion_motivo`, 
		concat(f.`paterno`, ' ', f.`nombres`) as visitador from `baja_dias` b, `baja_dias_detalle` bd, 
		`baja_dias_detalle_visitador` bv, `motivos_baja` m, `funcionarios` f, `lineas` l, `orden_dias` o, ciudades c 
		where b.`codigo_baja` = bd.`codigo_baja` and b.`codigo_baja` = bv.`codigo_baja` and 
		bd.`codigo_baja` = bv.`codigo_baja` and b.`codigo_ciudad`=c.`cod_ciudad` and 
		bd.`codigo_linea`=l.`codigo_linea` and b.`dia_contacto`=o.`dia_contacto` and 
		b.`gestion` = $rpt_gestion and b.`ciclo` = $rpt_ciclo and b.`codigo_ciudad` in ($rpt_territorio) and 
		m.`codigo_motivo` = b.`codigo_motivo` and f.`codigo_funcionario` = bv.`codigo_visitador` 
		order by c.descripcion, o.`id`, b.`turno`, l.`nombre_linea`, visitador";
	echo $sql;
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
	$nombreTerritorio=$dat[0];
	$diaContacto=$dat[1];
	$turno=$dat[2];
	$nombreLinea=$dat[3];
	$motivo=$dat[4];
	$nombreVisitador=$dat[5];
	echo "<tr><td>$nombreTerritorio</td><td>$diaContacto</td><td>$turno</td><td>$nombreLinea</td><td>$motivo</td><td>$nombreVisitador</td></tr>";
}
echo "</table>";

?>