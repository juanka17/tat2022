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

    if ($search == 'all') {
        $sql = "SELECT
        pro.id_afiliados,
        afi.nombre,
        SUM(ve.valor) AS venta,
        cuo.cuota,
        ROUND((sum(ve.valor)/cuo.cuota)*100,1)as cumplimiento,
        if((sum(ve.valor)/cuo.cuota)*100 >= 100,1,0) as validacion
        FROM afiliados AS af
        INNER JOIN ventas AS ve ON ve.id_vendedor = af.ID
        INNER JOIN almacenes AS al ON al.id = af.ID_ALMACEN
        INNER JOIN productos AS pr ON pr.id = ve.id_producto
        INNER JOIN periodo ON ve.id_periodo = periodo.id
        INNER JOIN temporada ON temporada.id = periodo.id_temporada
        INNER JOIN promotores_almacenes AS pro ON pro.id_almacen = al.id
        INNER JOIN afiliados AS afi ON afi.ID = pro.id_afiliados
        INNER JOIN promotores_cuota AS cuo ON cuo.id_afiliados = pro.id_afiliados
        WHERE
         pr.sku IN (
         7702132000868,
         7702132009588,
         7702132009571,
         7702132008048,
         7702132008611,
         7702132001803,
         7702132008598,
         7702132008444,
         7702132008581,
         7702132008482
        ) AND temporada.id >= 19
        GROUP BY pro.id_afiliados " ;
    }
    
    if ($search == 'detalle') {
        $sql="SELECT
            pro.id_afiliados,
            afi.nombre,
            al.id,
            al.nombre AS almacen, SUM(ve.valor) AS venta,
            cuo.cuota
            FROM afiliados AS af
            INNER JOIN ventas AS ve ON ve.id_vendedor = af.ID
            INNER JOIN almacenes AS al ON al.id = af.ID_ALMACEN
            INNER JOIN productos AS pr ON pr.id = ve.id_producto
            INNER JOIN periodo ON ve.id_periodo = periodo.id
            INNER JOIN temporada ON temporada.id = periodo.id_temporada
            INNER JOIN promotores_almacenes AS pro ON pro.id_almacen = al.id
            INNER JOIN afiliados AS afi ON afi.ID = pro.id_afiliados
            INNER JOIN promotores_cuota AS cuo ON cuo.id_afiliados = pro.id_afiliados
            WHERE
             pr.sku IN (
             7702132000868,
             7702132009588,
             7702132009571,
             7702132008048,
             7702132008611,
             7702132001803,
             7702132008598,
             7702132008444,
             7702132008581,
             7702132008482
            ) AND temporada.id >= 19
            AND pro.id_afiliados = $id_afiliado
            GROUP BY af.ID_ALMACEN";
    }

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