<?php
echo "<script language='JavaScript'>

		function envia_select(form){
			form.submit();
			return(true);
		}
		function envia_formulario(f)
		{	var rpt_territorio, rpt_vista, rpt_formato, rpt_parametro;
			var linea_rpt;
			linea_rpt=f.linea_rpt.value;
			rpt_territorio=f.rpt_territorio.value;
			rpt_vista=f.vista.value;
			rpt_parametro=f.parametro.value;
			if(f.formato[0].checked==true){rpt_formato=0;}
			if(f.formato[1].checked==true){rpt_formato=1;}
			window.open('rpt_central_medicos_linea.php?linea_rpt='+linea_rpt+'&rpt_formato='+rpt_formato+'&rpt_vista='+rpt_vista+'&rpt_parametro='+rpt_parametro+'&rpt_territorio='+rpt_territorio+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
			return(true);
		}
		function envia_formulario_xls(f)
		{	var rpt_territorio, rpt_vista, rpt_formato, rpt_parametro;
			var linea_rpt;
			linea_rpt=f.linea_rpt.value;
			rpt_territorio=f.rpt_territorio.value;
			rpt_vista=f.vista.value;
			rpt_parametro=f.parametro.value;
			if(f.formato[0].checked==true){rpt_formato=0;}
			if(f.formato[1].checked==true){rpt_formato=1;}
			window.open('rpt_central_medicos_linea_xls.php?linea_rpt='+linea_rpt+'&rpt_formato='+rpt_formato+'&rpt_vista='+rpt_vista+'&rpt_parametro='+rpt_parametro+'&rpt_territorio='+rpt_territorio+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
			return(true);
		}
</script>";
require("conexion.inc");
if($global_usuario==1052)
{	require("estilos_gerencia.inc");
}
else
{	require("estilos_inicio_adm.inc");
}
echo "<center><table class='textotit'><tr><th>Reporte M&eacute;dicos x L&iacute;nea</th></tr></table><br>";
echo"<form method='post'>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0'>\n";
	echo "<tr><th>L&iacute;nea</th>";
	$sql_linea="select codigo_linea, nombre_linea from lineas where linea_promocion=1 order by nombre_linea";
	$resp_linea=mysql_query($sql_linea);
	echo "<td><select name='linea_rpt' class='texto' onChange='envia_select(this.form)'>";
	$bandera=0;
	echo "<option value='0'>Todas las l&iacute;neas</option>";
	while($datos_linea=mysql_fetch_array($resp_linea))
	{	$cod_linea_rpt=$datos_linea[0];$nom_linea_rpt=$datos_linea[1];
		if($linea_rpt==$cod_linea_rpt)
		{	echo "<option value='$cod_linea_rpt' selected>$nom_linea_rpt</option>";
		}
		else
		{	echo "<option value='$cod_linea_rpt'>$nom_linea_rpt</option>";
		}
	}
	echo "</select></td>";
	echo "<tr><th>Territorio</th><td><select name='rpt_territorio' class='texto' onChange='envia_select(this.form)'>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		if($rpt_territorio==$codigo_ciudad)
		{	echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
		}
		else
		{	echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
		}
	}
	echo "</select></td></tr>";
	//estas lineas son auxiliares para el $ciclo_global y el $codigo_gestion
		$sql_gestion=mysql_query("select codigo_gestion,nombre_gestion from gestiones where estado='Activo' and codigo_linea='$linea_rpt'");
		$dat_gestion=mysql_fetch_array($sql_gestion);
		$codigo_gestion=$dat_gestion[0];
		$gestion=$dat_gestion[1];
		$sql_ciclo=mysql_query("select cod_ciclo from ciclos where estado='Activo' and codigo_linea='$linea_rpt'");
		$dat_ciclo=mysql_fetch_array($sql_ciclo);
		$ciclo_global=$dat_ciclo[0];

	echo"\n <tr>";
	echo "<th>Formato</th>";
	if($formato==0)
	{	echo "<td>Resumido<input type='radio' name='formato' value='0' checked>Detallado<input type='radio' name='formato' value='1'></td>";
	}
	if($formato==1)
	{	echo "<td>Resumido<input type='radio' name='formato' value='0'>Detallado<input type='radio' name='formato' value='1' checked></td>";
	}
	if($formato!=0 and $formato!=1)
	{	echo "<td>Resumido<input type='radio' name='formato' value='0' checked>Detallado<input type='radio' name='formato' value='1'></td>";
	}
	echo"</tr>";
	echo"\n <tr>";
	echo "<th>Sacar reporte por</th><td><select name='vista' class='texto' onChange='envia_select(this.form)'>";
	if($vista==0){echo "<option value='0' selected>Alfabetico</option><option value='1'>Especialidad</option><option value='2'>RUC</option></select>";}
	if($vista==1){echo "<option value='0'>Alfabetico</option><option value='1' selected>Especialidad</option><option value='2'>RUC</option></select>";}
	if($vista==2){echo "<option value='0'>Alfabetico</option><option value='1'>Especialidad</option><option value='2' selected>RUC</option></select>";}
	if($vista!=0 and $vista!=1 and $vista!=2)
	{	echo "<option value='0'>Alfabetico</option><option value='1'>Especialidad</option><option value='2'>RUC</option></select>";
	}
	echo"</td></tr>";
	echo "<tr><th>Parametro</th><td><select name='parametro' class='texto'>";
	if($vista==0 or $vista==2)
	{	echo "<option value='0'>Ascendente</option>
		<option value='1'>Descendente</option>";
	}
	if($vista==1)
	{	$sql_espe="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
		$resp_espe=mysql_query($sql_espe);
		while($dat_espe=mysql_fetch_array($resp_espe))
		{	$cod_especialidad=$dat_espe[0];
			$desc_especialidad=$dat_espe[1];
			$sql_cantidad_medicos="select m.cod_med from medicos m, especialidades_medicos e where e.cod_med=m.cod_med and m.cod_ciudad='$rpt_territorio' and e.cod_especialidad='$cod_especialidad'";
			echo $sql_cantidad_medicos;
			$resp_cantidad_medicos=mysql_query($sql_cantidad_medicos);
			$cant_medicos=mysql_num_rows($resp_cantidad_medicos);
			if($cant_medicos!=0)
			{	echo "<option value='$cod_especialidad'>$desc_especialidad</option>";
			}
		}
	}
	echo "</select></td>";
	echo"</tr>";
	echo"</table><br>";
	if($global_usuario==1032)
	{	require('home_gerencia.inc');
	}
	else
	{	require('home_central.inc');
	}
//	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte PDF' onClick='envia_formulario_pdf(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo"</form>";
?>