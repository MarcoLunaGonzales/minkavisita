<?php
require("conexion.inc");
require('estilos_reportes_administracion.inc');
$global_gestion = 1007;

echo $ciclo_rpt;
echo $global_gestion;

$rpt_ciclo=$ciclo_rpt;

$rpt_gestion=$gestion_rpt;

$borrado_cab = mysql_query("delete from reporte_cab");
$borrado_detalle = mysql_query("delete from reporte_detalle");
// desde aqui formamos los dias y fechas del ciclo
$sql_dias_ini_fin = "select fecha_ini,fecha_fin from ciclos where cod_ciclo='$ciclo_rpt' and codigo_gestion='$global_gestion' and codigo_linea='1021'";
$resp_dias_ini_fin = mysql_query($sql_dias_ini_fin);
$dat_dias = mysql_fetch_array($resp_dias_ini_fin);
$fecha_ini_actual = $dat_dias[0];
$fecha_fin_actual = $dat_dias[1];
$fecha_actual = $fecha_ini_actual;
$inicio = $fecha_ini_actual;
$k = 0;
list($anio, $mes, $dia) = explode("-", $fecha_actual);
$dia1 = $dia;
while ($inicio < $fecha_fin_actual) {
    // echo $inicio."<br>";
    $ban = 0;
    while ($ban == 0) {
        $nueva1 = mktime(0, 0, 0, $mes, $dia1, $anio);
        $dia_semana = date("l", $nueva1);
        if ($dia_semana == 'Sunday' or $dia_semana == 'Saturday') {
            $dia1 = $dia1 + 1;
        } else {
            $ban = 1;
        } 
    } 
    $num_dia = intval($k / 5) + 1;
    if ($dia_semana == 'Monday') {
        $dias[$k] = "Lunes $num_dia";
    } 
    if ($dia_semana == 'Tuesday') {
        $dias[$k] = "Martes $num_dia";
    } 
    if ($dia_semana == 'Wednesday') {
        $dias[$k] = "Miercoles $num_dia";
    } 
    if ($dia_semana == 'Thursday') {
        $dias[$k] = "Jueves $num_dia";
    } 
    if ($dia_semana == 'Friday') {
        $dias[$k] = "Viernes $num_dia";
    } 
    $fecha_actual = date("Y-m-d", $nueva1);
    $inicio = $fecha_actual;
    list($anio, $mes, $dia) = explode("-", $fecha_actual);
    $dia1 = $dia + 1;
    $fecha_actual_formato = "$dia/$mes/$anio";
    $fechas[$k] = $fecha_actual_formato;
    $k++;
} 
// fin formar dias y fechas del ciclo
$sql_territorio = "select descripcion from ciudades where cod_ciudad='$rpt_territorio'";
$resp_territorio = mysql_query($sql_territorio);
$dat_territorio = mysql_fetch_array($resp_territorio);
$nombre_territorio = $dat_territorio[0];
echo "<table align='center' class='textotit'><tr><th>Reporte Boletas de Visita</th></tr></table><br>";
$sql_visitadores = "select fl.codigo_funcionario, f.paterno, f.materno, f.nombres, fl.codigo_linea, f.codigo_lineaclave
 from funcionarios_lineas fl, funcionarios f
 where f.codigo_funcionario=fl.codigo_funcionario and f.cod_cargo=1011 and f.estado='1' and 
 f.cod_ciudad='$rpt_territorio' and f.codigo_funcionario=1118";


$resp_visitadores = mysql_query($sql_visitadores);
$indice_reporte = 1;
while ($dat_visitadores = mysql_fetch_array($resp_visitadores)) {
    $codigo_funcionario = $dat_visitadores[0];
    $nombre_funcionario = "$dat_visitadores[1] $dat_visitadores[2] $dat_visitadores[3]";
    $linea_funcionario = $dat_visitadores[4];
    $codigo_lineaclave = $dat_visitadores[5];
    $sql_linea = "select codigo_linea, nombre_linea from lineas where codigo_linea='$linea_funcionario'";
    $resp_linea = mysql_query($sql_linea);
    $dat_linea = mysql_fetch_array($resp_linea);
    $codigo_linea = $dat_linea[0];
    $nombre_linea = $dat_linea[1]; 
    // sacamos el nombre del supervisor
    $sql_ciudadtroncal = "select tipo from ciudades where cod_ciudad='$rpt_territorio'";
    $resp_ciudadtroncal = mysql_query($sql_ciudadtroncal);
    $dat_ciudadtroncal = mysql_fetch_array($resp_ciudadtroncal);
    $tipo_ciudad = $dat_ciudadtroncal[0];
    if ($tipo_ciudad == 0) {
        $sql_supervisor = "select paterno, materno, nombres from funcionarios where cod_cargo='1001' 
		and cod_ciudad='115'";
        $resp_supervisor = mysql_query($sql_supervisor);
        $dat_supervisor = mysql_fetch_array($resp_supervisor);
        $nombre_supervisor = "$dat_supervisor[0] $dat_supervisor[2]";
    } else {
        $sql_supervisor = "select f.paterno, f.materno, f.nombres from funcionarios f, funcionarios_lineas fl 
		where f.cod_cargo='1001' and f.codigo_funcionario=fl.codigo_funcionario and 
		fl.codigo_linea='$codigo_lineaclave' and f.cod_ciudad='$rpt_territorio'";
        $resp_supervisor = mysql_query($sql_supervisor);
        $dat_supervisor = mysql_fetch_array($resp_supervisor);
        $nombre_supervisor = "$dat_supervisor[0] $dat_supervisor[2]";
    } 
    // desde aqui va el rutero maestro aprobado
    $sql = "select r.cod_contacto, r.cod_rutero, r.cod_visitador, r.dia_contacto, r.turno, r.zona_viaje 
    from rutero_maestro_aprobado r, rutero_maestro_cab_aprobado rmc, orden_dias o 
    where rmc.cod_rutero=r.cod_rutero and rmc.estado_aprobado=1 and r.cod_visitador='$codigo_funcionario' 
    and rmc.cod_visitador=r.cod_visitador
    and r.dia_contacto=o.dia_contacto and rmc.codigo_linea='$linea_funcionario' 
    and rmc.codigo_ciclo='$ciclo_rpt' and rmc.codigo_gestion='$gestion_rpt' order by o.id";
    
   
    $resp = mysql_query($sql);
    $filas_rutero = mysql_num_rows($resp); 
    // echo "<h2>$nombre_funcionario $linea_funcionario</h2>";
    // saca el nombre del rutero maestro
    $sql_nom_rutero = mysql_query("select nombre_rutero from rutero_maestro_cab where cod_rutero='$rutero' and cod_visitador='$global_visitador'");
    $dat_nom_rutero = mysql_fetch_array($sql_nom_rutero);
    $nombre_rutero = $dat_nom_rutero[0]; 
    // fin sacar nombre
    $sql_cantidad_total_boletas = "select rd.cod_contacto from rutero_maestro_aprobado r, rutero_maestro_cab_aprobado rmc, 
    rutero_maestro_detalle_aprobado rd
	where rmc.cod_rutero=r.cod_rutero and rmc.estado_aprobado=1 and r.cod_visitador='$codigo_funcionario'
	and rmc.cod_visitador=r.cod_visitador and r.cod_visitador=rd.cod_visitador 
	and rmc.codigo_linea='$linea_funcionario' and r.cod_contacto=rd.cod_contacto and 
	rmc.codigo_ciclo='$ciclo_rpt' and rmc.codigo_gestion='$gestion_rpt'";
    $resp_cantidad_total_boletas = mysql_query($sql_cantidad_total_boletas);
    $cantidad_total_boletas = mysql_num_rows($resp_cantidad_total_boletas);
    $indice_boleta = 1;
    while ($dat = mysql_fetch_array($resp)) {
        $cod_contacto = $dat[0];
        $cod_ciclo = $dat[1];
        $dia_contacto = $dat[3];
        $turno = $dat[4];
        $zona_de_viaje = $dat[5];
        for($ww = 0;$ww <= $k;$ww++) {
            if ($dias[$ww] == $dia_contacto) {
                $fecha_planificada = $fechas[$ww];
            } 
        } 
        // echo $fecha_planificada;
        if ($zona_de_viaje == 1) {
            $fondo_fila = "#FFD8BF";
        } else {
            $fondo_fila = "";
        } 
        $sql1 = "select c.orden_visita, m.cod_med, m.ap_pat_med, m.ap_mat_med, m.nom_med, d.direccion, c.cod_especialidad, c.categoria_med, c.estado, m.telf_med, m.telf_celular_med
				from rutero_maestro_detalle_aprobado c, medicos m, direcciones_medicos d
					where (c.cod_contacto=$cod_contacto) and (c.cod_visitador=$codigo_funcionario) and
					(c.cod_med=m.cod_med) and (m.cod_med=d.cod_med) and (c.cod_zona=d.numero_direccion) 
					order by c.orden_visita";
        $resp1 = mysql_query($sql1);
        $contacto = "<table class='textomini' width='100%'>";
        $contacto = $contacto . "<tr><th width='5%'>Orden</th><th width='35%'>Medico</th><th width='10%'>Especialidad</th><th width='10%'>Categoria</th><th width='30%'>Direccion</th></tr>";
        while ($dat1 = mysql_fetch_array($resp1)) {
            $orden_visita = $dat1[0];
            $codigo_medico = $dat1[1];
            //VEMOS SI PERTENECE A UN GRUPO ESPECIAL
            $sqlGrupo="select g.`nombre_grupo_especial` from `grupo_especial` g, `grupo_especial_detalle` gd
						where g.`codigo_grupo_especial`=gd.`codigo_grupo_especial` and gd.`cod_med`='$codigo_medico' and g.`codigo_linea`=$linea_funcionario";
						$respGrupo=mysql_query($sqlGrupo);
						$cadGrupo="";
						while($datGrupo=mysql_fetch_array($respGrupo)){
							$cadGrupo.=" $datGrupo[0]"; 
						}
						
            $pat = $dat1[2];
            $mat = $dat1[3];
            $nombre = $dat1[4];
            $sql_dir = "select direccion from direcciones_medicos where cod_med=$codigo_medico";
            $resp_dir = mysql_query($sql_dir);
            $direccion_medico = "<table border=0 class='textomini'>";
            $indice_direccion = 1;
            $dir_rep1 = "";
            $dir_rep2 = "";
            $dir_rep3 = "";
            while ($dat_dir = mysql_fetch_array($resp_dir)) {
                $dir = $dat_dir[0];
                
                if ($indice_direccion == 1) {
                    $dir_rep1 = $dir;
                } 
                if ($indice_direccion == 2) {
                    $dir_rep2 = $dir;
                } 
                if ($indice_direccion == 3) {
                    $dir_rep3 = $dir;
                } 
                $direccion_medico = "$direccion_medico<tr><td align='left'>$dir</td></tr>";
                $indice_direccion++;
            } 
            $direccion_medico = "$direccion_medico</table>";
            $direccion = $dat1[5];
            $nombre_medico = "$pat $mat $nombre";
            $espe = $dat1[6];
            $cat = $dat1[7];
            $telefono_medico = $dat1[9];
            $celular_medico = $dat1[10]; 
            // saca el numero de visita
            $sql_numero_visita = "select distinct(o.id), rmd.cod_contacto, rmd.orden_visita from 
            orden_dias o, rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd
						where rm.cod_contacto=rmd.cod_contacto and rmc.cod_rutero=rm.cod_rutero and rmc.estado_aprobado=1 and rmc.codigo_linea='$linea_funcionario' and rm.cod_visitador='$codigo_funcionario' and rmd.cod_med='$codigo_medico'
						and o.dia_contacto=rm.dia_contacto 
						and rmc.codigo_ciclo='$rpt_ciclo' and rmc.codigo_gestion='$rpt_gestion' order by o.id";						
            $resp_numero_visita = mysql_query($sql_numero_visita);
            $indice_visita = 1;
            while ($dat_numero_visita = mysql_fetch_array($resp_numero_visita)) {
                $contacto_sistema = $dat_numero_visita[1];
                $orden_visita_sistema = $dat_numero_visita[2];
                if ($contacto_sistema == $cod_contacto and $orden_visita_sistema == $orden_visita) {
                    $numero_visita = $indice_visita;
                } 
                $indice_visita++;
            } 
            
            // verificamos si el medico no pertenece a un grupo especial
            $sql_verifica_parrillaespecial = "select g.codigo_grupo_especial from grupo_especial g, grupo_especial_detalle gd
								where g.codigo_grupo_especial=gd.codigo_grupo_especial and g.agencia='$rpt_territorio'
								and g.codigo_linea='$linea_funcionario' and gd.cod_med='$codigo_medico' and g.tipo_grupo=2";
            $resp_verifica_parrillaespecial = mysql_query($sql_verifica_parrillaespecial);
            $filas_verifica_parrillaespecial = mysql_num_rows($resp_verifica_parrillaespecial);
            if ($filas_verifica_parrillaespecial != 0) {
                $dat_parrilla_especial = mysql_fetch_array($resp_verifica_parrillaespecial);
                $grupo_especial = $dat_parrilla_especial[0];
                echo "parrilla especial";
                $sql_parrilla = "select * from parrilla_especial where codigo_grupo_especial='$grupo_especial' and
						codigo_linea='$linea_funcionario' and cod_ciclo='$ciclo_rpt' and codigo_gestion='$gestion_rpt' 
						and cod_especialidad='$espe' and numero_visita='$numero_visita' order by numero_visita";
            } else { // fin verificar grupo especial
                // aplicamos una consulta para saber si el visitador hace linea de visita para la especialidad
                $verifica_lineas = "select l.codigo_l_visita from lineas_visita l, lineas_visita_especialidad le, lineas_visita_visitadores lv
					where l.codigo_l_visita=le.codigo_l_visita and l.codigo_l_visita=lv.codigo_l_visita and le.codigo_l_visita=lv.codigo_l_visita
					and l.codigo_linea='$linea_funcionario' and lv.codigo_funcionario='$codigo_funcionario' and le.cod_especialidad='$espe'";
                $resp_verifica_lineas = mysql_query($verifica_lineas);
                $filas_verifica = mysql_num_rows($resp_verifica_lineas);
                if ($filas_verifica != 0) {
                    $dat_verifica = mysql_fetch_array($resp_verifica_lineas);
                    $codigo_l_visita = $dat_verifica[0];
                } else {
                    $codigo_l_visita = 0;
                } 
                // ahora verificamos si existe alguna parrilla solamente para la regional
                $sql_verificaparrillaregional = "select * from parrilla where codigo_linea='$linea_funcionario' and 
                agencia='$rpt_territorio' and cod_ciclo='$ciclo_rpt' and codigo_gestion='$global_gestion' and 
                codigo_l_visita='$codigo_l_visita' and cod_especialidad='$espe' and categoria_med='$cat' and numero_visita='$numero_visita' 
                order by numero_visita";
                $resp_verificaparrillaregional = mysql_query($sql_verificaparrillaregional);
                $filas_verificaparrillaregional = mysql_num_rows($resp_verificaparrillaregional);
                if ($filas_verificaparrillaregional == 0) {
                    $sql_parrilla = "select * from parrilla where codigo_linea='$linea_funcionario' and cod_ciclo='$ciclo_rpt' and codigo_gestion='$gestion_rpt' and codigo_l_visita='$codigo_l_visita' and cod_especialidad='$espe' and categoria_med='$cat' and numero_visita='$numero_visita' order by numero_visita";
                } else {
                    $sql_parrilla = "select * from parrilla where codigo_linea='$linea_funcionario' and cod_ciclo='$ciclo_rpt' and codigo_gestion='$gestion_rpt' and codigo_l_visita='$codigo_l_visita' and agencia='$rpt_territorio' and cod_especialidad='$espe' and categoria_med='$cat' and numero_visita='$numero_visita' order by numero_visita";
                } 

                
            } 
            // SACAMOS LA PARRILLA PARA EL NUMERO DE VISITA            
            
            $resp_parrilla = mysql_query($sql_parrilla);
            
            while ($dat_parrilla = mysql_fetch_array($resp_parrilla)) {
                $cod_parrilla = $dat_parrilla[0];
                $cod_ciclo = $dat_parrilla[1];
                $cod_espe = $dat_parrilla[2];
                $cod_cat = $dat_parrilla[3];
                $numero_de_visita = $dat_parrilla[7];
                $agencia = $dat_parrilla[8];
                if ($filas_verifica_parrillaespecial == 0) {
                    $sql_parrilla_detalle = "select m.codigo, m.descripcion, m.presentacion, p.cantidad_muestra, mm.descripcion_material, p.cantidad_material, p.observaciones
					from muestras_medicas m, parrilla_detalle p, material_apoyo mm
					where p.codigo_parrilla=$cod_parrilla and m.codigo=p.codigo_muestra and mm.codigo_material=p.codigo_material order by p.prioridad";
                } else {
                    $sql_parrilla_detalle = "select m.codigo, m.descripcion, m.presentacion, p.cantidad_muestra, mm.descripcion_material, p.cantidad_material, p.observaciones
				from muestras_medicas m, parrilla_detalle_especial p, material_apoyo mm
				where p.codigo_parrilla_especial=$cod_parrilla and m.codigo=p.codigo_muestra and mm.codigo_material=p.codigo_material order by p.prioridad";
                } 
                $resp_parrilla_detalle = mysql_query($sql_parrilla_detalle);
                $filas_parrilla = mysql_num_rows($resp_parrilla_detalle);
                $parrilla_medica = "<table class='textomini' width='100%' border='1'>";
                $parrilla_medica = $parrilla_medica . "<tr><th colspan='2'>Productos</th><th colspan='2'>Material de Apoyo</th><th>Observaciones de PMA(*)</th></tr>";
                
                $sql_insert_reporte_cab = "insert into reporte_cab values($indice_reporte,'$nombre_territorio',
                '$nombre_funcionario','$nombre_medico','$nombre_linea','$ciclo_rpt','$gestion_rpt',
                '$indice_boleta de $cantidad_total_boletas','$dir_rep1','$dir_rep2','$dir_rep3','$telefono_medico',
                '$celular_medico','$espe','$fecha_planificada','$nombre_supervisor','$cadGrupo')";
                $resp_insert_reporte_cab = mysql_query($sql_insert_reporte_cab);
                $orden_promocional = 1;
                while ($dat_parrilla_detalle = mysql_fetch_array($resp_parrilla_detalle)) {
                    $codMuestra=$dat_parrilla_detalle[0];
                    $muestra = "$dat_parrilla_detalle[1] $dat_parrilla_detalle[2]";
                    $cant_muestra = $dat_parrilla_detalle[3];
                    $material = $dat_parrilla_detalle[4];
                    $cant_material = $dat_parrilla_detalle[5]; 
                    
                    //VERIFICAMOS EN SISTEMA
                    
                    // algunas modificaciones para que salga bonito
                    if ($material == " Sin Material Apoyo") {
                        $material = "";
                    } 
                    if ($cant_material == 0) {
                        $cant_material = "";
                    } 
                    $insert_reporte_det = "insert into reporte_detalle values($indice_reporte,$orden_promocional,'$muestra','$cant_muestra','$material','$cant_material')";
                    $resp_reporte_det = mysql_query($insert_reporte_det);
                    $parrilla_medica = $parrilla_medica . "<tr><td width='30%'>$muestra</td><td width='2%'>$cant_muestra</td><td width='30%'>$material</td><td width='2%'>$cant_material</td><td align='center' width='36%'>&nbsp;</td></tr>";
                    $orden_promocional++;
                } 
                for($ii = $filas_parrilla;$ii < 12;$ii++) {
                    $insert_reporte_det = "insert into reporte_detalle values($indice_reporte,$orden_promocional,'','','','')";
                    $resp_reporte_det = mysql_query($insert_reporte_det);
                    $orden_promocional++;
                    $parrilla_medica = $parrilla_medica . "<tr><td width='30%'>&nbsp;</td><td width='2%'>&nbsp;</td><td width='30%'>&nbsp;</td><td width='2%'>&nbsp;</td><td align='center' width='36%'>&nbsp;</td></tr>";
                } 
                $parrilla_medica = $parrilla_medica . "</table>";
            } 
            // FIN SACAR PARRILLA
            // fin sacar numero visita
            $cabecera_izq = "<table border='0' class='textomini' width='100%'><tr><td width='50%'>Gesti�n: $gestion_rpt</td><td width='50%'>Ciclo: $ciclo_rpt</td></tr>";
            $cabecera_izq = "$cabecera_izq<tr><td width='50%'>Territorio: $nombre_territorio </td><td width='50%'>L�nea: $nombre_linea</td></tr>";
            $cabecera_izq = "$cabecera_izq<tr><td colspan=2>Visitador: $nombre_funcionario</td></tr>";
            $cabecera_izq = "$cabecera_izq<tr><td colspan=2>Supervisor: $nombre_supervisor</td></tr>";
            $cabecera_izq = "$cabecera_izq<tr><td colspan=2>N�mero de Boleta: $indice_boleta de $cantidad_total_boletas  Visita Nro: $numero_visita</td></tr></table>";
            $cabecera_der = "<table border='0' class='textomini' width='100%'><tr><td>Medico:</td><td>$nombre_medico</td></tr>";
            $cabecera_der = "$cabecera_der<tr><td>Direcciones:</td><td>$direccion_medico</td></tr>";
            $cabecera_der = "$cabecera_der<tr><td>Tel�fono: </td><td>$telefono_medico </td></tr>";
            $cabecera_der = "$cabecera_der<tr><td>Especialidad: </td><td>$espe $cadGrupo</td></tr></table>";
            echo "<table class='textomini' width='100%' border='1' align='center'>";
            echo "<tr><td width='40%'>$cabecera_izq</td><td width='40%'>$cabecera_der</td><td width='20%' align='center'><img src='imagenes/logo_cofar.png'></td></tr>";
            echo "<tr><td colspan='3'>$parrilla_medica</td></tr>";
            echo "<tr><td>Comentarios:<br><br></td><td>Fecha de Visita:<br><br></td><td>Sello y firma Medico<br><br></td></tr>";
            echo "<tr><td colspan='3'>(*)PMA: Producto y/o Material de Apoyo.</td></tr>";
            echo "</table><br><br>";
            $indice_boleta++;
            $indice_reporte++;
        } 
    } 
    // fin rutero maestro aprobado
} 
echo "<script language='JavaScript'>
	location.href='boletaspdf.php';
</script>";
?>
