<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='creacion_contactos_numero.php';
		}
		function recuperar_contactos()
		{	location.href='recuperacion_contactos.php';
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
			{	alert('Debe seleccionar al menos un contacto para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_contacto.php?datos='+datos+'';
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
			var j_contacto;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_contacto=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un contacto para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un contacto para editar sus datos.');
				}
				else
				{
					location.href='editar_contacto.php?j_contacto='+j_contacto+'';
				}
			}
		}
		</script>
	";
	require("conexion.inc");
	require("estilos_visitador.inc");
	echo "<form method='post' action='opciones_medico.php'>";
	//esta parte saca el ciclo activo
	if($global_linea==0)
	{	$sql="select * from rutero where cod_visitador=$global_visitador and zona_viaje='1' order by dia_contacto,turno";
	}
	else
	{	$sql="select * from contactos where cod_visitador=$global_visitador and zona_viaje='0' order by dia_contacto,turno";
	}
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Registro de Rutero Medico</td></tr></table></center><br>";
	echo "<center><table border='1' class='textomini' cellspacing='0'>";
	echo "<tr><th>&nbsp;</th><th>Ciclo</th><th>Dia Contacto</th><th>Fecha</th><th>Turno</th><th>Contactos</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_contacto=$dat[0];
		$cod_ciclo=$dat[1];
		$dia_contacto=$dat[3];
		$fecha=$dat[4];
		$turno=$dat[5];
		$sql1="select c.orden_visita, m.ap_pat_med, m.ap_mat_med, m.nom_med, d.direccion, c.cod_especialidad, c.categoria_med, c.estado
				from contactos_detalle c, medicos m, direcciones_medicos d
					where (c.cod_contacto=$cod_contacto) and (c.cod_visitador=$global_visitador) and (c.cod_med=m.cod_med) and (m.cod_med=d.cod_med) and (c.cod_zona=d.cod_zona) order by c.orden_visita";
		$resp1=mysql_query($sql1);
		$contacto="<table class='textomini' width='100%'>";
		$contacto=$contacto."<tr><th width='5%'>Orden</th><th width='35%'>Medico</th><th width='10%'>Especialidad</th><th width='10%'>Categoria</th><th width='30%'>Direccion</th><th width='10%'>Visitado</th></tr>";
		while($dat1=mysql_fetch_array($resp1))
		{
			$orden_visita=$dat1[0];
			$pat=$dat1[1];
			$mat=$dat1[2];
			$nombre=$dat1[3];
			$direccion=$dat1[4];
			$nombre_medico="$pat $mat $nombre";
			$espe=$dat1[5];
			$cat=$dat1[6];
			$estado=$dat1[7];
			if($estado==0)
			{	$det_estado="<img src='imagenes/no.gif' width='20' heigth='20'>";
			}
			else
			{	$det_estado="<img src='imagenes/si.gif' width='20' heigth='20'>";
			}
			$contacto=$contacto."<tr><td align='center'>$dat1[0]</td><td align='center'>$nombre_medico</td><td align='center'>$espe</td><td align='center'>$cat</td><td align='center'>$direccion </td><td align='center'>$det_estado </td></tr>";
		}
		$contacto=$contacto."</table>";
		echo "<tr><td align='center'><input type='checkbox' name='cod_contacto' value=$cod_contacto></td><td align='center' class='texto'>$cod_ciclo</td><td align='center'>$dia_contacto</td><td align='center'>$fecha</td><td align='center'>$turno</td><td align='center'>$contacto</td></tr>";
	}
	echo "</table></center><br>";
	$sql_numeros="select cd.estado from contactos c, contactos_detalle cd
				where c.cod_contacto=cd.cod_contacto and c.cod_visitador='$global_visitador'";
	$resp_numeros=mysql_query($sql_numeros);
	$total_contactos=mysql_num_rows($resp_numeros);
	$realizados=0;
	$faltantes=0;
	while($dat_numeros=mysql_fetch_array($resp_numeros))
	{	$estado=$dat_numeros[0];
		if($estado==0)
		{	$faltantes++;
		}
		else
		{	$realizados++;
		}
	}
	$cobertura=($realizados/$total_contactos)*100;
	echo "<center><table border='0' class='texto'>";
	echo "<tr><th align='left'>Total Contactos: </th><th>$total_contactos</th></tr>";
	echo "<tr><th align='left'>Realizados: </th><th>$realizados</th></tr>";
	echo "<tr><th align='left'>Sin realizar: </th><th>$faltantes</th></tr>";
	echo "<tr><th align='left'>Cobertura: </th><th>$cobertura %</th></tr></table></center>";
	//echo "<center><table border='0' class='texto'>";
	//echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td><td><input type='button' value='Recuperar Contactos' name='recuperar' class='boton' onClick='recuperar_contactos()'></td></tr></table></center>";
	echo "</form>";
?>