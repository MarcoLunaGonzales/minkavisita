<?php

/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita M&eacute;dica
 * * @copyright 2005
 */
echo "<script language='JavaScript'>
			function envia_select(menu,form){
			form.submit();
			return(true);}

			function prueba(f,num_el,parametro){
			var i,j;
			var n=num_el;
			var extra='muestra';
			var extra1='cantidad';
			var extra2='apoyo';
			var extra3='cantidad_a';
			var extra4='obs';
			var extra5='prioridad';
			var extra6='extra';
			var valor,valor1,valor2,valor3,valor4,valor5,valor6;
			var tipo,j_cod_muestra,j_cantidad,j_num_visita,j_agencia,j_linea_visita,j_categoria;
			vector_muestras=new Array(n-1);
			vector_cantidad=new Array(n-1);
			vector_apoyo=new Array(n-1);
			vector_cantidad_a=new Array(n-1);
			vector_obs=new Array(n-1);
			vector_categoria=new Array(20);
			vector_priori=new Array(n-1);
			vector_extra=new Array(n-1);
			var j_ciclo, j_especialidad, j_categoria,j_obs,j_muestras_extras,j_prioridad,j_extra;
			j_ciclo=f.elements[0].value;
			var codigo_parrilla;
			codigo_parrilla=f.codigo_parrilla.value;
			j_especialidad=f.h_espe.value;
			j_num_visita=f.num_visita.value;
			j_agencia=f.agencia.value;
			j_categoria=f.h_categoria.value;
			j_linea_visita=f.linea_visita.value;
			j_muestras_extras=f.h_numero_extras.value;
			i=1;
			//esta parte valida el formulario para mandarlo
			var comp='obs';
			var comp1='linea_visita';
			for(j=0;j<=f.length-1;j++)
				{
                                                    
					if(f.elements[j].name.indexOf(comp1)==-1)
					{	if(f.elements[j].name.indexOf(comp)==-1)
						{	if(f.elements[j].value=='')
							{
								alert('Algun elemento no tiene valor.');
								return(false);
							}
						}
					}
				}
			//hasta aca validar formulario
			//valida las prioridades
			var el='prioridad';
			var suma_real=0;
			var valor_v,numero,suma=0;
			var num_productos;
			num_productos=(f.h_numero_muestras.value*1)+(f.h_numero_extras.value*1);
			for(var j=1;j<=num_productos;j++)
			{
				valor_v=el+j;
				suma_real=suma_real+j;
				for(i=0;i<=f.length-2;i++)
				{	if(f.elements[i].name==valor_v)
					{	numero=(f.elements[i].value)*1;
						suma=suma+numero;
					}
				}
			}
			if(suma!=suma_real)
			{	alert('El Orden Promocional no puede repetirse ni debe ser cero.');
				alert('suma real'+suma_real+'suma'+suma+j);
				return(false);
			}
			//fin prioridades
			//validar sin material de apoyo producto no puede ser cero
			var cant_prod_comp,material_comp;
			cant_prod_comp='cantidad';
			material_comp='apoyo';
			for(j=0;j<=f.length-1;j++)
			{
				if(f.elements[j].name.indexOf(material_comp)!=-1)
				{
					/*if(f.elements[j].value==0 && f.elements[j-1].value==0)
					{	alert('Si no se lleva Material de Apoyo, la cantidad del Producto no puede ser cero.');
						return(false);
					}*/
					if(f.elements[j].value==0 && f.elements[j+1].value!=0)
					{	alert('Si no se lleva Material de Apoyo, su cantidad debe ser cero.');
						return(false);
					}
					/*if(f.elements[j-1].value==0 && f.elements[j+1].value==0)
					{	alert('Si se lleva Producto y Material de Apoyo alguna de las cantidades debe ser distinta de cero.');
						return(false);
					}*/
				}
			}
			//fin validar sin material de apoyo
			for(j=0;j<=f.length-1;j++)
				{
					if(f.elements[j].type=='checkbox')
					{
						if(f.elements[j].checked==true)
						{	vector_categoria[i]=f.elements[j].value;
							i=i+1;
						}

					}
					//alert(f.elements[j].checked);
				}
			for(i=1;i<=n;i++)
			{
				valor=extra+i;
				valor1=extra1+i;
				valor2=extra2+i;
				valor3=extra3+i;
				valor4=extra4+i;
				valor5=extra5+i;
				valor6=extra6+i;
				for(j=0;j<=f.length-1;j++)
				{
					if(f.elements[j].name==valor)
					{
						tipo=f.elements[j];
						j_cod_muestra=tipo.options[tipo.selectedIndex].value;
						vector_muestras[i-1]=j_cod_muestra;
					}
					if(f.elements[j].name==valor1)
					{
						tipo=f.elements[j];
						j_cantidad=tipo.options[tipo.selectedIndex].value;
						vector_cantidad[i-1]=j_cantidad;
					}
					if(f.elements[j].name==valor2)
					{
						tipo=f.elements[j];
						j_cod_muestra=tipo.options[tipo.selectedIndex].value;
						vector_apoyo[i-1]=j_cod_muestra;
					}
					if(f.elements[j].name==valor3)
					{
						tipo=f.elements[j];
						j_cantidad=tipo.options[tipo.selectedIndex].value;
						vector_cantidad_a[i-1]=j_cantidad;
					}
					if(f.elements[j].name==valor4)
					{
						tipo=f.elements[j];
						j_obs=tipo.value;
						vector_obs[i-1]=j_obs;
					}
					if(f.elements[j].name==valor5)
					{
						tipo=f.elements[j];
						j_prioridad=tipo.value;
						vector_priori[i-1]=j_prioridad;
					}
					if(f.elements[j].name==valor6)
					{
						tipo=f.elements[j];
						j_extra=tipo.value;
						vector_extra[i-1]=j_extra;
					}
				}

			}
			//inicio validar muestras repetidas
			var buscado,cant_buscado;
			for(k=0;k<=(n-1);k++)
			{	buscado=vector_muestras[k];
				cant_buscado=0;
				for(m=0;m<=(n-1);m++)
				{	if(buscado==vector_muestras[m])
					{	cant_buscado=cant_buscado+1;
					}
				}
				if(cant_buscado>1)
				{	alert('Los Productos a llevar no pueden repetirse');
					return(false);
				}
			}
			//fin validar muestras repetidas
			if(parametro==0)
			{	location.href='guarda_modi_parrilla.php?j_categoria='+j_categoria+'&codigo_parrilla='+codigo_parrilla+'&j_agencia='+j_agencia+'&j_ciclo='+j_ciclo+'&j_especialidad='+j_especialidad+'&vec_categoria='+vector_categoria+'&vec_muestras='+vector_muestras+'&vec_cant='+vector_cantidad+'&vec_apoyo='+vector_apoyo+'&vec_cant_a='+vector_cantidad_a+'&vector_obs='+vector_obs+'&vector_priori='+vector_priori+'&vector_extra='+vector_extra+'&cantidad='+n+'&j_num_visita='+j_num_visita+'&j_linea_visita='+j_linea_visita+'&j_muestras_extras='+j_muestras_extras+'';
			}
			if(parametro==1)
			{	location.href='guardar_como_parrilla_nueva.php?j_categoria='+j_categoria+'&codigo_parrilla='+codigo_parrilla+'&j_agencia='+j_agencia+'&j_ciclo='+j_ciclo+'&j_especialidad='+j_especialidad+'&vec_categoria='+vector_categoria+'&vec_muestras='+vector_muestras+'&vec_cant='+vector_cantidad+'&vec_apoyo='+vector_apoyo+'&vec_cant_a='+vector_cantidad_a+'&vector_obs='+vector_obs+'&vector_priori='+vector_priori+'&vector_extra='+vector_extra+'&cantidad='+n+'&j_num_visita='+j_num_visita+'&j_linea_visita='+j_linea_visita+'&j_muestras_extras='+j_muestras_extras+'';
			}
			return(true);
			}
			</script>";

require("estilos.inc");
require("conexion.inc");
//para armas los vectores de muestra y apoyo
$p_muestras = "muestra";
$p_cantidad = "cantidad";
$p_apoyo = "apoyo";
$p_cantidad_a = "cantidad_a";
$obs = "obs";
$h_numero_total = $h_numero_muestras + $h_numero_extras;
if ($j_cod_parrilla != "") {
    $codigo_parrilla = $j_cod_parrilla;
    $i = 1;
    //esta parte saca los datos de la parrilla
    $sql_master = "select cod_ciclo, cod_especialidad, categoria_med, numero_visita, agencia, codigo_l_visita,muestras_extra from parrilla where codigo_parrilla=$j_cod_parrilla";
    $resp_master = mysql_query($sql_master);
    $dat_master = mysql_fetch_array($resp_master);
    $h_ciclo = $dat_master[0];
    $h_espe = $dat_master[1];
    $h_categoria = $dat_master[2];
    $num_visita = $dat_master[3];
    $agencia = $dat_master[4];
    $linea_visita = $dat_master[5];
    $h_numero_extras = $dat_master[6];
    //echo $muestras_extra;
    //aca armamos las muestras
    $sql_muestras = "select codigo_muestra,cantidad_muestra,codigo_material,cantidad_material,prioridad,observaciones,extra
					from parrilla_detalle where codigo_parrilla=$j_cod_parrilla order by prioridad, extra";
    $resp_muestras = mysql_query($sql_muestras);

    $h_numero_total = mysql_num_rows($resp_muestras);
    $h_numero_muestras = $h_numero_total - $h_numero_extras;

    while ($dat_muestras = mysql_fetch_array($resp_muestras)) {
        $p_m = "$p_muestras$i";
        $$p_m = $dat_muestras[0];
        $p_c = "$p_cantidad$i";
        $$p_c = $dat_muestras[1];
        $p_a = "$p_apoyo$i";
        $$p_a = $dat_muestras[2];
        $p_c_a = "$p_cantidad_a$i";
        $$p_c_a = $dat_muestras[3];
        $p_o = "$obs$i";
        $priori = "prioridad$i";
        $$priori = $dat_muestras[4];
        $$p_o = $dat_muestras[5];
        $extra = "extra$i";
        $$extra = $dat_muestras[6];
        $i++;
    }
}

$ciclo_trabajo = $_GET['ciclo_trabajo'];
$gestion_trabajo = $_GET['gestion_trabajo'];

$gestion_trabajo=1011;



$sql_ges = mysql_query("select codigo_gestion from gestiones where estado = 'Activo'");
$gestion = mysql_result($sql_ges,0,0);
echo "<center><table border='0' class='textotit'>";
echo "<tr><td>Editar Parrilla M&eacute;dica</td></tr>";
echo "</table></center>";
echo "<form action=''>";

echo "<input type='hidden' name='ciclo_trabajo' value='$ciclo_trabajo'>";
echo "<input type='hidden' name='gestion_trabajo' value='$gestion_trabajo'>";

echo "<center>";
echo "<table border='1' cellspacing='0' class='texto'>";
echo "<tr><th>Ciclo</th><th>L&iacute;nea de Visita</th><th>Especialidad</th><th>Categor&iacute;a</th></tr>";

$sql1 = "select * from ciclos where codigo_linea=1032 and codigo_gestion='$gestion_trabajo' and cod_ciclo='$ciclo_trabajo' and estado in ('Inactivo') order by fecha_ini";
$resp1 = mysql_query($sql1);
echo "<tr><td><select name='h_ciclo' class='texto' onChange='envia_select(this,this.form)'  disabled>";
echo "<option value=''></option>";

while ($dat1 = mysql_fetch_array($resp1)) {
    $p_ciclo = $dat1[0];
    $p_estado = $dat1[3];
	$desc_estado=$p_estado;
    if ($h_ciclo == $p_ciclo) {
        echo "<option value=$p_ciclo selected>$p_ciclo $desc_estado</option>";
    } else {
        echo "<option value=$p_ciclo>$p_ciclo $desc_estado</option>";
    }
	$p_inicio=$dat1[1];
	$p_fin=$dat1[2];
}
echo "</select>&nbsp;";
echo "<input type='text' name='h_inicio' value='$p_inicio' disabled='true' size='10' class='texto'>&nbsp;
<input type='text' name='h_fin' value='$p_fin' disabled='true' size='10' class='texto'></td>";
echo "<td align='center'><select name='linea_visita' class='texto' onChange='envia_select(this,this.form)'>";
$sql_linea_vis = "select codigo_l_visita, nombre_l_visita from lineas_visita where codigo_linea='$global_linea' order by nombre_l_visita";
$resp_linea_vis = mysql_query($sql_linea_vis);
echo "<option value=''></option>";
while ($dat_linea_vis = mysql_fetch_array($resp_linea_vis)) {
    $cod_linea_vis = $dat_linea_vis[0];
    $nombre_linea_vis = $dat_linea_vis[1];
    if ($linea_visita == $cod_linea_vis) {
        echo "<option value='$cod_linea_vis' selected>$nombre_linea_vis</option>";
    } else {
        echo "<option value='$cod_linea_vis'>$nombre_linea_vis</option>";
    }
}
echo "</select></td>";
//verificamos para que linea de visita sacamos las especialidades y en caso vacio obtenemos todas
if ($linea_visita != 0) {
    $sql2 = "select e.cod_especialidad,e.desc_especialidad from especialidades e,lineas_visita_especialidad l
		where e.cod_especialidad=l.cod_especialidad and l.codigo_l_visita='$linea_visita' order by e.desc_especialidad";
} else {
    $sql2 = "select * from especialidades order by desc_especialidad";
}
$resp2 = mysql_query($sql2);
echo "<td><select name='h_espe' class='texto' onChange='envia_select(this,this.form)'>";
while ($dat2 = mysql_fetch_array($resp2)) {
    $p_cod_espe = $dat2[0];
    $p_desc_espe = $dat2[1];
    if ($p_cod_espe == $h_espe) {
        echo "<option value='$p_cod_espe' selected>$p_desc_espe</option>";
    } else {
        echo "<option value='$p_cod_espe'>$p_desc_espe</option>";
    }
}
echo "</td>";
echo "<td align='center'>";
echo "<select name='h_categoria' class='texto'>";
$sql_categoria = mysql_query("select categoria_med from categorias_medicos order by categoria_med");
while ($dat_categoria = mysql_fetch_array($sql_categoria)) {
    $cat = $dat_categoria[0];
    if ($h_categoria == $cat) {
        echo "<option value='$cat' selected>$cat</option>";
    } else {
        echo "<option value='$cat'>$cat</option>";
    }
}
echo "</select>";
echo "</td>";
echo "</tr>";
/* desde aqui registramos el numero de muestras que se debe dejar y lo enviamos
  a este mismo modulo para crear dinamicamente las muestras */
//vemos cuantos productos existen ya sea por linea o por especialidad
if ($linea_visita != 0) {
    $sql5 = "select m.codigo, m.descripcion,m.presentacion from muestras_medicas m order by m.descripcion";
} else {
    $sql5 = "select m.codigo, m.descripcion,m.presentacion from muestras_medicas m order by m.descripcion";
}
$resp5 = mysql_query($sql5);
$numero_de_muestras = mysql_num_rows($resp5);
/* if($numero_de_muestras>10)
  {	$numero_de_muestras=10;
  } */
//fin contar cuantos productos hay
echo "<tr><th>N&uacute;mero Productos</th><th>N&uacute;mero Visita</th><th>Agencia</th><th>Productos Extra</th></tr>";
echo "<tr><td align='center'><select class='texto' name='h_numero_muestras' onChange='envia_select(this,this.form)'>";
for ($i = 1; $i <= $numero_de_muestras; $i++) {
    if ($h_numero_muestras == $i) {
        echo "<option value='$i' selected>$i</option>";
    } else {
        echo "<option value='$i'>$i</option>";
    }
}
echo "</select>";
echo "</td>";
echo "<td align='center'><select name='num_visita' class='texto'>";
for ($k = 1; $k <= 8; $k++) {
    if ($num_visita == $k) {
        echo "<option value='$k' selected>Visita $k</option>";
    } else {
        echo "<option value='$k'>Visita $k</option>";
    }
}
echo "</select>";
echo "</td>";
echo "<td align='center'><select name='agencia' class='texto'>";
$sql_agencia = mysql_query("select cod_ciudad,descripcion from ciudades order by descripcion asc");
echo "<option value='0'>Todas las Agencias</option>";
while ($dat_agencia = mysql_fetch_array($sql_agencia)) {
    $cod_ciudad = $dat_agencia[0];
    $descripcion = $dat_agencia[1];
    if ($agencia == $cod_ciudad) {
        echo "<option value='$cod_ciudad' selected>$descripcion</option>";
    } else {
        echo "<option value='$cod_ciudad'>$descripcion</option>";
    }
}
echo "</select></td>";
echo "<td align='center'><select class='texto' name='h_numero_extras' onChange='envia_select(this,this.form)'>";
for ($i = 0; $i <= 10; $i++) {
    if ($h_numero_extras == $i) {
        echo "<option value='$i' selected>$i</option>";
    } else {
        echo "<option value='$i'>$i</option>";
    }
}

echo "</td>";
echo "</table></center>";
echo "<br>";
/* desde aqui construimos dinamicamente el numero de muestras con la
 * variable $h_numero_muestras */
echo "<center><table border='0' class='textomini'><tr><td>Leyenda:</td><td>Producto Extra</td><td bgcolor='#66CCFF' width='30%'></td></tr></table></center><br>";

echo "<center><table span class='texto' border='1' cellspacing='0'  width='90%'>";
echo "<tr><th>Orden Promocional</th><th>Producto</th><th> </th><th>Cantidad</th><th> </th><th>Material de Apoyo</th><th> </th><th>Cantidad</th><th>Observaciones</th></tr>";
for ($i = 1; $i <= $h_numero_total; $i++) {
    $p_m = "$p_muestras$i";
    $val_extra = "extra$i";
    $val_priori = "prioridad$i";
    $valor_prioridad = $$val_priori;
    $prioridad = "prioridad";
    if ($i <= $h_numero_muestras) {
        echo "<input type='hidden' name='extra$i' value='0'>";
        echo "<tr>";
        /* if($linea_visita!=0)
          {	$sql5="select l.codigo_mm,m.descripcion, m.presentacion from muestras_medicas m, lineas_visita_detalle l
          where m.codigo=l.codigo_mm and l.codigo_l_visita=$linea_visita order by m.descripcion";
          }
          else
          {	$sql5="select p.codigo_mm,m.descripcion, m.presentacion from muestras_medicas m, producto_especialidad p
          where m.codigo=p.codigo_mm and p.codigo_linea='$global_linea' and p.cod_especialidad='$h_espe' order by m.descripcion";
          } */
        $sql5 = "select m.codigo, m.descripcion,m.presentacion from muestras_medicas m order by m.descripcion";
    } else {
        echo "<input type='hidden' name='extra$i' value='1'>";
        echo "<tr bgcolor='#66CCFF'>";
        $sql5 = "select codigo, descripcion,presentacion from muestras_medicas order by descripcion";
    }
    $resp5 = mysql_query($sql5);
    echo "<td align='center'><input type='text' name='$prioridad$i' value='$valor_prioridad' class='texto' size='1' maxlength='2' onKeypress='if (event.keyCode < 48 || event.keyCode > 57 ) event.returnValue = false;'></td>";
    echo "<td align='center'><select name='$p_m' class='textomini'>";
    echo "<option value=''></option>";
    while ($dat5 = mysql_fetch_array($resp5)) {
        $p_codigo_muestra = $dat5[0];
        $p_desc_muestra = $dat5[1];
        $p_pres_muestra = $dat5[2];
        if ($p_codigo_muestra == $$p_m) {
            echo "<option value='$p_codigo_muestra' selected>$p_desc_muestra $p_pres_muestra</option>";
        } else {
            echo "<option value='$p_codigo_muestra'>$p_desc_muestra $p_pres_muestra</option>";
        }
    }
    echo "</select></td><td>  </td>";

    $p_c = "$p_cantidad$i";
    echo "<td><select class='textomini' name='$p_c'>";
    for ($j = 0; $j <= 10; $j++) {
        if ($j == $$p_c) {
            echo "<option value=$j selected>$j</option>";
        } else {
            echo "<option value=$j>$j</option>";
        }
    }
    echo "</select></td>";
    echo "<td></td>";
    $p_a = "$p_apoyo$i";
    echo "<td><select name='$p_a' class='textomini'>";
    $sql6 = "select * from material_apoyo where estado='Activo' order by descripcion_material";
    $resp6 = mysql_query($sql6);
    while ($dat6 = mysql_fetch_array($resp6)) {
        $p_codigo_apoyo = $dat6[0];
        $p_desc_apoyo = $dat6[1];
        if ($p_codigo_apoyo == $$p_a) {
            echo "<option value='$p_codigo_apoyo' selected>$p_desc_apoyo</option>";
        } else {
            echo "<option value='$p_codigo_apoyo'>$p_desc_apoyo</option>";
        }
    }
    echo "</select></td><td>  </td>";

    $p_c_a = "$p_cantidad_a$i";
    echo "<td><select class='textomini' name='$p_c_a'>";
    for ($j = 0; $j <= 10; $j++) {
        if ($j == $$p_c_a) {
            echo "<option value=$j selected>$j</option>";
        } else {
            echo "<option value=$j>$j</option>";
        }
    }
    echo "</select></td>";
    $p_o = "$obs$i";
    $valor = $$p_o;
    echo "<td><input type='text' class='texto' name='$p_o' value='$valor' size='30'></td>";
    echo "</tr>";
}
echo "</table><br>";
echo "<input type='button' value='Modificar' class='boton' onClick='prueba(this.form,$h_numero_total,0)'>";
echo "<input type='button' value='Guardar como nueva' class='boton' onClick='prueba(this.form,$h_numero_total,1)'>";
//echo "<input type='hidden' name='j_cod_parrilla' value=$codigo_parrilla>";
//echo "<input type='hidden' name='ciclo_trabajo' value=$ciclo_trabajo>";
//echo "<input type='hidden' name='gestion_trabajo' value=$gestion_trabajo>";
echo "<input type='hidden' name='h_ciclo' value=$h_ciclo>";
echo "<input type='hidden' name='codigo_parrilla' value=$codigo_parrilla>";

echo "<center><table border='0' class='texto' width='90%'><tr><th>Nota: El Orden Promocional puede ser cambiado de acuerdo a necesidad, especialmente en el caso de Productos Extras.</th></tr></table></center><br>";
?>