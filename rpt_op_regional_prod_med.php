<?php
echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var medico, obj, filtrado;
			medico=f.codmed.value;
			obj=f.codigo.value;
			filtrado=f.codigomuestra.value;
			window.open('rpt_regional_prod_med.php?medico='+medico+'&obj='+obj+'&filtrado='+filtrado+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		function envia_formulario_xls(f)
		{	var medico, obj, filtrado;
			medico=f.codmed.value;
			obj=f.codigo.value;
			filtrado=f.codigomuestra.value;
			window.open('rpt_regional_prod_med_xls.php?medico='+medico+'&obj='+obj+'&filtrado='+filtrado+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		function envia_formulario_pdf(f)
		{	var medico, obj, filtrado;
			medico=f.codmed.value;
			obj=f.codigo.value;
			filtrado=f.codigomuestra.value;
			window.open('rpt_regional_prod_med_pdf.php?medico='+medico+'&obj='+obj+'&filtrado='+filtrado+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		</script>";
require("conexion.inc");
require("estilos_regional_pri.inc");
echo"<html>";
echo"<body>";
require('espacios_regional.inc');
echo "<center><table class='textotit'><tr><th>Reporte Productos Objetivo y Filtrados por Medico</th></tr></table><br>";
echo"<form metodh='post'>";
	echo"\n<table class='texto' border='1' align='center' cellPadding='1' cellSpacing='0' bordercolorlight='#ffffFF' bordercolordark='#003c72' >\n";
	echo"\n <tr>";
	$sql_medicos_regional="select distinct(m.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med 
	from medicos m, categorias_lineas c, productos_objetivo p, muestras_negadas n 
	where m.cod_med=c.cod_med and (m.cod_med=p.cod_med or m.cod_med=n.cod_med) and m.cod_ciudad='$global_agencia' order by m.ap_pat_med, ap_mat_med";
	$resp_medicos_regional=mysql_query($sql_medicos_regional);
	echo"<th align='left'>Medico</th><td><select name='codmed' class='texto'>";
	echo"\n<option value='' >Elija una Opci�n</option>";		
	while($dat_medicos_regional=mysql_fetch_array($resp_medicos_regional))
	{	
		$cod_med=$dat_medicos_regional[0];
		$ap_pat_med=$dat_medicos_regional[1];
		$ap_mat_med=$dat_medicos_regional[2];
		$nom_med=$dat_medicos_regional[3];
		echo"\n <option value='$cod_med'>$ap_pat_med $ap_mat_med $nom_med</option>";	
	}
	echo"\n</select></td>";
	echo"\n </tr>";
	echo"\n <tr>";
	$sql_prod_objetivo="select distinct(p.codigo_muestra), mm.descripcion, mm.presentacion
						from productos_objetivo p, categorias_lineas c, muestras_medicas mm
						where p.cod_med=c.cod_med and p.codigo_linea='$global_linea' and mm.codigo=p.codigo_muestra
						order by mm.descripcion, mm.presentacion";
	$resp_prod_objetivo=mysql_query($sql_prod_objetivo);
	echo"<th align='left'>Producto Objetivo</th><td><select name='codigo' class='texto'>";
	echo"\n<option value='' >Elija una Opci�n</option>";
	while($dat_muestras=mysql_fetch_array($resp_prod_objetivo))
	{	
		$codigo=$dat_muestras[0];
		$descripcion=$dat_muestras[1];
		$presentacion=$dat_muestras[2];
		echo"\n <option value='$codigo'>$descripcion $presentacion</option>";	
	}
	echo"\n</select></td>";
	echo"\n </tr>";
	echo"\n <tr>";
	echo"<th align='left'>Producto Filtrado</th><td><select name='codigomuestra' class='texto'>";
	echo"\n<option value='' >Elija una Opci�n</option>";
	$sql_muestras_negadas="select distinct(mn.codigo_muestra), mm.descripcion, mm.presentacion
						from muestras_negadas mn, categorias_lineas c, muestras_medicas mm
						where mn.cod_med=c.cod_med and mn.codigo_linea='$global_linea' and mm.codigo=mn.codigo_muestra
						order by mm.descripcion, mm.presentacion";
	$resp_muestras_negadas=mysql_query($sql_muestras_negadas);
	while($dat_muestras_negadas=mysql_fetch_array($resp_muestras_negadas))
	{	
		$codigo_muestra_negada=$dat_muestras_negadas[0];
		$descripcion_muestra_negada=$dat_muestras_negadas[1];
		$presentacion_muestra_negada=$dat_muestras_negadas[2];
		echo"\n <option value='$codigo_muestra_negada'>$descripcion_muestra_negada $presentacion_muestra_negada</option>";	
	}
	echo"\n</select></td>";
	echo"\n </tr>";
	echo"\n </table><br>";
	require('home_regional1.inc');
	//echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte PDF' onClick='envia_formulario_pdf(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo "<table align='center' class='texto' width='80%'><tr><th>Nota: Este reporte se genera a partir de un solo campo cualquiera. Si tiene mas de un campo seleccionado se generara el reporte a partir del primer campo empezando desde arriba.</th></tr></table>";
	echo"</form>";
	echo"</body>";
	echo"</html>";
?>