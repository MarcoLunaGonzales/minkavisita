<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita M�dica
 * * @copyright 2005 
*/ 
	require("conexion.inc");
	require('estilos_visitador.inc');
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="delete from contactos where cod_contacto=$vector[$i]";
		$resp=mysql_query($sql);
		$sql1="delete from contactos_detalle where cod_contacto=$vector[$i]";
		$resp1=mysql_query($sql1);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_contactos.php';
			</script>";	
  
?>