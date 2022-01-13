<?php

include_once('clsDDBBOperations.php');
include_once('consultas.php');

$id_almacen = $_POST["id_almacen"];

$almacen = "select * from almacenes where id = " . $id_almacen;
$results_almacen =  clsDDBBOperations::ExecuteSelectNoParams($almacen);

$nombre = $results_almacen[0]["nombre"];

$fichero = $_FILES["archivo"];
if (isset($_FILES)) {

    if ($_FILES['archivo']['type'] == 'application/pdf') {
        $insert = array();
        $insert["id_almacen"] = $id_almacen;
        $insert["nombre"] = $nombre;
        $insert["tipo_acta"] = 1;
        $insert["firma"] = "terminos_condiciones/" . $id_almacen . "_" . $nombre . ".pdf";

        $insertResult = clsDDBBOperations::ExecuteInsert($insert, "habeas_data");
        // Cómo subir el archivo


        // Cargando el fichero en la carpeta "subidas"
        move_uploaded_file($fichero["tmp_name"], "../terminos_condiciones/" . $id_almacen . "_" . $nombre . ".pdf");

        alert("Archivo Subido Correctamente");
    }

    if ($_FILES['archivo']['type'] != 'application/pdf') {
        alert("Esto NO es un archivo pdf");
    }
} else {

    alert("Debe Seleccionar un archivo");
}



function alert($msg)
{
    echo "<script type='text/javascript'>alert('$msg');javascript: history.go(-1);</script>";
}
