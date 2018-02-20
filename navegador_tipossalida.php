<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_tipossalida.php';
		}
		function eliminar_nav(f)
		{
			var i;
			var j=0;
			datos=new Array();
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	datos[j]=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j==0)
			{	alert('Debe seleccionar al menos un Tipo de Salida para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_tipossalida.php?datos='+datos+'';
				}
				else
				{
					return(false);
				}
			}
		}

		function editar_nav(f)
		{
			var i;
			var j=0;
			var j_cod_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un Tipo de Salida para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Tipo de Salida para editar sus datos.');
				}
				else
				{
					location.href='editar_tipossalida.php?codigo_registro='+j_cod_registro+'';
				}
			}
		}
		</script>";
	require("conexion.inc");
	require("estilos_administracion.inc");
	echo "<form method='post' action=''>";
	$sql="select cod_tiposalida, nombre_tiposalida, obs_tiposalida, tipo_almacen from tipos_salida order by nombre_tiposalida";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Registro de Tipos de Salida</td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0'>";
	echo "<tr><th>&nbsp;</th><th>Nombre de Tipo de Salida</th><th>Definición de Tipo de Salida</th><th>Tipo Almacen</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$tipo_ingreso=$dat[1];
		$obs_salida=$dat[2];
		$tipo_almacen=$dat[3];
		if($tipo_almacen==1)
		{	$desc_tipoalmacen="Almacen Central";
		}
		else
		{	$desc_tipoalmacen="Almacen Regional";
		}
		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='left'>$tipo_ingreso</td><td align='left'>&nbsp;$obs_salida</td><td align='left'>&nbsp;$desc_tipoalmacen</td></tr>";
	}
	echo "</table></center><br>";
	require('home_administracion.php');
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";
	echo "</form>";
?>