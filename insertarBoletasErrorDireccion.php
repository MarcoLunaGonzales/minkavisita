<?php
error_reporting(0);
set_time_limit(0);

require("conexion.inc");
require('estilos_reportes_administracion.inc');
require("funcion_nombres.php");

echo "Hora inicio: " . date("H:i:s");

$rpt_ciclo = "2";
$rpt_gestion = "2018/2019";
$global_gestion = "1000";

//$agencia=$_GET['agencia'];

$sql_dias_ini_fin = "SELECT fecha_ini,fecha_fin from ciclos where cod_ciclo='$rpt_ciclo' and 
codigo_gestion='$global_gestion' and codigo_linea='1032'";
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
    $fecha_actual_formato = "$anio-$mes-$dia";
    $fechas[$k] = $fecha_actual_formato;
    $k++;
}

	//AQUI PONDREMOS EL LISTADO DE TODOS LOS VISITADORES ACTIVOS
	//$cod_visitador="118";
	//$cod_linea="1046";
	
$sql_visitadores="SELECT f.codigo_funcionario, f.paterno, f.materno, f.nombres, rc.codigo_linea, rc.codigo_linea, f.cod_zeus, f.cod_ciudad
	from rutero_maestro_cab_aprobado rc, funcionarios f
	where f.codigo_funcionario=rc.cod_visitador and 
	rc.codigo_ciclo='$rpt_ciclo' and rc.codigo_gestion='$global_gestion' 
	and f.estado=1 
	and f.codigo_funcionario in (142)
	order by f.cod_ciudad";

echo $sql_visitadores;
//	rc.codigo_linea in (1041)";

$resp_visitadores  = mysql_query($sql_visitadores);
//$indice_reporte    = 1;
$sqlXXX="select IFNULL(max(id_boleta+1),1) from boletas_visita_cabXXX";
$respXXX=mysql_query($sqlXXX);
$indice_reporte=mysql_result($respXXX,0,0);

while ($dat_visitadores = mysql_fetch_array($resp_visitadores)) {
    $codigo_funcionario = $dat_visitadores[0];
    $nombre_funcionario = trim($dat_visitadores[1])." ".trim($dat_visitadores[2])." ".trim($dat_visitadores[3]);
    $linea_funcionario  = $dat_visitadores[4];
    $codigo_lineaclave  = $dat_visitadores[5];
	$codZeus=$dat_visitadores[6];
	$codZeus=$codigo_funcionario;
	$rpt_territorio=$dat_visitadores[7];
	
	$nombre_territorio = nombreTerritorio($rpt_territorio);

	
    $nombre_linea       = nombreLinea($linea_funcionario);
    $nombre_supervisor = nombreSupervisor($rpt_territorio);
    
    $sql = "SELECT r.cod_contacto, r.cod_rutero, r.cod_visitador, r.dia_contacto, r.turno, r.zona_viaje from rutero_maestro_aprobado r, 
	rutero_maestro_cab_aprobado rmc, orden_dias o where rmc.cod_rutero=r.cod_rutero and rmc.estado_aprobado=1 
	and r.cod_visitador='$codigo_funcionario'and rmc.cod_visitador=r.cod_visitador and r.dia_contacto=o.dia_contacto and 
	rmc.codigo_linea='$linea_funcionario' and rmc.codigo_ciclo = '$rpt_ciclo' and rmc.codigo_gestion='$global_gestion' 
	order by o.id, r.turno";
//	and rmc.codigo_linea in (1041)
	
    $resp = mysql_query($sql);
    $filas_rutero = mysql_num_rows($resp);
	
    $sql_cantidad_total_boletas = "SELECT count(*) from rutero_maestro_aprobado r, rutero_maestro_cab_aprobado rmc, 
	rutero_maestro_detalle_aprobado rd where rmc.cod_rutero=r.cod_rutero and r.cod_contacto=rd.cod_contacto and rmc.estado_aprobado=1 
	and r.cod_visitador='$codigo_funcionario' and rmc.cod_visitador=r.cod_visitador and r.cod_visitador=rd.cod_visitador 
	and rmc.codigo_linea='$linea_funcionario' and r.cod_contacto=rd.cod_contacto and rmc.codigo_ciclo='$rpt_ciclo' 
	and rmc.codigo_gestion='$global_gestion'";	
    $resp_cantidad_total_boletas = mysql_query($sql_cantidad_total_boletas);
    $cantidad_total_boletas = mysql_result($resp_cantidad_total_boletas,0,0);

	/*ESTO DEBE CAMBIARSE A 1*/
    $indice_boleta = 311;
	
	
    while ($dat = mysql_fetch_array($resp)) {
        $cod_contacto = $dat[0];
        $cod_ciclo = $dat[1];
        $dia_contacto = $dat[3];
        $turno = $dat[4];
        $zona_de_viaje = $dat[5];
        for ($ww = 0; $ww <= $k; $ww++) {
            if ($dias[$ww] == $dia_contacto) {
                $fecha_planificada = $fechas[$ww];
            }
        }

		
        $sql1 = "SELECT c.orden_visita, m.cod_med, m.ap_pat_med, m.ap_mat_med, m.nom_med, 
		(select d.direccion from direcciones_medicos d where d.cod_med=c.cod_med limit 0,1) as direccion,
		c.cod_especialidad, 
		c.categoria_med, c.estado, m.telf_med, m.telf_celular_med, m.cod_catcloseup from rutero_maestro_detalle_aprobado c, 
		medicos m, direcciones_medicos d where (c.cod_contacto=$cod_contacto) and (c.cod_visitador=$codigo_funcionario) and 
		(c.cod_med=m.cod_med) and (m.cod_med=d.cod_med) and c.cod_med=d.cod_med and (c.cod_zona<>d.numero_direccion) order by c.orden_visita";
        $resp1 = mysql_query($sql1);
        while ($dat1 = mysql_fetch_array($resp1)) {
			
			$strNegados="";
			
            $orden_visita = $dat1[0];
            $codigo_medico = $dat1[1];
            $pat = trim($dat1[2]);
            $mat = trim($dat1[3]);
            $nombre = trim($dat1[4]);
            $sql_dir = "SELECT direccion from direcciones_medicos where cod_med=$codigo_medico";
            $resp_dir = mysql_query($sql_dir);
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
                $indice_direccion++;
            }
            $direccion = $dat1[5];
            $nombre_medico = "$pat $mat $nombre";
            $espe = $dat1[6]." (".$dat1[7].")";
            $cat = $dat1[7];
            $telefono_medico = $dat1[9];
            $celular_medico = $dat1[10];
            $catCloseUp = $dat1[11];
                     
            $sql_numero_visita = "SELECT distinct(o.id), rmd.cod_contacto, rmd.orden_visita from orden_dias o, rutero_maestro_cab_aprobado rmc, 
			rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd where rm.cod_contacto=rmd.cod_contacto and 
			rmc.cod_rutero=rm.cod_rutero and rmc.cod_visitador=rm.cod_visitador and rm.cod_visitador=rmd.cod_visitador and rmc.estado_aprobado=1 and rmc.codigo_linea='$linea_funcionario' and 
			rm.cod_visitador='$codigo_funcionario' and rmd.cod_med='$codigo_medico' and o.dia_contacto=rm.dia_contacto and 
			rmc.codigo_ciclo='$rpt_ciclo' and rmc.codigo_gestion='$global_gestion' and rmc.cod_visitador = $codigo_funcionario  order by o.id";
            
			//echo $sql_numero_visita."<br />";
			$cadGrupo="";
			
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
			
			$numFilasPersonalizada=1;
			
			
                $sql_insert_reporte_cab = "INSERT into boletas_visita_cabXXX (id_boleta, id_visitador_hermes, id_visitador_zeus, territorio, visitador, medico, linea, 
				gestion, id_ciclo, id_gestion, fecha, id_medico, nro_boleta, direccion1, telefono, celular, especialidad, supervisor, estado, cod_contacto) 
				values($indice_reporte,$codigo_funcionario,$codZeus,'$nombre_territorio', '$nombre_funcionario',
				'$nombre_medico','$nombre_linea','$rpt_gestion','$rpt_ciclo','$global_gestion','$fecha_planificada',$codigo_medico,
				'$indice_boleta de $cantidad_total_boletas','$dir_rep1','$telefono_medico', '$celular_medico','$espe','$nombre_supervisor','0','$cod_contacto')";
				
				echo "$sql_insert_reporte_cab<br>";
                
				$resp_insert_reporte_cab = mysql_query($sql_insert_reporte_cab);
						
				$sql_parrilla_detalle="select p.cod_mm, 
					(select concat(m.descripcion,' ',m.presentacion) from muestras_medicas m where m.codigo=p.cod_mm) ,
					p.cantidad_mm, 
					(select ma.descripcion_material from material_apoyo ma where ma.codigo_material=p.cod_ma), p.cantidad_ma
					from parrilla_personalizada p
					where p.cod_gestion=$global_gestion and p.cod_ciclo=$rpt_ciclo and p.cod_med=$codigo_medico and 
					p.numero_visita=$numero_visita and p.cod_linea='$linea_funcionario' ORDER BY orden_visita";
				$resp_parrilla_detalle = mysql_query($sql_parrilla_detalle);
				
				$numFilasDetalle=mysql_num_rows($resp_parrilla_detalle);
				
				if($numero_visita==2 && $numFilasDetalle==0){
					$sql_parrilla_detalle="select p.cod_mm, 
					(select concat(m.descripcion,' ',m.presentacion) from muestras_medicas m where m.codigo=p.cod_mm) ,
					p.cantidad_mm, 
					(select ma.descripcion_material from material_apoyo ma where ma.codigo_material=p.cod_ma), p.cantidad_ma
					from parrilla_personalizada p
					where p.cod_gestion=$global_gestion and p.cod_ciclo=$rpt_ciclo and p.cod_med=$codigo_medico and 
					p.numero_visita=3 and p.cod_linea='$linea_funcionario' ORDER BY orden_visita";
				
				}
				//echo $sql_parrilla_detalle;
				$resp_parrilla_detalle = mysql_query($sql_parrilla_detalle);
				
				$filas_parrilla = mysql_num_rows($resp_parrilla_detalle);
				$orden_promocional = 1;
				while ($dat_parrilla_detalle = mysql_fetch_array($resp_parrilla_detalle)) {
					$ultima_poc = '';
					$codMuestra = $dat_parrilla_detalle[0];
					$muestra = $dat_parrilla_detalle[1];
					$cant_muestra = $dat_parrilla_detalle[2];
					$material = $dat_parrilla_detalle[3];
					$cant_material = $dat_parrilla_detalle[4];
					
					if ($material == " - ") {
						$material = " ";
					}
					if ($cant_material == 0) {
						$cant_material = " ";
					}
					$insert_reporte_det = "INSERT into boletas_visita_detalleXXX (id_boleta, orden, muestra, cantidad_muestra, material, 
					cantidad_material, tipo,codigo_muestra) values ($indice_reporte,$orden_promocional,'$muestra','$cant_muestra','$material','$cant_material',1,'$codMuestra')";
					echo $insert_reporte_det."<br>";
					$resp_reporte_det = mysql_query($insert_reporte_det);
					$orden_promocional++;			
				}
			
				//HACEMOS LOS GRUPOS ESPECIALES
				$sqlPGE="select pd.codigo_muestra, 
				(select concat(m.descripcion,' ',m.presentacion) from muestras_medicas m where m.codigo=pd.codigo_muestra),
				pd.cantidad_muestra, pd.codigo_material, 
				(select ma.descripcion_material from material_apoyo ma where ma.codigo_material=pd.codigo_material), 
				pd.cantidad_material
				from grupos_especiales g, grupos_especiales_detalle gd, parrilla_especial p, parrilla_detalle_especial pd
				where g.id=gd.id and g.ciclo='$rpt_ciclo' and g.gestion='$global_gestion' and gd.cod_med='$codigo_medico' 
				and gd.cod_visitador='$codigo_funcionario' and p.codigo_parrilla_especial=pd.codigo_parrilla_especial 
				and p.cod_ciclo=g.ciclo and p.codigo_gestion=g.gestion and p.codigo_grupo_especial=g.codigo_grupo_especial and 
				p.numero_visita='$numero_visita' order by p.codigo_grupo_especial, pd.prioridad;";
				
				$respPGE=mysql_query($sqlPGE);
				while($datPGE=mysql_fetch_array($respPGE)){
					$codMuestra=$datPGE[0];
					$muestra=$datPGE[1];
					$cant_muestra=$datPGE[2];
					$material=$datPGE[4];
					$cant_material=$datPGE[5];
					if ($cant_material == 0) {
						$cant_material = " ";
					}
					$insert_reporte_det = "INSERT into boletas_visita_detalleXXX values($indice_reporte,$orden_promocional,'$muestra','$cant_muestra','$material','$cant_material',2,'$codMuestra')";
					//echo "GRUPOSESPECIALES $insert_reporte_det<br>";
					$resp_reporte_det = mysql_query($insert_reporte_det);
					$orden_promocional++;			
				}
				
				/*//HACEMOS LOS BANCOS
				if($numero_visita==2){
					$sqlBancos="select bmv.codigo_muestra, 
					(select concat(m.descripcion,' ', m.presentacion) from muestras_medicas m where m.codigo=bmv.codigo_muestra),
					bmv.cantidad  from banco_muestras bm, banco_muestras_detalle bmd, banco_muestra_cantidad_visitador bmv
					where bm.id=bmd.id and bmd.id=bmv.id_for and bm.id=bmv.id_for and 
					bm.estado=1 and bm.cod_med='$codigo_medico' and bmv.cod_visitador='$codigo_funcionario' 
					and bmv.cantidad>0 and bm.cod_med=bmv.cod_medico and 
					bmd.cod_muestra=bmv.codigo_muestra group by bmv.cod_visitador, bmv.codigo_muestra order by 2";
					$respBancos=mysql_query($sqlBancos);
					while($datBancos=mysql_fetch_array($respBancos)){
						$codMuestra=$datBancos[0];
						$muestra=$datBancos[1];
						$cant_muestra=$datBancos[2];
						$material="Sin Material Apoyo";
						$cant_material=0;
						$insert_reporte_det = "INSERT into boletas_visita_detalleXXX values($indice_reporte,$orden_promocional,'$muestra','$cant_muestra','$material','',3,'$codMuestra')";
						//echo "BANCOS $insert_reporte_det<br>";
						$resp_reporte_det = mysql_query($insert_reporte_det);
						$orden_promocional++;						
					}
				}*/				
            $indice_boleta++;
            $indice_reporte++;
        }
    }
}
//verificar que maximo hayan 15 items.
	$sqlDelMax="update boletas_visita_detalleXXX set material='--', cantidad_material='0' where material='Sin Material Apoyo'";
	$respDelMax=mysql_query($sqlDelMax);

	$sqlDelMax="delete from boletas_visita_detalleXXX where orden_promocional>15";
	$respDelMax=mysql_query($sqlDelMax);

	echo "Hora Fin: " . date("H:i:s");

	echo "*********************FINNNNNNNNNNNNN***************";
?>