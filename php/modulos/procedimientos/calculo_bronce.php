<?php

require_once('../../conf/conex.php');

header("Content-Type: application/json; charset=UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

extract($_REQUEST);

try {
    $conexion = new Conexion();
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->exec("set names utf8");


    $sql = "
        ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute();
    // Especificamos el fetch mode antes de llamar a fetch()
    if (!$stmt) {
        echo 'Error al ejecutar la consulta ';
    } else {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $mensaje = [];
        $mensaje['sql'] = $sql;
        $mensaje['request'] = $_REQUEST;
        $mensaje['success'] = true;
        $mensaje['msj'] = "se consulto registros";
        $mensaje['data'] = $results;
        echo json_encode($mensaje);
    }
} catch (PDOException $e) {
    echo 'ERROR: PDOException' . $e->getMessage();
} catch (Exception $e) {
    echo 'ERROR: Exception' . $e->getMessage();
} catch (Throwable $e) {
    echo 'ERROR: Throwable' . $e->getMessage();
}