<?php
echo"<head><title>Reportes</title><link href='stilos.css' rel='stylesheet' type='text/css'></head>";  
require("conexion.inc");
$sql_gestion=mysql_query("select codigo_gestion,nombre_gestion from gestiones where estado='Activo' and codigo_linea='$global_linea'");
$dat_gestion=mysql_fetch_array($sql_gestion);
$codigo_gestion=$dat_gestion[0];
$gestion=$dat_gestion[1];
$sql_ciclo=mysql_query("select cod_ciclo from ciclos where estado='Activo' and codigo_linea='$global_linea'");
$dat_ciclo=mysql_fetch_array($sql_ciclo);
$ciclo_global=$dat_ciclo[0];
$sql="select nombre_linea from lineas where codigo_linea='$global_linea'";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$linea=$dat[0];
$sql="select paterno, materno, nombres from funcionarios where codigo_funcionario=$global_usuario";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$paterno=$dat[0];
$materno=$dat[1];
$nombre=$dat[2];
$nombre_completo="$paterno $materno $nombre";
$sql="select descripcion from ciudades where cod_ciudad=$global_agencia";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$agencia=$dat[0];
$fecha_reporte=date("d/m/Y");
//echo "<center><table border=0 class='linea' width='100%'><tr><td width='10%'><img src='imagenes/logo_cofar.png'></td><th width='80%'>Territorio $agencia<br>L�nea $linea<br>Gesti�n:$gestion - Ciclo en Curso:$ciclo_global</th><td width='10%'></td></tr></table></center><br>";
echo "<center><table border=0 class='linea' width='100%'><tr><td width='10%'><img src='imagenes/logo_cofar.png'>
</td><th width='80%'>Territorio $agencia<br>Fecha de reporte: $fecha_reporte</th><td width='10%'></td></tr></table></center><br>";
?>