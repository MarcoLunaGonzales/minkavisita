<?php
require("conexion.inc");
echo "<script language='JavaScript'>
	function enviar(f)
	{	
		if(f.codProdOrigen.value==f.codProdDestino.value){
			alert('Debe elegir materiales diferentes.');
			return(false);
		}
		f.submit();
	}
</script>";
require("estilos_gerencia.inc");
require("funcion_nombres.php");
echo "<form name='form1' action='guardaCambiarMaterialDistribucion.php' method='post'>";
$global_linea_distribucion=$global_linea_distribucion;
$sql_gestion="select nombre_gestion from gestiones where estado='Activo'";
$resp_gestion=mysql_query($sql_gestion);
$dat_gestion=mysql_fetch_array($resp_gestion);
$nombre_gestion=$dat_gestion[0];
$nombreLinea=nombreLinea($global_linea_distribucion);

echo "<input type='hidden' name='codLineaDist' value='$global_linea_distribucion'>
<input type='hidden' name='codCicloDist' value='$global_ciclo_distribucion'>
<input type='hidden' name='codGestionDist' value='$global_gestion_distribucion'>
";

echo "<center><table border='0' class='textotit'><tr><td align='center'>Cambiar Material de Apoyo en Distribucion<br>
Ciclo: <strong>$global_ciclo_distribucion</strong> Gesti�n: <strong>$nombre_gestion</strong> Linea: <strong>$nombreLinea</strong></td></tr></table></center><br>";

echo "<center><table border='1' class='texto' cellspacing='0' width='80%'>";
echo "<tr><th>Material a reemplazar</th><th>Material de reemplazo</th></tr>";

$sql="select codigo_material, descripcion_material from material_apoyo where estado='Activo' order by 2";
$resp=mysql_query($sql);
echo "<tr><td><select name='codProdOrigen' class='texto'>";
while($dat=mysql_fetch_array($resp)){
	echo "<option value='$dat[0]'>$dat[1]</option>";
}
echo "</select></td>";

$sql="select codigo_material, descripcion_material from material_apoyo where estado='Activo' order by 2";
$resp=mysql_query($sql);
echo "<td><select name='codProdDestino' class='texto'>";
while($dat=mysql_fetch_array($resp)){
	echo "<option value='$dat[0]'>$dat[1]</option>";
}
echo "</select></td>";
echo "</table></center><br>";

echo "<center><input type='button' class='boton' value='Intercambiar' onClick='enviar(this.form)'></center></form>";
?>