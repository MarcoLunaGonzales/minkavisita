<?php
echo "<script language='JavaScript'>
		function envia_select(form){
			form.submit();
			return(true);
		}
		function envia_formulario(f)
		{	var visitador,gestion_rpt, ciclo_rpt;
			visitador=f.visitador.value;
			gestion_rpt=f.gestion_rpt.value;
			ciclo_rpt=f.ciclo_rpt.value;
			window.open('rpt_regional_cobertura.php?visitador='+visitador+'&gestion_rpt='+gestion_rpt+'&ciclo_rpt='+ciclo_rpt+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		function envia_formulario_xls(f)
		{	var visitador,gestion_rpt, ciclo_rpt;
			visitador=f.visitador.value;
			gestion_rpt=f.gestion_rpt.value;
			ciclo_rpt=f.ciclo_rpt.value;
			window.open('rpt_regional_cobertura_xls.php?visitador='+visitador+'&gestion_rpt='+gestion_rpt+'&ciclo_rpt='+ciclo_rpt+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		function envia_formulario_pdf(f)
		{	var visitador,gestion_rpt, ciclo_rpt;
			visitador=f.visitador.value;
			gestion_rpt=f.gestion_rpt.value;
			ciclo_rpt=f.ciclo_rpt.value;
			window.open('rpt_regional_cobertura_pdf.php?visitador='+visitador+'&gestion_rpt='+gestion_rpt+'&ciclo_rpt='+ciclo_rpt+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		</script>";
require("conexion.inc");
require("estilos_regional_pri.inc");
require('espacios_regional.inc');
echo "<center><table class='textotit'><tr><th>Reporte Cobertura</th></tr></table><br>";
echo"<form method='post'>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='30%'>\n";

	echo "<tr><th align='left'>Gesti�n</th>";
	$sql_gestion="select codigo_gestion, nombre_gestion, estado from gestiones where codigo_linea='$global_linea'";
	$resp_gestion=mysql_query($sql_gestion);
	echo "<td><select name='gestion_rpt' class='texto' onChange='envia_select(this.form)'>";
	$bandera=0;
	while($datos_gestion=mysql_fetch_array($resp_gestion))
	{	$cod_gestion_rpt=$datos_gestion[0];$nom_gestion_rpt=$datos_gestion[1];$estado_gestion_rpt=$datos_gestion[2];
		if($gestion_rpt==$cod_gestion_rpt)
		{	echo "<option value='$cod_gestion_rpt' selected>$nom_gestion_rpt</option>";
			$ban=1;
		}
		else
		{	if($estado_gestion_rpt=="Activo" and $ban==0)
			{	echo "<option value='$cod_gestion_rpt' selected>$nom_gestion_rpt</option>";	
			}
			else
			{	echo "<option value='$cod_gestion_rpt'>$nom_gestion_rpt</option>";
			}
		}
	}
	echo "</select></td>";
	echo "<tr><th align='left'>Ciclo</th>";
	if($gestion_rpt=="")
	{	$sql_ciclo="select cod_ciclo, estado from ciclos where codigo_linea='$global_linea' and codigo_gestion='$codigo_gestion' order by cod_ciclo desc";
	}
	else
	{	$sql_ciclo="select cod_ciclo, estado from ciclos where codigo_linea='$global_linea' and codigo_gestion='$gestion_rpt' order by cod_ciclo desc";
	}
	$resp_ciclo=mysql_query($sql_ciclo);
	echo "<td><select name='ciclo_rpt' class='texto'>";
	while($datos_ciclo=mysql_fetch_array($resp_ciclo))
	{	$cod_ciclo_rpt=$datos_ciclo[0];$estado_ciclo_rpt=$datos_ciclo[1];
		if($estado_ciclo_rpt=="Activo")
		{	echo "<option value='$cod_ciclo_rpt' selected>$cod_ciclo_rpt ($estado_ciclo_rpt)</option>";	
		}
		else
		{	echo "<option value='$cod_ciclo_rpt'>$cod_ciclo_rpt ($estado_ciclo_rpt)</option>";
		}
	}
	echo "</select></td>";
	echo "<tr><th align='left'>Visitador</th><td align='center'><select name='visitador' class='texto'>";
	$sql_visitador="select f.codigo_funcionario, f.paterno, f.materno, f.nombres
	from funcionarios f, cargos c, ciudades ci, funcionarios_lineas fl
	where f.cod_cargo=c.cod_cargo and f.cod_cargo='1011' and fl.codigo_funcionario=f.codigo_funcionario and fl.codigo_linea='$global_linea' and f.cod_ciudad='$global_agencia' and f.cod_ciudad=ci.cod_ciudad order by ci.descripcion,f.paterno,c.cargo";
	$resp_visitador=mysql_query($sql_visitador);
	echo "<option value=''>Todos</option>";
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	$codigo_visitador=$dat_visitador[0];
		$nombre_visitador="$dat_visitador[1] $dat_visitador[2] $dat_visitador[3]";
		echo "<option value='$codigo_visitador'>$nombre_visitador</option>";
	}
	echo "</select>";
	echo "</td></tr>";
	echo"\n </table><br>";
	require('home_regional1.inc');
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	//echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte PDF' onClick='envia_formulario_pdf(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo"</form>";
?>