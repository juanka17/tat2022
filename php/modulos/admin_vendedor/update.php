<?php

require_once('../../conf/conex.php');

header("Content-Type: application/json; charset=UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//extract($_REQUEST);
$postdata = file_get_contents("php://input");
$requestData = json_decode($postdata);

try {
    $conexion = new Conexion();
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->exec("set names utf8");
    $conexion->beginTransaction();

    $sql_afiliados = " UPDATE afiliados  SET
            NOMBRE = '$requestData->nombre',
            COD_FORMAS = '$requestData->cod_formas',
            ID_ALMACEN= '$requestData->id_almacen',
            ID_ESTATUS = '$requestData->id_estatus',
            ID_CLASIFICACION = '$requestData->id_clasificacion',
            ID_CATEGORIA = '$requestData->id_categoria'
            WHERE ID = '$requestData->id'";


    $conexion->prepare($sql_afiliados)->execute();

 
    $sql_almance_afiliados = "UPDATE afiliado_almacen SET  ID_ALMACEN = '$requestData->id_almacen'
        WHERE id_afiliado = '$requestData->id' and ID_ALMACEN = '$requestData->id_almacen_old'  ;";
    
    $conexion->prepare($sql_almance_afiliados)->execute();

    $mensaje = [];
    $mensaje['request'] = $_REQUEST;
    $mensaje['success'] = true;
    $mensaje['msj'] = "se consulto registros";
    $mensaje['sql_afiliados'] = $sql_afiliados;
    $mensaje['afiliado_almacen'] = $sql_almance_afiliados;

    echo json_encode($mensaje);

    $conexion->commit();
} catch (PDOException $e) {
    echo 'ERROR: PDOException' . $e->getMessage();
    $conexion->rollBack();
} catch (Exception $e) {
    echo 'ERROR: Exception' . $e->getMessage();
    $conexion->rollBack();
} catch (Throwable $e) {
    echo 'ERROR: Throwable' . $e->getMessage();
    $conexion->rollBack();
}
