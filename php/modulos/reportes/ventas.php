
<?php

require_once('../../conf/conex.php');

header("Content-Type: application/json; charset=UTF-8");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

extract($_REQUEST);

foreach( $_REQUEST as $key => $value )
{
    $_REQUEST[$key] = strip_tags($value);
}

try {
    $conexion = new Conexion();
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->exec("set names utf8");

    if ($search == 'periodos_ventas') {

        $sql = "SELECT
                per.id,
                per.nombre 
                FROM ventas ven
                INNER JOIN periodo per ON per.id = ven.id_periodo
                where per.id >= 14
                GROUP BY id ";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            ////$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'distribuidoras') {

        $sql = "SELECT * FROM almacenes where estado = 1 order by 2 ";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'distribuidoras_activas') {

        $sql = "
            SELECT
                id,
                nombre
            FROM 
                almacenes
            WHERE 
                estado = 1  
            ORDER BY
                nombre";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'marcas') {

        $sql = "
            SELECT  
                id,
                nombre 
            FROM 
                marcas
            ORDER BY 
                nombre ";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'categoria_marcas') {

        $sql = "
            SELECT 
                mar.id,
                mar.nombre
            FROM
                productos pro
                INNER JOIN marcas mar ON mar.id = pro.id_marca
            WHERE 
                ($id_categoria_productos_marca = 0 || id_categoria = $id_categoria_productos_marca)
                AND ($id_portafolio = 0 || id_portafolio = $id_portafolio)					 
            GROUP BY 1
            ORDER BY 
            mar.nombre";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'sub_marcas') {

        $sql = "
            SELECT 
                mar.id,
                mar.nombre
            FROM 
                productos pro
                INNER JOIN sub_marca mar ON mar.id = pro.id_sub_marca
            GROUP BY
                1
            ORDER BY 
                mar.nombre";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'sub_marcas_filtros') {

        $sql = "
            SELECT 
                mar.id,
                mar.nombre
            FROM productos pro
                INNER JOIN sub_marca mar ON mar.id = pro.id_sub_marca
            WHERE 
                ($id_portafolio = 0 || id_portafolio = $id_portafolio)	
                AND ($id_categoria_productos_marca = 0 || id_categoria = $id_categoria_productos_marca)	
                AND ($id_marca = 0 || id_marca  = $id_marca)					 
            GROUP BY
                1
            ORDER BY mar.nombre";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'representantes') {

        $sql = "
            SELECT
                DISTINCT 
                afi.id,
                afi.nombre
            FROM
                almacenes alm
                INNER JOIN afiliados afi ON alm.id_visitador =  afi.ID
            ORDER BY 
                2 ";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'representantes_territorio') {

        $sql = "
            SELECT
                DISTINCT
                afi.id,
                afi.nombre
            FROM almacenes alm
                INNER JOIN afiliados afi ON alm.id_visitador =  afi.ID
                WHERE alm.id_territorio = $id_territorio
            ORDER BY
                2";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'distribuidora_madre') {

        $sql ="
            SELECT
                id,
                nombre
            FROM 
                distribuidora_madre
            ORDER BY 
                nombre";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'distribuidora_madre_representante') {

        $sql = "
            SELECT distinct
                dis.id,
                dis.nombre
            FROM
                almacenes alm
                INNER JOIN distribuidora_madre dis ON dis.id = alm.id_madre 
            WHERE 
                ($id_territorio = 0 || id_territorio = $id_territorio)
                AND ($id_representante = 0 || id_visitador = $id_representante)   
            ORDER BY dis.nombre
            ";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'almacen_representantes') {

        $sql = "
            SELECT
                id,
                nombre 
            FROM 
                almacenes alm 
            WHERE 
                ($id_territorio = 0 || id_territorio = $id_territorio) 
                AND ($id_visitador = 0 || id_visitador = $id_visitador)
                AND ($id_madre = 0 || id_madre = $id_madre)
             ORDER BY nombre";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'categoria_productos') {

        $sql = "
            SELECT
                cat.id id_categoria_producto,
                cat.nombre categoria_producto
            FROM  
                ventas ven
                INNER JOIN afiliado_almacen afia ON afia.id_afiliado = ven.id_vendedor
                INNER JOIN almacenes alm ON alm.id = afia.id_almacen
                INNER JOIN productos pro ON pro.id = ven.id_producto
                INNER JOIN categoria_producto cat ON cat.id = pro.id_categoria
            GROUP  BY  
                cat.id
            ORDER BY 
                cat.nombre";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'categoria_productos_portafolio') {

        $sql = "
            SELECT
                cat.id id_categoria_producto,
                cat.nombre categoria_producto
            FROM 
                ventas ven
                INNER JOIN afiliado_almacen afia ON afia.id_afiliado = ven.id_vendedor
                INNER JOIN almacenes alm ON alm.id = afia.id_almacen
                INNER JOIN productos pro ON pro.id = ven.id_producto
                INNER JOIN categoria_producto cat ON cat.id = pro.id_categoria
            WHERE 
                pro.id_portafolio = $id_portafolio
            GROUP BY  
                cat.id
            ORDER BY 
                cat.nombre";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'productos_total') {

        $sql = "
            SELECT 
                id,
                nombre
            FROM 
                productos
            ORDER BY 
                nombre";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'productos') {

        $sql = "
            SELECT
                id,
                nombre
            FROM 
                productos
            WHERE
                ($id_portafolio = 0 || id_portafolio = $id_portafolio) 
                AND ($id_categoria = 0 || id_categoria = $id_categoria)
                AND ($id_marca = 0 || id_marca = $id_marca)
                AND ($id_sub_marca = 0 || id_sub_marca = $id_sub_marca)
            ORDER BY
                nombre
                ";
        
        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'temporadas') {

        $sql = "SELECT * FROM temporada order by 1";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }

    if ($search == 'distribuidoras_vendedor_perfecto') {

        $sql = "SELECT * FROM almacenes where estado = 1 order by 2";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'usuarios_promotores') {

        $sql = "SELECT * FROM afiliados where id_clasificacion = 9 order by 2";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'supervisor_lider') {

        $sql = "SELECT
        almacenes.id AS id_drogueria,
        (SELECT nombre FROM afiliados WHERE id_almacen = almacenes.id AND id_clasificacion = 4 LIMIT 1) supervisor,
        (SELECT id FROM afiliados WHERE id_almacen = almacenes.id AND id_clasificacion = 4 LIMIT 1) id_supervisor,
        almacenes.id_visitador,
        afiliado_visitador.nombre ejecutivo,
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
        if((sum(ventas.valor)/cuotas_supervisor_lider.cuota)*100 >= 100,'SI','NO') validacion,
        if(redenciones.id IS NULL,'NO','Si') solicitado
    FROM
        afiliados
    INNER JOIN ventas ON ventas.id_vendedor = afiliados.ID
    INNER JOIN productos ON ventas.id_producto = productos.id
    INNER JOIN afiliado_almacen ON afiliado_almacen.id_afiliado = afiliados.id
    INNER JOIN almacenes ON afiliado_almacen.ID_ALMACEN = almacenes.id
    INNER JOIN periodo ON ventas.id_periodo = periodo.id
    INNER JOIN temporada ON temporada.id = periodo.id_temporada
    INNER JOIN cuotas_supervisor_lider  on cuotas_supervisor_lider.id_almacen = almacenes.id AND temporada.id = cuotas_supervisor_lider.id_temporada 
    INNER JOIN afiliados afiliado_visitador ON afiliado_visitador.ID = almacenes.id_visitador
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
    AND temporada.id >= 19 and almacenes.id = '$id_almacen'
    GROUP BY temporada.id,almacenes.id
    ORDER BY almacenes.id";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'promotores') {

        $sql = "SELECT
            pro.id_afiliados,
            afi.nombre,
            al.id,
            al.nombre AS almacen,
            SUM(ve.valor) AS venta,
            cuo.cuota,
            ROUND((sum(ve.valor)/cuo.cuota)*100,1)as cumplimiento
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
            AND pro.id_afiliados = $id_promotor
            GROUP BY af.ID_ALMACEN";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }

    if ($search == 'vendedor_perfecto') {

        $sql = "call sp_grafica_vendedor_perfecto(" . $id_almacen_vendedor_perfecto . "," . $id_ciclo . ")";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }

    if ($search == 'entregas') {

        $sql = "SELECT
            ope.nombre,
            red.id_operacion,
            count(red.id_operacion)estado
        FROM seguimiento_redencion red
            inner join operaciones_redencion ope on ope.id = red.id_operacion
            where id_redencion in (select id from redenciones where id_almacen = $id_almacen AND temporada = $temporada)
            group by 
            id_operacion;";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'cupos') {
        $objDatos = json_decode(file_get_contents("php://input"));
        $sql = $objDatos->where_validacion_id_almacen;

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'ventas') {

        $sql = "SELECT
                    alm.id,
                    alm.nombre,
                    alm.id_visitador id_representante,
                    afi.nombre representante,
                    tem.id id_temporada,
                    tem.nombre temporada,
                    SUM(est.venta)venta,
                    SUM(est.impactos)impactos
                FROM estado_cuenta est
                    INNER JOIN afiliado_almacen afia ON afia.id_afiliado = est.id_afiliado
                    INNER JOIN almacenes alm ON alm.id = afia.id_almacen
                    INNER JOIN afiliados afi ON afi.ID = alm.id_visitador
                    INNER JOIN periodo per ON per.id = est.id_periodo
                    INNER JOIN temporada tem ON tem.id = per.id_temporada
                    WHERE tem.id in($id_temporada_ventas) AND alm.id = $id_almacen
                    GROUP BY alm.id,tem.id";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'ventas_distribuidora') {
        $objDatos = json_decode(file_get_contents("php://input"));
        $sql = "SELECT
                    alm.id,
                    alm.nombre distribuidora,
                    ter.nombre territorio,
                    alm.id_visitador id_representante,
                    afi.nombre representante,
                    tem.id id_temporada,
                    tem.nombre temporada,
                    per.id id_periodo,
                    per.nombre periodo,
                    SUM(est.venta)venta,
                    SUM(est.impactos)impactos
                FROM estado_cuenta est
                    INNER JOIN afiliado_almacen afia ON afia.id_afiliado = est.id_afiliado
                    INNER JOIN almacenes alm ON alm.id = afia.id_almacen
                    INNER JOIN afiliados afi ON afi.ID = alm.id_visitador
                    INNER JOIN periodo per ON per.id = est.id_periodo
                    INNER JOIN temporada tem ON tem.id = per.id_temporada
                    INNER JOIN territorios ter ON ter.id = alm.id_territorio
                    WHERE $objDatos->where_validacion
                    GROUP BY $objDatos->group_by_validacion";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'ventas_temporada') {

        $sql = "SELECT
                    alm.id,
                    alm.nombre,
                    alm.id_visitador id_representante,
                    afi.nombre representante,
                    tem.id id_temporada,
                    tem.nombre temporada,
                    SUM(est.venta)venta,
                    SUM(est.impactos)impactos
                FROM estado_cuenta est
                    INNER JOIN afiliado_almacen afia ON afia.id_afiliado = est.id_afiliado
                    INNER JOIN almacenes alm ON alm.id = afia.id_almacen
                    INNER JOIN afiliados afi ON afi.ID = alm.id_visitador
                    INNER JOIN periodo per ON per.id = est.id_periodo
                    INNER JOIN temporada tem ON tem.id = per.id_temporada
                    WHERE tem.id in ($id_temporada_ventas)
                    GROUP BY alm.id,tem.id";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'ventasSku') {
        $objDatos = json_decode(file_get_contents("php://input"));
        $sql = "
            
                    $objDatos->where_validacion 
            group by 
		$objDatos->group_by_validacion
                ORDER BY 1;";

        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
    if ($search == 'crecimiento') {
        $objDatos = json_decode(file_get_contents("php://input"));
        $sql = "
            
                    $objDatos->where_validacion
            group by 
		per.id
                ORDER BY 4,3";
        $sql = str_replace("and per.id >=1", "and per.id >=1", $sql);
        $sql = "SELECT periodo,
              anio,
              mes,
              ventas_descuento,
              if(mes = @lastMes, ((ventas_descuento / @lastValue) - 1) * 100, 0) crecimiento,
              @lastMes := mes vmes,
              @lastValue := ventas_descuento vventas_descuento
         FROM (  " . $sql . ") xcrecimiento
     ORDER BY mes, anio";
        $stmt = $conexion->prepare($sql);

        $stmt->execute();
        $stmt->execute();
        // Especificamos el fetch mode antes de llamar a fetch()
        if (!$stmt) {
            echo 'Error al ejecutar la consulta ';
        } else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $mensaje = [];
            //$mensaje['sql'] = $sql;
            $mensaje['request'] = $_REQUEST;
            $mensaje['success'] = true;
            $mensaje['msj'] = "se consulto registros";
            $mensaje['data'] = $results;
            echo json_encode($mensaje);
        }
    }
} catch (PDOException $e) {
    echo 'ERROR: DDBB';
    //echo 'ERROR: PDOException' . $e->getMessage();
} catch (Exception $e) {
    echo 'ERROR: System';
    //echo 'ERROR: Exception' . $e->getMessage();
} catch (Throwable $e) {
    echo 'ERROR: Custom';
    //echo 'ERROR: Throwable' . $e->getMessage();
}