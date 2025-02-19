<?php
header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=archivo.xls"); 	
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexion.inc');
$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";
$sql_tipo_salida="select nombre_tiposalida from tipos_salida where cod_tiposalida='$tipo_salida'";
$resp_tipo_salida=mysql_query($sql_tipo_salida);
$datos_tipo_salida=mysql_fetch_array($resp_tipo_salida);
$nombre_tiposalida=$datos_tipo_salida[0];
	if($tipo_item==1)
	{$nombre_item="Muestra M�dica";}else{$nombre_item="Material de Apoyo";}
	if($tipo_salida!="")
	{	$nombre_tiposalidamostrar="Tipo de Salida: <strong>$nombre_tiposalida</strong>";
	}
	else
	{	$nombre_tiposalidamostrar="Todos los tipos de Salida";
	}
	if($tipo_reporte==0)
	{	$nombre_tiporeporte="Reporte x N�mero de Salida";
	}
	else
	{	$nombre_tiporeporte="Reporte x Producto";
	}
	echo "<table align='center' class='textotit'><tr><td align='center'>Reporte Salidas Almacen<br>$nombre_tiporeporte<br>$nombre_tiposalidamostrar Fecha inicio: <strong>$fecha_ini</strong> Fecha final: <strong>$fecha_fin</strong> Tipo de Item: <strong>$nombre_item</strong><br>$txt_reporte</th></tr></table>";
if($tipo_reporte==0)
{

	//desde esta parte viene el reporte en si
	$fecha_iniconsulta=cambia_formatofecha($fecha_ini);
	$fecha_finconsulta=cambia_formatofecha($fecha_fin);

	$sql="select s.cod_salida_almacenes, s.fecha, ts.nombre_tiposalida, c.descripcion, a.nombre_almacen, s.observaciones, s.estado_salida, s.nro_correlativo, s.salida_anulada
	FROM salida_almacenes s, tipos_salida ts, ciudades c, almacenes a
	where s.cod_tiposalida=ts.cod_tiposalida and s.cod_almacen='$global_almacen' and c.cod_ciudad=s.territorio_destino and a.cod_almacen=s.cod_almacen and s.grupo_salida='$tipo_item' and s.fecha>='$fecha_iniconsulta' and s.fecha<='$fecha_finconsulta' and s.tipo_salida='$tipo_salida' order by s.nro_correlativo";
	if($tipo_salida=="")
	{	$sql="select s.cod_salida_almacenes, s.fecha, ts.nombre_tiposalida, c.descripcion, a.nombre_almacen, s.observaciones, s.estado_salida, s.nro_correlativo, s.salida_anulada
		FROM salida_almacenes s, tipos_salida ts, ciudades c, almacenes a
		where s.cod_tiposalida=ts.cod_tiposalida and s.cod_almacen='$global_almacen' and c.cod_ciudad=s.territorio_destino and a.cod_almacen=s.cod_almacen and s.grupo_salida='$tipo_item' and s.fecha>='$fecha_iniconsulta' and s.fecha<='$fecha_finconsulta' order by s.nro_correlativo";
	}
	$resp=mysql_query($sql);
	echo "<center><br><table border='1' class='texto' cellspacing='0' width='130%'>";
	echo "<tr><th>Nro.</th><th>Fecha</th><th>Tipo de Salida</th><th>Territorio<br>Destino</th><th>Almacen Destino</th><th>Funcionario Destino</th><th>Observaciones</th><th>Estado</th><th>Detalle</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$fecha_salida=$dat[1];
		$fecha_salida_mostrar="$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
		$nombre_tiposalida=$dat[2];
		$nombre_ciudad=$dat[3];
		$nombre_almacen=$dat[4];
		$obs_salida=$dat[5];
		$estado_almacen=$dat[6];
		$nro_correlativo=$dat[7];
		$salida_anulada=$dat[8];
		echo "<input type='hidden' name='fecha_salida$nro_correlativo' value='$fecha_salida_mostrar'>";
		if($estado_almacen==0)
		{	$estado_salida='';
		}
		//salida despachada
		if($estado_almacen==1)
		{	$estado_salida='Despachada';
		}
		//salida recepcionada
		if($estado_almacen==2)
		{	$estado_salida='Recepcionada';
		}
		if($salida_anulada==1)
		{	$estado_salida='Anulada';
		}
		$sql_funcionario="select f.paterno, f.materno, f.nombres from funcionarios f, salida_detalle_visitador sv
		where sv.cod_salida_almacen='$codigo' and f.codigo_funcionario=sv.codigo_funcionario";
		$resp_funcionario=mysql_query($sql_funcionario);
		$dat_funcionario=mysql_fetch_array($resp_funcionario);
		$nombre_funcionario="$dat_funcionario[0] $dat_funcionario[1] $dat_funcionario[2]";
		//aqui sacamos el detalle
		$detalle_salida="";
		$detalle_salida.="<table border='1' class='texto' cellspacing='0' width='100%' align='center'>";
		$detalle_salida.="<tr><th>&nbsp;</th><th width='80%'>Muestra</th><th width='20%'>Cantidad</th></tr>";
		$sql_detalle="select s.cod_material, s.cantidad_unitaria from salida_detalle_almacenes s
		where s.cod_salida_almacen='$codigo'";
		$resp_detalle=mysql_query($sql_detalle);
		$indice=1;
		$bandera=0;
		while($dat_detalle=mysql_fetch_array($resp_detalle))
		{	$cod_material=$dat_detalle[0];
			if($rpt_linea!=0)
			{	$sql_linea="select * from producto_especialidad where codigo_mm='$cod_material' and codigo_linea='$rpt_linea'";
				$resp_linea=mysql_query($sql_linea);
				$num_filas=mysql_num_rows($resp_linea);
				if($num_filas!=0)
				{	$bandera=1;
				}
			}
			$cantidad_unitaria=$dat_detalle[1];
			if($tipo_item==1)
			{	$sql_nombre_material="select descripcion, presentacion from muestras_medicas where codigo='$cod_material'";
			}
			else
			{	$sql_nombre_material="select descripcion_material from material_apoyo where codigo_material='$cod_material'";
			}
			$resp_nombre_material=mysql_query($sql_nombre_material);
			$dat_nombre_material=mysql_fetch_array($resp_nombre_material);
			$nombre_material=$dat_nombre_material[0];
			$presentacion_material=$dat_nombre_material[1];
			if($rpt_linea!=0 and $num_filas!=0)
			{	$detalle_salida.="<tr><td align='center'>$indice</td><td width='80%'>$nombre_material $presentacion_material</td><td width='20%' align='center'>$cantidad_unitaria</td></tr>";
			}
			if($rpt_linea==0)
			{	$detalle_salida.="<tr><td align='center'>$indice</td><td width='80%'>$nombre_material $presentacion_material</td><td width='20%' align='center'>$cantidad_unitaria</td></tr>";
			}
			$indice++;
		}
		$detalle_salida.="</table>";
		//fin detalle
		if($rpt_linea==0)
		{	echo "<tr><td align='center'>$nro_correlativo</td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>$nombre_ciudad</td><td>&nbsp;$nombre_almacen</td><td>&nbsp;$nombre_funcionario</td><td>&nbsp;$obs_salida</td><td>&nbsp;$estado_salida</td><td>&nbsp;$detalle_salida</td></tr>";
		}
		if($rpt_linea!=0 and $bandera==1)
		{	echo "<tr><td align='center'>$nro_correlativo</td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>$nombre_ciudad</td><td>&nbsp;$nombre_almacen</td><td>&nbsp;$nombre_funcionario</td><td>&nbsp;$obs_salida</td><td>&nbsp;$estado_salida</td><td>&nbsp;$detalle_salida</td></tr>";
		}

	}
	echo "</table></center><br>";
}
if($tipo_reporte==1)
{	if($tipo_item==1)
	{	$sql_producto="select codigo, descripcion, presentacion from muestras_medicas order by descripcion";
	}
	if($tipo_item==2)
	{	$sql_producto="select codigo_material, descripcion_material from material_apoyo order by descripcion_material";
	}
	$resp_producto=mysql_query($sql_producto);
	echo "<table border='1' class='texto' width='70%' align='center'>";
	if($tipo_item==1)
	{	echo "<tr><th>Muestra</th><th>Salidas</th></tr>";
	}
	if($tipo_item==2)
	{	echo "<tr><th>Material</th><th>Salidas</th></tr>";
	}
	while($dat_producto=mysql_fetch_array($resp_producto))
	{	$codigo_producto=$dat_producto[0];
		$nombre_producto="$dat_producto[1] $dat_producto[2]";
		$sql="select * from ciudades order by descripcion";
		$resp=mysql_query($sql);
		$cadena_ciudades="<table border=1 cellspacing='0' class='textomini' width='100%'><tr><th>Territorio Destino</th><th>Cantidad</th><th>&nbsp;</th></tr>";
		$bandera_producto=0;
		$suma_salida_producto=0;
		while($dat=mysql_fetch_array($resp))
		{	$cod_ciudad=$dat[0];
			$nombre_ciudad=$dat[1];
			$sql_almacen="select cod_almacen from almacenes where cod_ciudad='$cod_ciudad'";
			$resp_almacen=mysql_query($sql_almacen);
			$dat_almacen=mysql_fetch_array($resp_almacen);
			$cod_almacen=$dat_almacen[0];

			$fecha_iniconsulta=cambia_formatofecha($fecha_ini);
			$fecha_finconsulta=cambia_formatofecha($fecha_fin);

			$sql_salidas="select sum(sdi.cantidad_unitaria), s.cod_salida_almacenes, sdi.nro_lote
from salida_almacenes s, salida_detalle_almacenes sd, salida_detalle_ingreso sdi
where sd.cod_salida_almacen=s.cod_salida_almacenes and sd.cod_salida_almacen=sdi.cod_salida_almacen 
and sd.cod_material='$codigo_producto' and s.cod_salida_almacenes=sdi.cod_salida_almacen and 
sd.cod_material=sdi.material and s.salida_anulada<>1 and s.cod_almacen='$global_almacen'
and s.almacen_destino='$cod_almacen' and s.fecha>='$fecha_iniconsulta' and s.fecha<='$fecha_finconsulta'
group by (sdi.nro_lote)";
			//echo $sql_salidas;
			$resp_salidas=mysql_query($sql_salidas);
			$cantidad_salida=0;
			$cadena_lotes="<table border='1' cellspacing='0' width='100%' class='textomini'><tr><th>Lote</th><th>Cantidad</th></tr>";
			while($dat_salidas=mysql_fetch_array($resp_salidas))
			{	$cant_salida=$dat_salidas[0];
				$cod_salida_almacen=$dat_salidas[1];
				$nro_lote=$dat_salidas[2];
				$cantidad_salida=$cant_salida+$cantidad_salida;
				$cadena_lotes.="<tr><td>&nbsp;$nro_lote</td><td align='right'>$cant_salida</td></tr>";
				
			}
			$cadena_lotes.="</table>";
			if($cantidad_salida>0)
			{	$bandera_producto=1;
//				echo $sql_salidas;
				$suma_salida_producto=$suma_salida_producto+$cantidad_salida;
				if($tipo_item==1)
				{	$cadena_ciudades.="<tr><td>$nombre_ciudad</td><td align='right'>$cantidad_salida</td><td>$cadena_lotes</td></tr>";
				}
				if($tipo_item==2)
				{	$cadena_ciudades.="<tr><td>$nombre_ciudad</td><td align='right'>$cantidad_salida</td><td>&nbsp;</td></tr>";
				}
			}
		}
		$cadena_ciudades.="<tr><th>Total:</th><th align='right'>$suma_salida_producto</th></tr>";
		$cadena_ciudades.="</table>";
		if($bandera_producto==1)
		{	echo "<tr><td>$nombre_producto</td><td>$cadena_ciudades</td></tr>";
		}
	}
	echo "</table>";
}
?>