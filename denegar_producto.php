<style>
	tr td {
		padding: 5px 5px;
	}
	tr th {
		padding: 10px 5px;
	}
</style>
<?php

echo "<script language='Javascript'>
function enviar_nav() {	
	location.href='registrar_prod_negado.php?j_cod_med=$j_cod_med';
}
function eliminar_nav(f) {
	var i;
	var j=0;
	datos=new Array();
	for(i=0;i<=f.length-1;i++) {
		if(f.elements[i].type=='checkbox') {	
			if(f.elements[i].checked==true) {	
				datos[j]=f.elements[i].value;
				j=j+1;
			}
		}
	}
	if(j==0) {	
		alert('Debe seleccionar al menos un Producto para eliminarlo de la lista de Productos Filtrados.');
	} else {
		if(confirm('Esta seguro de eliminar los datos.')) {
			location.href='eliminar_prod_negado.php?datos='+datos+'&j_cod_med=$j_cod_med';
		} else {
			return(false);
		}
	}
}
</script>";
require("conexion.inc");
require("estilos_regional_pri.inc");
$sql_cab=mysql_query("SELECT ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$j_cod_med'");
$dat_cab=mysql_fetch_array($sql_cab);
$nombre_medico="$dat_cab[2] $dat_cab[0] $dat_cab[1]";
$sql_cab_espe=mysql_query("SELECT c.cod_especialidad,c.categoria_med, es.desc_especialidad from categorias_lineas c, especialidades_medicos e, especialidades es where c.cod_med = e.cod_med and c.cod_especialidad = es.cod_especialidad and c.cod_med = '$j_cod_med' and c.cod_especialidad = e.cod_especialidad and c.codigo_linea = '$global_linea' order by es.desc_especialidad");
$num_filas_cab = mysql_num_rows($sql_cab_espe);
if($num_filas_cab==1) {	
	$dat=mysql_fetch_array($sql_cab_espe);
	$espe_cab="$dat[2]:<strong>$dat[0]</strong> Categoria:<strong>$dat[1]</strong>";
} else {	
	while($dat=mysql_fetch_array($sql_cab_espe)) {	
		$espe_cab=$espe_cab."$dat[2]:<strong>$dat[0]</strong> Categoria:<strong>$dat[1]</strong> ";
	}
}
echo "<center><table border='0' class='textotit'><tr><td>Quitar Productos x Medico</td></tr></table></center>";
echo "<center><table border='0' class='textotit'><tr><td>Medico: <strong>$nombre_medico</strong> $espe_cab</td></tr></table></center><br>";
echo "<form>";
$sql="SELECT m.codigo,m.descripcion,m.presentacion from muestras_negadas n, muestras_medicas m where m.codigo = n.codigo_muestra and n.codigo_linea = $global_linea and n.cod_med = '$j_cod_med' order by m.descripcion";
$resp = mysql_query($sql);
$numero_filas = mysql_num_rows($resp);
if($numero_filas != 0 || $numero_filas == '') {
	echo "<center><table border='1' class='texto' cellspacing='0'>";
	echo "<tr><th>&nbsp;</th><th>Producto</th><th>Presentación</th></tr>";
	while($dat=mysql_fetch_array($resp)) {
		$codigo_mm = $dat[0];
		$mm        = $dat[1];
		$pres      = $dat[2];
		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo_mm'></td><td>$mm</td><td>$pres</td></tr>";
	}
	echo "</table></center><br>";
	echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Quitar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td></tr></table></center>";
} else {	
	echo "<center><table border='0' class='texto' cellspacing='0'>";
	echo "<tr><th>No existen Productos Filtrados para este Medico</th></tr></table></center><br>";
	echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Quitar' name='adicionar' class='boton' onclick='enviar_nav()'></td></tr></table></center>";
}
echo "</form>";

?>