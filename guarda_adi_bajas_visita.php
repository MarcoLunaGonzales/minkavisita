<?php
require("conexion.inc");
require("estilos_regional.inc");
$sql_ciclo="select cod_ciclo, codigo_gestion from ciclos where estado='Activo'";
$resp_ciclo=mysql_query($sql_ciclo);
$dat_ciclo=mysql_fetch_array($resp_ciclo);
$ciclo=$dat_ciclo[0];
$gestion=$dat_ciclo[1];
$var="linea";
$vis="visitador";
$sql_codigo="select max(codigo_baja) from baja_dias";
$resp_codigo=mysql_query($sql_codigo);
$dat_codigo=mysql_fetch_array($resp_codigo);
$codigo=$dat_codigo[0]+1;

if($turno=="Am/Pm"){
	$sql_inserta="insert into baja_dias values('$codigo','$ciclo','$gestion','$global_agencia','$dia_contacto','Am','$motivo')";	
	$resp_inserta=mysql_query($sql_inserta);
	$codigoNew=$codigo+1;
	$sql_inserta="insert into baja_dias values('$codigoNew','$ciclo','$gestion','$global_agencia','$dia_contacto','Pm','$motivo')";	
	$resp_inserta=mysql_query($sql_inserta);
		
}else{
	$sql_inserta="insert into baja_dias values('$codigo','$ciclo','$gestion','$global_agencia','$dia_contacto','$turno','$motivo')";
	$resp_inserta=mysql_query($sql_inserta);
}


if($resp_inserta==1)
{	for($i=1;$i<$numero_lineas;$i++)
	{	$var_linea="$var$i";
		$linea=$$var_linea;
		if($linea!="")
		{
			for($j=0;$j<=50;$j++)
			{	$var_vis="$vis$j";
				$visitador=$$var_vis;
				if($visitador!="")
				{	$sql_veri="select * from funcionarios_lineas where codigo_funcionario='$visitador' and codigo_linea='$linea'";
					$resp_veri=mysql_query($sql_veri);
					$filas_veri=mysql_num_rows($resp_veri);
					if($filas_veri!=0)
					{	$sql_adi_visitador="insert into baja_dias_detalle_visitador values('$codigo','$linea','$visitador')";
						$resp_adi_visitador=mysql_query($sql_adi_visitador);
					
						if($turno=="Am/Pm"){
							$sql_adi_visitador="insert into baja_dias_detalle_visitador values('$codigoNew','$linea','$visitador')";
							$resp_adi_visitador=mysql_query($sql_adi_visitador);
						}
						
					}
				}
			}
			$sql_adiciona="insert into baja_dias_detalle values('$codigo','$linea')";
			$resp_adiciona=mysql_query($sql_adiciona);
			
			if($turno=="Am/Pm"){
				$sql_adiciona="insert into baja_dias_detalle values('$codigoNew','$linea')";
				$resp_adiciona=mysql_query($sql_adiciona);
			}

		}
	}
	echo "<script language='Javascript'>
		alert('Los datos fueron adicionados correctamente.');
		location.href='navegador_bajas_visitas.php';
		</script>";
}
else
{	echo "<script language='Javascript'>
		alert('No se pudieron insertar los datos.');
		location.href='navegador_bajas_visitas.php';
		</script>";
}
?>