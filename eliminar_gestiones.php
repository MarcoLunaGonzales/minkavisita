<?php
 /**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita M�dica
 * * @copyright 2005
*/
	require("conexion.inc");
	require("estilos.inc");
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="delete from gestiones where codigo_gestion=$vector[$i]";
		$resp=mysql_query($sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_gestiones.php';
			</script>";


?>