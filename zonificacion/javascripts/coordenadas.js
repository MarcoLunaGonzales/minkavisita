function coordenadas(ciudad, coordendas){
    $.getJSON("ajax/coordenadas.php",{
        "ciudad": ciudad,
        "coordenadas": coordendas
    },response);
    return false;
}

function response(datos){
//    if(datos.mensaje == true){
//        alert("Datos eliminados satisfactoriamente")
//        window.location.reload(true);
//    }else{
//        alert("Ocurrio un error en el proceso, Intentelo de nuevo")
//        window.location.reload(true);
//    }
alert(datos.coordenadas)
}
