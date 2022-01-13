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


    $sql = "SELECT
        almacenes.id AS id_drogueria,
        (SELECT nombre FROM afiliados WHERE id_almacen = almacenes.id AND id_clasificacion = 4 LIMIT 1) supervisor,
        (SELECT id FROM afiliados WHERE id_almacen = almacenes.id AND id_clasificacion = 4 LIMIT 1) id_supervisor,
        almacenes.nombre AS almacen,
        almacenes.id AS id_almacen,
        ventas.id_periodo,
        periodo.nombre AS periodo,
        temporada.id AS id_temporada,
        temporada.nombre AS temporada,
        sum(ventas.unidades) unidades,
        sum(ventas.valor) as venta,
        ventas.fecha,
        cuotas_supervisor_lider.cuota,
        ROUND((sum(ventas.valor)/cuotas_supervisor_lider.cuota)*100,1)cumplimiento,
        if((sum(ventas.valor)/cuotas_supervisor_lider.cuota)*100 >= 100,1,0) validacion,
        if(redenciones.id IS null,0,1)redencion
    FROM
        afiliados
    INNER JOIN ventas ON ventas.id_vendedor = afiliados.ID
    INNER JOIN productos ON ventas.id_producto = productos.id
    INNER JOIN afiliado_almacen ON afiliado_almacen.id_afiliado = afiliados.id
    INNER JOIN almacenes ON afiliado_almacen.ID_ALMACEN = almacenes.id
    INNER JOIN periodo ON ventas.id_periodo = periodo.id
    INNER JOIN temporada ON temporada.id = periodo.id_temporada
    INNER JOIN cuotas_supervisor_lider  on cuotas_supervisor_lider.id_almacen = almacenes.id
    left JOIN redenciones ON redenciones.id_almacen = almacenes.id 
    AND redenciones.temporada = temporada.id 
    AND redenciones.id_afiliado = (SELECT id FROM afiliados WHERE id_almacen = almacenes.id AND id_clasificacion = 4 LIMIT 1)
    AND redenciones.id_premio = 2468
WHERE
        productos.sku IN (
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
        )
        AND almacenes.id = '$id_almacen' AND temporada.id = 19
    GROUP BY temporada.id
    ORDER BY temporada.id desc
        "
    ;

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