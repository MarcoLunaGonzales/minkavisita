<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2005 
*/
echo "<script language='JavaScript'>
	  function pedir_confirm(){
	  	if(confirm('Desea Agregar mas especialidades del mismo Medico.'))
		{
			history.back(-1);
		}
		else
		{	window.close();
		}
	}
	  </script>";
require("conexion.inc");
//require("estilos.inc");
	$sql="insert into especialidades_medicos values('$h_cod_med','$h_esp','$h_categoria')";
	$resp=mysql_query($sql);
	echo "<script language='JavaScript'>javascript:pedir_confirm();</script>";
?>