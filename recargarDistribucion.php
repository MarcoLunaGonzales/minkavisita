<?php
require('conexion.inc');
$sql="select s.`cod_salida_almacen`, s.`codigo_funcionario` from `salida_detalle_visitador` s 
			where s.codigo_gestion = 1007 and s.codigo_ciclo = 6";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$codigoSalida=$dat[0];
	$codigoVisitador=$dat[1];
	$sqlDetalle="select s.`cod_material`, s.`cantidad_unitaria` from `salida_detalle_almacenes` s where s.`cod_salida_almacen`=$codigoSalida";
	$respDetalle=mysql_query($sqlDetalle);
	while($datDetalle=mysql_fetch_array($respDetalle)){
		$codMaterial=$datDetalle[0];
		$cantidad=$datDetalle[1];
		$buscarDistribucion="update `distribucion_productos_visitadores` set `cantidad_sacadaalmacen` = '$cantidad', 
			cantidad_distribuida='$cantidad'
			where `codigo_gestion` = 1007 and `cod_ciclo` = 6 and `cod_visitador` = $codigoVisitador and 
			`codigo_linea`=1021 and `codigo_producto`='$codMaterial'";
		$respBuscar=mysql_query($buscarDistribucion);
		echo $buscarDistribucion."<br>";
	}		
}
?>