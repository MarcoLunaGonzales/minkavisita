<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2005
*/

//$global_visitador=14059;
require("conexion.inc");
require("estilos_visitador.inc");
$vector=explode(",",$variables);
$num_elementos=sizeof($vector);
$fecha=$vector[1];
$turno=$vector[2];
$numero_contactos=$vector[3];
$dia_contacto=$vector[0];
$fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
//verifica que no exista repeticion de datos en nuestra estructura
$sql_pre="select * from rutero_maestro where cod_rutero='$rutero' and cod_visitador=$global_visitador and dia_contacto='$dia_contacto' and turno='$turno'";
$resp_pre=mysql_query($sql_pre);
$num_filas=mysql_num_rows($resp_pre);
if($num_filas==0)
{
//esto es para el codigo de los contactos
	$sql_cod="select cod_contacto from rutero_maestro order by cod_contacto desc";
	$resp_cod=mysql_query($sql_cod);
	$num_filas=mysql_num_rows($resp_cod);
	if($num_filas==0)
	{	$cod_contacto=1000;
	}
	else
	{	$dat_cod=mysql_fetch_array($resp_cod);
		$cod_contacto=$dat_cod[0];
		$cod_contacto=$cod_contacto+1;
	}
	$sql_insert="insert into rutero_maestro values($cod_contacto ,'$rutero','$global_visitador','$dia_contacto','$turno','$global_zona_viaje' )";
	$resp_insert=mysql_query($sql_insert);
for($i=1;$i<=$numero_contactos;$i++)
{	$indice=($i*5)-1;
	$orden_visita=$vector[$indice];
	$cod_med=$vector[$indice+1];
	$cod_zona=$vector[$indice+2];
	$cod_espe=$vector[$indice+3];
	$cod_categoria=$vector[$indice+4];
	$sql="insert into rutero_maestro_detalle values($cod_contacto ,'$orden_visita' , '$global_visitador' , '$cod_med' , '$cod_espe' , '$cod_categoria' , '$cod_zona' , 0, 2)";
	$resp=mysql_query($sql);
}
	echo "<script language='Javascript'>
			alert('Los datos se registraron satisfactoriamente');
			location.href='rutero_maestro_todo.php?rutero=$rutero';
		</script>";
}
else
{	echo "<script language='Javascript'>
			alert('No puede ingresar este contacto con este dia de contacto ni este turno, porque ya existe.');
			history.back(-1);
		</script>";
}
?>