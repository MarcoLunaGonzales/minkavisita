<?php
echo "<script language='Javascript'>
	function enviar_form(f)
	{	f.submit();
	}
	function validar(f)
	{
		var i,j,cantidad_material,nombre_tipo, nota_entrega, codigo_registro;
		variables=new Array(f.length-1);
		vector_material=new Array(100);
		vector_nrolote=new Array(100);
		vector_fechavenci=new Array(100);
		vector_cantidades=new Array(100);
		vector_tipomaterial=new Array(100);
		var indice,fecha, tipo_ingreso, observaciones;
		fecha=f.fecha.value;
		codigo_registro=f.codigo_registro.value;
		tipo_ingreso=f.tipo_ingreso.value;
		nota_entrega=f.nota_entrega.value;
		observaciones=f.observaciones.value;
		cantidad_material=f.cantidad_material.value;
		if(f.fecha.value=='')
		{	alert('El campo Fecha esta vacio.');
			f.fecha.focus();
			return(false);
		}
		if(f.nota_entrega.value=='')
		{	alert('El campo Nota de Entrega esta vacio.');
			f.nota_entrega.focus();
			return(false);
		}
		for(i=6;i<=f.length-2;i++)
		{
			variables[i]=f.elements[i].value;
			if(f.elements[i].name.indexOf('fecha')==-1)
			{	if(f.elements[i].value=='')
				{	alert('Algun elemento no tiene valor');
					return(false);
				}		
			}
		}
		indice=0;
		for(j=0;j<=f.length-1;j++)
		{
			if(f.elements[j].name.indexOf('materiales')!=-1)	
			{	vector_material[indice]=f.elements[j].value;
				indice++;	
			}
		}
		indice=0;
		for(j=0;j<=f.length-1;j++)
		{
			if(f.elements[j].name.indexOf('nrolote')!=-1)	
			{	vector_nrolote[indice]=f.elements[j].value;
				indice++;	
			}
		}
		indice=0;
		for(j=0;j<=f.length-1;j++)
		{
			if(f.elements[j].name.indexOf('fecha_vencimiento')!=-1)	
			{	vector_fechavenci[indice]=f.elements[j].value;
				indice++;	
			}
		}
		indice=0;
		for(j=0;j<=f.length-1;j++)
		{
			if(f.elements[j].name.indexOf('cantidad_unitaria')!=-1)	
			{	vector_cantidades[indice]=f.elements[j].value;
				indice++;	
			}
		}
		var buscado,cant_buscado;
		for(k=0;k<=indice;k++)
		{	buscado=vector_material[k];
			cant_buscado=0;
			for(m=0;m<=indice;m++)
			{	if(buscado==vector_material[m])
				{	cant_buscado=cant_buscado+1;
				}
			}
			if(cant_buscado>1)
			{	alert('Los Materiales no pueden repetirse.');
				return(false);
			}
		}
		location.href='guarda_editaringresomateriales.php?vector_material='+vector_material+'&vector_nrolote='+vector_nrolote+'&vector_fechavenci='+vector_fechavenci+'&vector_cantidades='+vector_cantidades+'&fecha='+fecha+'&tipo_ingreso='+tipo_ingreso+'&nota_entrega='+nota_entrega+'&observaciones='+observaciones+'&cantidad_material='+cantidad_material+'&vector_tipomaterial='+vector_tipomaterial+'&codigo_registro='+codigo_registro+'';
	}
	</script>";
require("conexion.inc");
if($global_tipoalmacen==1)
{	require("estilos_almacenes_central.inc");
}
else
{	require("estilos_almacenes.inc");
}
if($fecha=="")
{	$fecha=date("d/m/Y");
}
echo "<form action='' method='get'>";
//sacamos los valores iniciales para la edicion
if($valor_inicial==1)
{	$sql_val_inicial="select cod_ingreso_almacen, cod_almacen, cod_tipoingreso, fecha, hora_ingreso, observaciones, 
				grupo_ingreso, cod_salida_almacen, nota_entrega, nro_correlativo from ingreso_almacenes
				where cod_ingreso_almacen='$codigo_registro'";
	$resp_val_inicial=mysql_query($sql_val_inicial);
	$dat_val_inicial=mysql_fetch_array($resp_val_inicial);
	$tipo_ingreso=$dat_val_inicial[2];
	$observaciones=$dat_val_inicial[5];
	$nota_entrega=$dat_val_inicial[8];
	$nro_correlativo=$dat_val_inicial[9];

	$sql_val_inicial_detalle="select cod_material, nro_lote, fecha_vencimiento, cantidad_unitaria, cantidad_restante
	from ingreso_detalle_almacenes where cod_ingreso_almacen='$codigo_registro'";
	$resp_val_inicial_detalle=mysql_query($sql_val_inicial_detalle);
	$indice_inicial=1;
	$cantidad_material=mysql_num_rows($resp_val_inicial_detalle);
	while($dat_inicial=mysql_fetch_array($resp_val_inicial_detalle))
	{	$var_material_ini="materiales$indice_inicial";
		$var_nrolote_ini="nrolote$indice_inicial";
		$var_fechavenci_ini="fecha_vencimiento$indice_inicial";
		$var_cant_unit_ini="cantidad_unitaria$indice_inicial";
		$$var_material_ini=$dat_inicial[0];
		$$var_nrolote_ini=$dat_inicial[1];
		$fecha_ingsinformato=$dat_inicial[2];
		$$var_fechavenci_ini="$fecha_ingsinformato[8]$fecha_ingsinformato[9]/$fecha_ingsinformato[5]$fecha_ingsinformato[6]/$fecha_ingsinformato[0]$fecha_ingsinformato[1]$fecha_ingsinformato[2]$fecha_ingsinformato[3]";
		if($$var_fechavenci_ini=="00/00/0000")
		{	$$var_fechavenci_ini="";
		}
		$$var_cant_unit_ini=$dat_inicial[3];
		$indice_inicial++;
	}
}
//fin sacar valores iniciales
echo "<input type='hidden' name='nro_correlativo' value='$nro_correlativo'>";
echo "<input type='hidden' name='codigo_registro' value='$codigo_registro'>";
echo "<table border='0' class='textotit' align='center'><tr><th>Registrar Ingreso de Material de Apoyo</th></tr></table><br>";
echo "<table border='1' class='texto' cellspacing='0' align='center' width='90%'>";
echo "<tr><th>N�mero de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Nota de Entrega</th></tr>";
echo "<tr>";
echo "<td align='center'>$nro_correlativo</td>";
echo "<td align='center'>";
	echo"<INPUT type='text' disabled='true' class='texto' value='$fecha' id='fecha' size='10' name='fecha'>";
	echo" <IMG id='imagenFecha' src='imagenes/fecha.bmp'>";
	/*echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
	echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
	echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
	echo" input_element_id='fecha'";
	echo" click_element_id='imagenFecha'></DLCALENDAR></td>";*/
$sql1="select cod_tipoingreso, nombre_tipoingreso from tipos_ingreso where tipo_almacen='$global_tipoalmacen' order by nombre_tipoingreso";
$resp1=mysql_query($sql1);
echo "<td align='center'><select name='tipo_ingreso' class='texto'>";
while($dat1=mysql_fetch_array($resp1))
{	$cod_tipoingreso=$dat1[0];
	$nombre_tipoingreso=$dat1[1];
	if($cod_tipoingreso==$tipo_ingreso)
	{	echo "<option value='$cod_tipoingreso' selected>$nombre_tipoingreso</option>";
	}
	else
	{	echo "<option value='$cod_tipoingreso'>$nombre_tipoingreso</option>";
	}
}
echo "</select></td>";
echo "<td align='center'><input type='text' class='texto' name='nota_entrega' value='$nota_entrega'></td></tr>";
echo "<tr><th colspan='4'>Observaciones</th></tr>";
echo "<tr><td colspan='4' align='center'><input type='text' class='texto' name='observaciones' value='$observaciones' size='100'></td></tr>";
echo "</table><br>";
echo "<table border=1 class='texto' width='100%' align='center'>";
echo "<tr><th colspan='4'>Cantidad de Materiales a ingresar:  <select name='cantidad_material' OnChange='enviar_form(this.form)' class='texto'>";
for($i=0;$i<=15;$i++)
{	if($i==$cantidad_material)
	{	echo "<option value='$i' selected>$i</option>";
	}
	else
	{	echo "<option value='$i'>$i</option>";
	}
}
echo "</select><th></tr>";
echo "<tr><th width='5%'>&nbsp;</th><th width='45%'>Material</th><th width='25%'>Fecha Vencimiento</th><th width='25%'>Cantidad Unitaria</th></tr>";
for($indice_detalle=1;$indice_detalle<=$cantidad_material;$indice_detalle++)
{	echo "<tr><td align='center'>$indice_detalle</td>";
	//obtenemos los valores de las variables creadas en tiempo de ejecucion
	$var_material="materiales$indice_detalle";
	$valor_material=$$var_material;
	echo "<td align='center'><select name='materiales$indice_detalle' class='textomini'>";
	echo "<option></option>";
	$sql_materiales="select * from material_apoyo where estado='Activo' and codigo_material<>0 order by descripcion_material";
	$resp_materiales=mysql_query($sql_materiales);
	while($dat_materiales=mysql_fetch_array($resp_materiales))
	{	$cod_material=$dat_materiales[0];
		$nombre_material=$dat_materiales[1];
		if($cod_material==$valor_material)
		{	echo "<option value='$cod_material' selected>$nombre_material</option>";
		}
		else
		{	echo "<option value='$cod_material'>$nombre_material</option>";
		}
	}
	echo "</select></td>";
	
	//$var_nrolote="nrolote$indice_detalle";
	//$valor_nrolote=$$var_nrolote;
	//echo "<td align='center'><input type='text' name='nrolote$indice_detalle' value='$valor_nrolote' class='texto' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td>";
	
	$var_fecha_vencimiento="fecha_vencimiento$indice_detalle";
	$valor_fecha_vencimiento=$$var_fecha_vencimiento;
	echo "<td align='center'>";
	echo" <INPUT type='text' class='texto' value='$valor_fecha_vencimiento' id='fecha_vencimiento$indice_detalle' size='10' name='fecha_vencimiento$indice_detalle'>";
	echo" <IMG id='imagenFecha$indice_detalle' src='imagenes/fecha.bmp'>";
	echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
	echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
	echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
	echo" input_element_id='fecha_vencimiento$indice_detalle'";
	echo" click_element_id='imagenFecha$indice_detalle'></DLCALENDAR></td>";
	$var_cant_unit="cantidad_unitaria$indice_detalle";
	$valor_cant_unit=$$var_cant_unit;
	echo "<td align='center'><input type='text' name='cantidad_unitaria$indice_detalle' value='$valor_cant_unit' class='texto' onKeypress='if (event.keyCode < 48 || event.keyCode > 57 ) event.returnValue = false;'></td>";
	echo "</tr>";
}
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_ingresomateriales.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
echo "<center><input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
echo "</div></body>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";
?>