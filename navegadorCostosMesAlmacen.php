<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_almacenes.php';
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
			{	alert('Debe seleccionar al menos un Almacen para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_almacenes.php?datos='+datos+'';
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
			{	alert('Debe seleccionar solamente un Almacen para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Almacen para editar sus datos.');
				}
				else
				{
					location.href='editar_almacenes.php?codigo_registro='+j_cod_registro+'';
				}
			}
		}
		</script>";
	require("conexion.inc");
	require("estilos_administracion.inc");
	echo "<form method='post' action=''>";
	$sql="select a.cod_almacen, c.descripcion, a.nombre_almacen, f.paterno, f.materno, f.nombres 
	from almacenes a, funcionarios f, ciudades c where 
	c.cod_ciudad=a.cod_ciudad and f.codigo_funcionario=a.responsable_almacen 
	order by c.descripcion, a.nombre_almacen";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Costos x Almacen x Mes</td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='80%'>";
	echo "<tr><th>&nbsp;</th><th>Territorio</th><th>Nombre Almacen</th><th>Responsable</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombre_ciudad=$dat[1];
		$nombre_almacen=$dat[2];
		$nombre_responsable="$dat[3] $dat[4] $dat[5]";
		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td>$nombre_ciudad</td><td>$nombre_almacen</td><td>$nombre_responsable</td></tr>";
	}
	echo "</table></center><br>";
	require('home_administracion.php');
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";
	echo "</form>";
?>