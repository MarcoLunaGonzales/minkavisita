<?php
require("conexion.inc");
require("estilos_gerencia.inc");

$codLineaDist=$_POST['codLineaDist'];
$codCicloDist=$_POST['codCicloDist'];
$codGestionDist=$_POST['codGestionDist'];
$codProdOrigen=$_POST['codProdOrigen'];
$codProdDestino=$_POST['codProdDestino'];

$sql="select d.`cod_visitador`, d.`cantidad_planificada`, d.`territorio` from `distribucion_productos_visitadores` d where 
	d.`cod_ciclo`=$codCicloDist and d.`codigo_gestion`=$codGestionDist and d.`codigo_producto`='$codProdOrigen'";

$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$codVisitador=$dat[0];
	$cantPlani=$dat[1];
	$codCiudad=$dat[2];
	
	$sqlUpd="select d.`cod_visitador`, d.`cantidad_planificada` from `distribucion_productos_visitadores` d where 
	d.`cod_ciclo`=$codCicloDist and d.`codigo_gestion`=$codGestionDist and d.`codigo_producto`='$codProdDestino' and cod_visitador='$codVisitador'";
	
	$respUpd=mysql_query($sqlUpd);
	$filasUpd=mysql_num_rows($respUpd);
	
	if($filasUpd==0){
		$sqlActualiza="insert into distribucion_productos_visitadores values($codGestionDist,$codCicloDist,$codCiudad,$codLineaDist, $codVisitador, '$codProdDestino', $cantPlani,0,1,0)";
		$respActualiza=mysql_query($sqlActualiza);
	}else{
		$sqlActualiza="update distribucion_productos_visitadores set cantidad_planificada=cantidad_planificada+$cantPlani 
		where `cod_ciclo`=$codCicloDist and `codigo_gestion`=$codGestionDist and `codigo_producto`='$codProdDestino' and cod_visitador='$codVisitador'";
		$respActualiza=mysql_query($sqlActualiza);
	}
	$sqlDelete="delete from distribucion_productos_visitadores where 
	`cod_ciclo`=$codCicloDist and `codigo_gestion`=$codGestionDist and 
	`codigo_producto`='$codProdOrigen' and cod_visitador='$codVisitador'";
	$respDelete=mysql_query($sqlDelete);
}

echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			window.close();
			</script>";


?>