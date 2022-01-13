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
    
    $sql = " INSERT INTO afiliados (
    NOMBRE,
	CEDULA,
	COD_FORMAS,
	ID_ALMACEN,	
	ID_ESTATUS,
	ID_CLASIFICACION,
	ID_ROL,
        ID_CATEGORIA,
	ID_GERENTE,
    ID_ASISTENTE
    )VALUES(
    '$requestData->nombre',
    0,
    '$requestData->cod_formas',
    '$requestData->id_almacen',    
    '$requestData->id_estatus',
    '$requestData->id_clasificacion',
    '$requestData->id_categoria',
    1,
    0,
    ''
    );
    ";

    $conexion->prepare($sql)->execute();


     $sql_almance_afiliados = "INSERT INTO afiliado_almacen (
    id_afiliado,
	id_almacen	
    ) select 
        max(ID) ,
        '$requestData->id_almacen'       
    from afiliados;";

    $conexion->prepare($sql_almance_afiliados)->execute();

    

    $mensaje = [];
    /*
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $mensaje['data'] = $results;
    */
    $mensaje['sql'] = $sql;
    $mensaje['request'] = $_REQUEST;
    $mensaje['success'] = true;
    $mensaje['msj'] = "se consulto registros";
        
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
