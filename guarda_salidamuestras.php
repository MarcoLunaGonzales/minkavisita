<?php
require("conexion.inc");
require("estilos_almacenes.inc");

$grupoSalida=$_GET['grupoSalida'];

$sql="select max(cod_salida_almacenes)+1 as cod_salida_almacenes from salida_almacenes ";
$resp=mysql_query($sql);
$codigo=mysql_result($resp,0,0);

$sql="select max(nro_correlativo)+1 as nro_correlativo from salida_almacenes where cod_almacen='$global_almacen' and grupo_salida='$grupoSalida' ";
$resp=mysql_query($sql);
$nro_correlativo=mysql_result($resp,0,0);


$fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
$hora_salida=date("H:i:s");
$vector_material=explode(",",$vector_material);
$vector_cantidades=explode(",",$vector_cantidades);
$vector_fecha_vencimiento=explode(",",$vector_fechavenci);

$txtInsertaCab="insert into salida_almacenes(cod_salida_almacenes, cod_almacen, cod_tiposalida, fecha, hora_salida, territorio_destino, 
	almacen_destino, observaciones, estado_salida, grupo_salida, nro_correlativo, salida_anulada)
	values($codigo,$global_almacen,$tipo_salida,'$fecha_real','$hora_salida','$territorio','$almacen','$observaciones',0,'$grupoSalida',
	'$nro_correlativo',0)";
$sql_inserta=mysql_query($txtInsertaCab);

if($sql_inserta==1){
	if($funcionario!=""){	
		$sql_inserta=mysql_query("insert into salida_detalle_visitador (cod_salida_almacen, codigo_funcionario, codigo_gestion, codigo_ciclo) values($codigo,$funcionario,0,0)");
	}

	for($i=0;$i<=$cantidad_material-1;$i++){	
		$cod_material=$vector_material[$i];
		$fecha_vencimiento=$vector_fechavenci[$i];
		$fecha_sistema_vencimiento=$fecha_vencimiento[6].$fecha_vencimiento[7].$fecha_vencimiento[8].$fecha_vencimiento[9]."-".$fecha_vencimiento[3].$fecha_vencimiento[4]."-".$fecha_vencimiento[0].$fecha_vencimiento[1];
		$cantidad=$vector_cantidades[$i];
		
		$sql_detalle_ingreso="select id.cod_ingreso_almacen, id.cantidad_restante, id.nro_lote from ingreso_detalle_almacenes id,
		ingreso_almacenes i
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$global_almacen' and id.cod_material='$cod_material' 
		and id.cantidad_restante>0 and i.ingreso_anulado=0 order by id.fecha_vencimiento";
		//echo $sql_detalle_ingreso;
		$resp_detalle_ingreso=mysql_query($sql_detalle_ingreso);
		$cantidad_bandera=$cantidad;
		$bandera=0;
		while($dat_detalle_ingreso=mysql_fetch_array($resp_detalle_ingreso))
		{	$cod_ingreso_almacen=$dat_detalle_ingreso[0];
			$cantidad_restante=$dat_detalle_ingreso[1];
			$nro_lote=$dat_detalle_ingreso[2];
			if($bandera!=1)
			{
				if($cantidad_bandera>$cantidad_restante)
				{	$sql_salida_det_ingreso="insert into salida_detalle_ingreso (cod_salida_almacen, cod_ingreso_almacen, material, nro_lote, cantidad_unitaria, grupo_salida_ingreso)
					values('$codigo','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_restante','$grupoSalida')";
					$resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
					$cantidad_bandera=$cantidad_bandera-$cantidad_restante;
					$upd_cantidades="update ingreso_detalle_almacenes set cantidad_restante=0 where cod_ingreso_almacen='$cod_ingreso_almacen' and cod_material='$cod_material' and nro_lote='$nro_lote'";
					$resp_upd_cantidades=mysql_query($upd_cantidades);
				}
				else
				{
					$sql_salida_det_ingreso="insert into salida_detalle_ingreso (cod_salida_almacen, cod_ingreso_almacen, material, nro_lote, cantidad_unitaria, grupo_salida_ingreso)
					values('$codigo','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_bandera','$grupoSalida')";
					$resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
					$cantidad_a_actualizar=$cantidad_restante-$cantidad_bandera;
					$bandera=1;
					$upd_cantidades="update ingreso_detalle_almacenes set cantidad_restante=$cantidad_a_actualizar where cod_ingreso_almacen='$cod_ingreso_almacen' and cod_material='$cod_material' and nro_lote='$nro_lote'";
					$resp_upd_cantidades=mysql_query($upd_cantidades);
					$cantidad_bandera=$cantidad_bandera-$cantidad_restante;
				}
			}
		}
		$sql_inserta2=mysql_query("insert into salida_detalle_almacenes (cod_salida_almacen, cod_material, cantidad_unitaria, observaciones) values($codigo,'$cod_material',$cantidad,'')");
	}	
	echo "<script language='Javascript'>
			alert('Los datos fueron guardados.');
			location.href='navegador_salidamuestras.php?grupoSalida=$grupoSalida';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('Hubo un ERROR en la transaccion, consultar con el administrador.');
			location.href='navegador_salidamuestras.php?grupoSalida=$grupoSalida';
			</script>";
}



?>