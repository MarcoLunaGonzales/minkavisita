<?php
header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=archivo.xls"); 	
require("conexion.inc");
require("estilos_reportes_central_xls.inc");
	$global_linea=$linea_rpt;
	$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";$resp1=mysql_query($sql_cab);
	$dato=mysql_fetch_array($resp1);
	$nombre_territorio=$dato[1];	
if($frecuencia_visita=="")
{	echo "<center>Frecuencias de Visita<br>Territorio: $nombre_territorio</center><br>";
	for($frecuencia=$maxima_frecuencia;$frecuencia>=1;$frecuencia--)
	{	$sql_medicos_asignados="select m.cod_med, m.ap_pat_med, m.ap_mat_med, m.nom_med from medicos m, categorias_lineas c where 
		m.cod_ciudad='$rpt_territorio' and m.cod_med=c.cod_med order by m.ap_pat_med, m.ap_mat_med";
		$resp_medicos_asignados=mysql_query($sql_medicos_asignados);
		$indice_tabla=1;
		echo "<table border='1' class='textomini' width='50%' cellspacing='0' align='center'>";
		echo "<tr><th colspan=5>Frecuencia de Visita: $frecuencia</th></tr>";
		echo "<tr><th width='3%'>&nbsp;</th><th width='30%'>Medico</th><th width='20%'>Especialidad</th></tr>";
		while($dat=mysql_fetch_array($resp_medicos_asignados))
		{
			$codigo_medico=$dat[0];
			$nombre_medico="$dat[1] $dat[2] $dat[3]";
			$sql2="select c.cod_especialidad, c.categoria_med, e.descripcion
      			from especialidades_medicos e, categorias_lineas c
          			where c.cod_med=e.cod_med and c.cod_med=$codigo_medico and c.cod_especialidad=e.cod_especialidad and c.codigo_linea=$global_linea order by e.descripcion";
			$resp2=mysql_query($sql2);
			$especialidad="";
			while($dat2=mysql_fetch_array($resp2))
			{
				$espe=$dat2[0];
				$cat=$dat2[1];
				$desc_espe=$dat2[2];
				$especialidad="$especialidad<br>$espe&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$cat";
			}
			$especialidad="$especialidad";
		
			$sql_num_frecuencia="select COUNT(rd.cod_med) from rutero r, rutero_detalle rd 
			where r.cod_contacto=rd.cod_contacto and r.cod_ciclo='$ciclo_global' and r.codigo_gestion='$codigo_gestion' and rd.cod_med='$codigo_medico'";
			$resp_num_frecuencia=mysql_query($sql_num_frecuencia);
			$dat_frecuencia=mysql_fetch_array($resp_num_frecuencia);
			$frecuencia_medico=$dat_frecuencia[0];
			if($frecuencia_medico==$frecuencia)
			{	echo "<tr><td align='center'>$indice_tabla</td><td>$nombre_medico</td><td align='center'>$especialidad</td></tr>";
				$indice_tabla++;
			}	
		}
		echo "</table>";
	}
}
if($frecuencia_visita!="")
{	echo "<center>Frecuencias de Visita: $frecuencia_visita<br>Territorio: $nombre_territorio</center><br>";
	$sql_medicos_asignados="select m.cod_med, m.ap_pat_med, m.ap_mat_med, m.nom_med from medicos m, categorias_lineas c where 
	m.cod_ciudad='$rpt_territorio' and m.cod_med=c.cod_med order by m.ap_pat_med, m.ap_mat_med";
	$resp_medicos_asignados=mysql_query($sql_medicos_asignados);
	$indice_tabla=1;
	echo "<table border='1' class='textomini' width='50%' cellspacing='0' align='center'>";
	echo "<tr><th width='3%'>&nbsp;</td><th width='30%'>Medico</th><th width='20%'>Especialidad</th></tr>";
	while($dat=mysql_fetch_array($resp_medicos_asignados))
	{
		$codigo_medico=$dat[0];
		$nombre_medico="$dat[1] $dat[2] $dat[3]";
		$sql2="select c.cod_especialidad, c.categoria_med, e.descripcion
     			from especialidades_medicos e, categorias_lineas c
         			where c.cod_med=e.cod_med and c.cod_med=$codigo_medico and c.cod_especialidad=e.cod_especialidad and c.codigo_linea=$global_linea order by e.descripcion";
		$resp2=mysql_query($sql2);
		$especialidad="";
		while($dat2=mysql_fetch_array($resp2))
		{
			$espe=$dat2[0];
			$cat=$dat2[1];
			$desc_espe=$dat2[2];
			$especialidad="$especialidad<br>$espe&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$cat";
		}
		$especialidad="$especialidad";
	
		$sql_num_frecuencia="select COUNT(rd.cod_med) from rutero r, rutero_detalle rd 
		where r.cod_contacto=rd.cod_contacto and r.cod_ciclo='$ciclo_global' and r.codigo_gestion='$codigo_gestion' and rd.cod_med='$codigo_medico'";
		$resp_num_frecuencia=mysql_query($sql_num_frecuencia);
		$dat_frecuencia=mysql_fetch_array($resp_num_frecuencia);
		$frecuencia_medico=$dat_frecuencia[0];
		if($frecuencia_medico==$frecuencia_visita)
		{	echo "<tr><td align='center'>$indice_tabla</td><td>$nombre_medico</td><td align='center'>$especialidad</td></tr>";
			$indice_tabla++;
		}		
	}
	echo "</table>";	
}
?>