<?php

/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita M&eacute;dica
 * * @copyright 2005 
 */
error_reporting(0);
echo "<form name='form1' method='post' action='guarda_devolucion_almacen.php'>";
require("conexion.inc");
header("Content-Type: text/html; charset=UTF-8");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
require("estilos_almacenes.inc");
$cod_ciclo = $_GET['cod_ciclo'];
$cod_gestion = $_GET['cod_gestion'];
$cod_visitador = $_GET['cod_visitador'];
$cod_devolucion = $_GET['cod_devolucion'];
$tipo = $_GET['tipo'];
if ($tipo == 2) {
    $tip = 'disabled';
} else {
    $tip = '';
}
if ($tipo == 2) {
    $sqlNombreVis = mysql_query("select paterno, nombres from funcionarios where codigo_funcionario=$cod_visitador");
    $datNombreVis = mysql_fetch_array($sqlNombreVis);
    $nombreVisitador = "$datNombreVis[0] $datNombreVis[1]";

    $sqlCantidades = "select d.codigo_material, concat(m.descripcion,' ', m.presentacion), d.cantidad_devolucion, d.cantidad_ing_almacen from devoluciones_ciclodetalle d, muestras_medicas m
				 where d.codigo_devolucion=$cod_devolucion and d.codigo_material=m.codigo order by 2";

    $respCantidades = mysql_query($sqlCantidades);
    echo "<center><table border='0' class='textotit'><tr><th>Devolucion de MM y MA por Visitador<br>Ciclo: $cod_ciclo Visitador: $nombreVisitador</th></tr></table></center><br>";
    echo "<center><table border='1' class='texto' cellspacing='0' width='60%'>";
    echo "<tr><th>Producto</th><th>Cantidad</th><th>Cantidad Ingresado por almacen</th></tr>";
    while ($datCantidades = mysql_fetch_array($respCantidades)) {
        $codigoMaterial = $datCantidades[0];
        $nombreMaterial = $datCantidades[1];
        $cantidadMaterial = $datCantidades[2];
        $cantidadalmacen = $datCantidades[3];

        if($cantidadalmacen>0){
			echo "<tr><td>$nombreMaterial</td><td align='center'>$cantidadMaterial</td>
			<td align='center'>$cantidadalmacen</td></tr>";

		}
    }
    echo "</table></center><br>";
    echo "<input type='hidden' name='cod_devolucion' value='$cod_devolucion'>";
    echo "<input type='hidden' name='cod_ciclo' value='$cod_ciclo'>";
    echo "<input type='hidden' name='cod_gestion' value='$cod_gestion'>";
    echo "<input type='hidden' name='cod_visitador' value='$cod_visitador'>";

    echo "<input type='hidden' name='nombre_visitador' value='$nombreVisitador'>";
    if ($tipo == 1) {
        echo "<center><input type='Submit' value='Guardar' class='boton'></center>";
    }
    echo "</form>";
} else {
    $sqlNombreVis = mysql_query("select paterno, nombres from funcionarios where codigo_funcionario=$cod_visitador");
    $datNombreVis = mysql_fetch_array($sqlNombreVis);
    $nombreVisitador = "$datNombreVis[0] $datNombreVis[1]";

    $sqlCantidades = "select d.codigo_material, concat(m.descripcion,' ', m.presentacion), d.cantidad_devolucion from devoluciones_ciclodetalle d, muestras_medicas m
				 where d.codigo_devolucion=$cod_devolucion and d.codigo_material=m.codigo";
    $respCantidades = mysql_query($sqlCantidades);
    echo "<center><table border='0' class='textotit'><tr><th>Devolucion de MM y MA por Visitador<br>Ciclo: $cod_ciclo Visitador: $nombreVisitador</th></tr></table></center><br>";
    echo "<center><table border='1' class='texto' cellspacing='0' width='60%'>";
    echo "<tr><th>Producto</th><th>Cantidad</th><th>Cantidad a Registrar</th></tr>";
    while ($datCantidades = mysql_fetch_array($respCantidades)) {
        $codigoMaterial = $datCantidades[0];
        $nombreMaterial = $datCantidades[1];
        $cantidadMaterial = $datCantidades[2];

        echo "<tr><td>$nombreMaterial</td><td align='center'>$cantidadMaterial</td>
			<td align='center'><input type='text' name='$codigoMaterial' size='5' value='$cantidadMaterial' class='texto' $tip></td></tr>";
    }
    echo "</table></center><br>";
    echo "<input type='hidden' name='cod_devolucion' value='$cod_devolucion'>";
    echo "<input type='hidden' name='cod_ciclo' value='$cod_ciclo'>";
    echo "<input type='hidden' name='cod_gestion' value='$cod_gestion'>";
    echo "<input type='hidden' name='cod_visitador' value='$cod_visitador'>";

    echo "<input type='hidden' name='nombre_visitador' value='$nombreVisitador'>";
    if ($tipo == 1) {
        echo "<center><input type='Submit' value='Guardar' class='boton'></center>";
    }
    echo "</form>";
}
?>