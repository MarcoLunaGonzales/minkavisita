<?php
	require("conexion.inc");
	require("estilos_reportes_regional.inc");
	//echo $rpt_territorio;
	$global_linea=$linea_rpt;
		$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";$resp1=mysql_query($sql_cab);
		$dato=mysql_fetch_array($resp1);
		$nombre_territorio=$dato[1];	
		$sql_cabecera_gestion=mysql_query("select nombre_gestion from gestiones where codigo_gestion='$gestion_rpt' and codigo_linea='$global_linea'");
		$datos_cab_gestion=mysql_fetch_array($sql_cabecera_gestion);
		$nombre_cab_gestion=$datos_cab_gestion[0];
	echo "<form method='post' action='opciones_medico.php'>";
	$sql_ciclo=mysql_query("select cod_ciclo from ciclos where estado='Activo' and codigo_linea='$global_linea'");
	$dat=mysql_fetch_array($sql_ciclo);
	$cod_ciclo=$dat[0];
	if($rpt_territorio==0)
	{	echo "<center><table border='0' class='textotit'><tr><th>Parrillas Promocionales<br>Gesti�n: $nombre_cab_gestion Ciclo: $ciclo_rpt</th></tr></table></center><br>";
	}
	else
	{	echo "<center><table border='0' class='textotit'><tr><th>Parrillas Promocionales<br>Territorio: $nombre_territorio<br>Gesti�n: $nombre_cab_gestion Ciclo: $ciclo_rpt</th></tr></table></center><br>";
	}
	echo "<center><table border='0' class='textomini'><tr><td>Leyenda:</td><td>Producto Extra</td><td bgcolor='#66CCFF' width='30%'></td></tr></table></center><br>";

	$sql_agencia="select cod_ciudad, descripcion from ciudades where cod_ciudad='$rpt_territorio'";
	$resp_agencia=mysql_query($sql_agencia);
	while($dat_agencia=mysql_fetch_array($resp_agencia))
	{
		$cod_ciudad=$dat_agencia[0];
		$descripcion_ciudad=$dat_agencia[1];
		//seleccionando parrillas dependiendo de la agencia
		if($cod_especialidad=="")
		{	$sql="select * from parrilla 
			where agencia=$cod_ciudad and codigo_linea=$global_linea and cod_ciclo='$ciclo_rpt' 
			and codigo_gestion='$gestion_rpt' order by cod_ciclo,cod_especialidad,codigo_l_visita,categoria_med,numero_visita";
		}
		else
		{	$sql="select * from parrilla 
			where cod_especialidad='$cod_especialidad' and agencia=$cod_ciudad and codigo_linea=$global_linea 
			and cod_ciclo='$ciclo_rpt' and codigo_gestion='$gestion_rpt' 
			order by cod_ciclo,cod_especialidad,codigo_l_visita,categoria_med,numero_visita";
		}
		$resp=mysql_query($sql);
		$filas=mysql_num_rows($resp);
		if($filas>0)
		{	echo "<table align='center' class='texto'><tr><th>$descripcion_ciudad</th></tr></table>";
			echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
			echo "<tr><th>Especialidad</th><th>L. V�sita</th><th>Categoria</th><th>Visita</th><th>Parrilla Promocional</th></tr>";
			while($dat=mysql_fetch_array($resp))
			{
				$cod_parrilla=$dat[0];
				$cod_ciclo=$dat[1];
				$cod_espe=$dat[2];
				$cod_cat=$dat[3];
				$codLineaVisita=$dat[9];
				$sqlLineaVisita="select nombre_l_visita from lineas_visita where codigo_l_visita=$codLineaVisita";
				$respLineaVisita=mysql_query($sqlLineaVisita);
				$nombreLineaVisita="";
				while($datLineaVisita=mysql_fetch_array($respLineaVisita)){
						$nombreLineaVisita=$datLineaVisita[0];
				}
				$fecha_creacion=$dat[5];
				$fecha_modi=$dat[6];
				$numero_de_visita=$dat[7];
				$sql1="select m.descripcion, m.presentacion, p.cantidad_muestra, mm.descripcion_material, p.cantidad_material, p.observaciones,p.prioridad,p.extra
				from muestras_medicas m, parrilla_detalle p, material_apoyo mm
      				where p.codigo_parrilla=$cod_parrilla and m.codigo=p.codigo_muestra and mm.codigo_material=p.codigo_material order by p.prioridad";
				$resp1=mysql_query($sql1);
				$parrilla_medica="<table class='textomini' width='100%' border='0'>";
				$parrilla_medica=$parrilla_medica."<tr><th>Orden</th><th>Producto</th><th>Cantidad</th><th>Material de Apoyo</th><th>Cantidad</td><th>Obs.</th></tr>";
				while($dat1=mysql_fetch_array($resp1))
				{
					$muestra=$dat1[0];
					$presentacion=$dat1[1];
					$cant_muestra=$dat1[2];
					$material=$dat1[3];
					$cant_material=$dat1[4];
					$obs=$dat1[5];
					$prioridad=$dat1[6];
					$extra=$dat1[7];
					if($extra==1)
					{	$fondo_extra="<tr bgcolor='#66CCFF'>";
					}
					else
					{	$fondo_extra="<tr>";
					}
					if($obs!="")
					{	
			  			$observaciones="<img src='imagenes/informacion.gif' alt='$obs'>";
					}
					else
					{
					 $observaciones="&nbsp;";
					}
					$parrilla_medica=$parrilla_medica."$fondo_extra<td align='center'>$prioridad</td><td align='left' width='35%'>$muestra $presentacion</td><td align='center' width='10%'>$cant_muestra</td><td align='left' width='35%'>$material</td><td align='center' width='10%'>$cant_material</td><td align='center' width='10%'>$observaciones</td></tr>";
				}
				$parrilla_medica=$parrilla_medica."</table>";
				echo "<tr><td align='center'>$cod_espe</td><td align='center'>&nbsp;$nombreLineaVisita</td>
				<td align='center'>$cod_cat</td><td align='center'>$numero_de_visita</td><td align='center'>$parrilla_medica</td></tr>";
			}
			echo "</table></center><br>";
	 	}
	}
	
	if($rpt_territorio==0)
	{
	////////////
	if($cod_especialidad=="")
	{	$sql="select * from parrilla where agencia=0 and codigo_linea=$global_linea and cod_ciclo='$ciclo_rpt' and codigo_gestion='$gestion_rpt' order by cod_ciclo,cod_especialidad,categoria_med,numero_visita";	
	}
	else
	{	$sql="select * from parrilla where cod_especialidad='$cod_especialidad' and agencia=0 and codigo_linea=$global_linea and cod_ciclo='$ciclo_rpt' and codigo_gestion='$gestion_rpt' order by cod_ciclo,cod_especialidad,categoria_med,numero_visita";
		
	}
		$resp=mysql_query($sql);
		$filas=mysql_num_rows($resp);
		if($filas>0)
		{	echo "<table align='center' class='texto'><tr><th>Todas los Territorios</th></tr></table>";
			echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
			echo "<tr><th>Especialidad</th><th>L�nea de Visita</th><th>Categoria</th><th>Visita</th><th>Parrilla Promocional</th></tr>";
			while($dat=mysql_fetch_array($resp))
			{
				$cod_parrilla=$dat[0];
				$cod_ciclo=$dat[1];
				$cod_espe=$dat[2];
				$cod_cat=$dat[3];
				$fecha_creacion=$dat[5];
				$fecha_modi=$dat[6];
				$numero_de_visita=$dat[7];
				$codigo_lineavisita=$dat[9];
				$sql_nombre_lineavisita="select nombre_l_visita from lineas_visita where codigo_l_visita='$codigo_lineavisita'";
				$resp_lineavisita=mysql_query($sql_nombre_lineavisita);
				$dat_lineavisita=mysql_fetch_array($resp_lineavisita);
				$nombre_lineavisita=$dat_lineavisita[0];
				
				$sql1="select m.descripcion, m.presentacion, p.cantidad_muestra, mm.descripcion_material, p.cantidad_material, p.observaciones,p.prioridad,p.extra
				from muestras_medicas m, parrilla_detalle p, material_apoyo mm
      				where p.codigo_parrilla=$cod_parrilla and m.codigo=p.codigo_muestra and mm.codigo_material=p.codigo_material order by p.prioridad";
				$resp1=mysql_query($sql1);
				$parrilla_medica="<table class='textomini' width='100%' border='0'>";
				$parrilla_medica=$parrilla_medica."<tr><th>Orden</th><th>Producto</th><th>Cantidad</th><th>Material de Apoyo</th><th>Cantidad</td><th>Obs.</th></tr>";
				while($dat1=mysql_fetch_array($resp1))
				{
					$muestra=$dat1[0];
					$presentacion=$dat1[1];
					$cant_muestra=$dat1[2];
					$material=$dat1[3];
					$cant_material=$dat1[4];
					$obs=$dat1[5];
					$prioridad=$dat1[6];
					$extra=$dat1[7];
					if($extra==1)
					{	$fondo_extra="<tr bgcolor='#66CCFF'>";
					}
					else
					{	$fondo_extra="<tr>";
					}
					if($obs!="")
					{	
			  			$observaciones="<img src='imagenes/informacion.gif' alt='$obs'>";
					}
					else
					{
					 $observaciones="&nbsp;";
					}
					$parrilla_medica=$parrilla_medica."$fondo_extra<td align='center'>$prioridad</td><td align='left' width='35%'>$muestra $presentacion</td><td align='center' width='10%'>$cant_muestra</td><td align='left' width='35%'>$material</td><td align='center' width='10%'>$cant_material</td><td align='center' width='10%'>$observaciones</td></tr>";
				}
				$parrilla_medica=$parrilla_medica."</table>";
				echo "<tr><td align='center'>$cod_espe</td><td align='center'>$nombre_lineavisita&nbsp;</td><td align='center'>$cod_cat</td><td align='center'>$numero_de_visita</td><td align='center'>$parrilla_medica</td></tr>";
			}
			echo "</table></center><br>";
		}
		}
		echo "<center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";
?>