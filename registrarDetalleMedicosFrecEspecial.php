<script language=JavaScript>
function validar(f, indice, frecPermitida){
	indice=indice-1;
	//fin validacion frecPermitida
	
	var bandera=0;
	if(indice==8){
		if((f.diaContactoAgru1.value>=1 && f.diaContactoAgru1.value<=5) && (f.diaContactoAgru2.value>=1 && f.diaContactoAgru2.value<=5) && (f.diaContactoAgru3.value>=6 && f.diaContactoAgru3.value<=10) && (f.diaContactoAgru4.value>=11 && f.diaContactoAgru4.value<=15) && (f.diaContactoAgru5.value>=11 && f.diaContactoAgru5.value<=15) && (f.diaContactoAgru6.value>=16 && f.diaContactoAgru6.value<=20)){
			
		}else{
				if((f.diaContactoAgru1.value>=1 && f.diaContactoAgru1.value<=5) && (f.diaContactoAgru2.value>=6 && f.diaContactoAgru2.value<=10) && (f.diaContactoAgru3.value>=6 && f.diaContactoAgru3.value<=10) && (f.diaContactoAgru4.value>=11 && f.diaContactoAgru4.value<=15) && (f.diaContactoAgru5.value>=16 && f.diaContactoAgru5.value<=20) && (f.diaContactoAgru6.value>=16 && f.diaContactoAgru6.value<=20)){
				}else{
					bandera=1;	
				}
		}
	}	
	if(indice==6){
		if((f.diaContactoAgru1.value>=1 && f.diaContactoAgru1.value<=5) && (f.diaContactoAgru2.value>=1 && f.diaContactoAgru2.value<=5) && (f.diaContactoAgru3.value>=6 && f.diaContactoAgru3.value<=10) && (f.diaContactoAgru4.value>=11 && f.diaContactoAgru4.value<=15) && (f.diaContactoAgru5.value>=11 && f.diaContactoAgru5.value<=15) && (f.diaContactoAgru6.value>=16 && f.diaContactoAgru6.value<=20)){
			
		}else{
				if((f.diaContactoAgru1.value>=1 && f.diaContactoAgru1.value<=5) && (f.diaContactoAgru2.value>=6 && f.diaContactoAgru2.value<=10) && (f.diaContactoAgru3.value>=6 && f.diaContactoAgru3.value<=10) && (f.diaContactoAgru4.value>=11 && f.diaContactoAgru4.value<=15) && (f.diaContactoAgru5.value>=16 && f.diaContactoAgru5.value<=20) && (f.diaContactoAgru6.value>=16 && f.diaContactoAgru6.value<=20)){
				}else{
					bandera=1;	
				}
		}
	}	
	if(indice==4){
		if((f.diaContactoAgru1.value>=1 && f.diaContactoAgru1.value<=5) && (f.diaContactoAgru2.value>=6 && f.diaContactoAgru2.value<=10) && (f.diaContactoAgru3.value>=11 && f.diaContactoAgru3.value<=15) && (f.diaContactoAgru4.value>=16 && f.diaContactoAgru4.value<=20)){
			
		}else{
			bandera=1;	
		}
	}

	if(bandera==0){
		f.submit();
	}else{
		alert("Los dias agrupados no cumplen con la secuencia establecida.");
	}
}
</script>

<?php
require("conexion.inc");
require("estilos_visitador.inc");
require("funcion_nombres.php");

echo "<form name='form1' action='guardaDetalleMedicosFrecEspecial.php' method='post'>";

$codRutero=$cod_rutero;
$codMed=$cod_med;
$codCiclo=$cod_ciclo;
$codGestion=$cod_gestion;

echo "<input type='hidden' name='cod_ciclo' value='$codCiclo'>";
echo "<input type='hidden' name='cod_gestion' value='$codGestion'>";
echo "<input type='hidden' name='cod_rutero' value='$codRutero'>";
echo "<input type='hidden' name='cod_med' value='$codMed'>";

$sqlFrec="select frecuencia_linea, frecuencia_permitida from categorias_lineas where cod_med='$codMed' and codigo_linea='$global_linea'";
$respFrec=mysql_query($sqlFrec);
$frecuenciaLinea=mysql_result($respFrec,0,0);
$frecuenciaPermitida=mysql_result($respFrec,0,1);

$nombreMed=nombreMedico($codMed);
echo "<table border='0' class='textotit' align='center'><tr><th>Registrar Detalle de Medicos con Frecuencia Reducida
<br>Medico: $nombreMed<br>Frecuencia Linea: $frecuenciaLinea  Frecuencia Reducida: $frecuenciaPermitida</th></tr></table><br>";

echo "<table border='1' width='50%' cellspacing='0' class='texto' align='center'><tr>
<th>Dias de Visita</th><th>Dia de Agrupacion</th></tr>";

$sql="select rd.`cod_med`, r.`dia_contacto`, o.id from `rutero_maestro_cab` rc, 
		`rutero_maestro` r, `rutero_maestro_detalle` rd, `orden_dias` o 
		where rc.`cod_rutero` = r.`cod_rutero` and r.`cod_contacto` = rd.`cod_contacto` and 
		rc.`cod_rutero` = '$codRutero' and rd.`cod_med`='$codMed' and r.`dia_contacto`=o.`dia_contacto` order by o.id;";

$resp=mysql_query($sql);
$indice=1;
while($dat=mysql_fetch_array($resp)){
	$codMed=$dat[0];
	$diaContacto=$dat[1];
	$codDia=$dat[2];
	echo "<input type='hidden' name='diaContacto$indice' value='$codDia'>";
	echo "<tr><td>$diaContacto</td>";
	$resp1=mysql_query($sql);
	echo "<td align='center'><select name='diaContactoAgru$indice' class='texto'>";
	while($dat1=mysql_fetch_array($resp1)){
		$nombreDiaAgrupado=$dat1[1];
		$codDiaAgrupado=$dat1[2];
		if($codDia==$codDiaAgrupado){
			echo "<option value='$codDiaAgrupado' selected>$nombreDiaAgrupado</option>";
		}else{
			echo "<option value='$codDiaAgrupado'>$nombreDiaAgrupado</option>";
		}
	}
	echo "</select></td></tr>";
	$indice++;
}
echo "<input type='hidden' name='num_registros' value='$indice'>";
echo "</table><br>";
//echo "<center><input type='button' class='boton' value='Guardar' onClick='validar(this.form, $indice, $frecuenciaPermitida)'></center>";
echo "<center><input type='submit' class='boton' value='Guardar'></center>";
echo "</form>";
?>