<?php

class Consultas
{

    public static $consulta_afiliados = '
        SELECT 
        afi.ID, 
        afi.NOMBRE, 
        afi.CEDULA, 
        afi.DIRECCION, 
        afi.TELEFONO, 
        afi.CELULAR, 
        afi.NACIMIENTO, 
        afi.EMAIL,  
        gen.ID ID_GENERO,
        gen.NOMBRE GENERO,
        eci.ID ID_ESTADO_CIVIL,
        eci.NOMBRE ESTADO_CIVIL,
        ciu.ID ID_CIUDAD,
        ciu.NOMBRE CIUDAD,
        dep.ID ID_DEPARTAMENTO,
        dep.NOMBRE DEPARTAMENTO,
        afi.FECHA_INSCRIPCION, 
        afi.ULTIMA_ACTUALIZACION,
        alm.id ID_ALMACEN,
        alm.nombre ALMACEN,
        cla.id ID_CLASIFICACION,
        cla.nombre CLASIFICACION,
        rol.ID id_rol,
        rol.NOMBRE rol,
        rol.es_administrador,
        est.nombre estatus
    FROM
        afiliados afi
        left join clasificacion cla on cla.id = afi.id_clasificacion and cla.id <> 2
        left join roles rol on rol.id = afi.id_rol
        left join almacen alm on alm.id = afi.id_almacen
        left join genero gen on gen.id = afi.id_genero
        left join estado_civil eci on eci.id = afi.id_estado_civil
        left join ciudad ciu on ciu.id = afi.id_ciudad
        left join departamento dep on dep.id = ciu.id_departamento
        left join estatus est on est.id = afi.ID_ESTATUS
        ';
    public static $consulta_login = '
        SELECT 
            afi.id,
            afi.nombre,
            afi.cedula,
            afi.acepto_terminos,
            afi.email,
            afi.clave,
            rol.es_administrador,
            cla.id id_clasificacion,
            cla.nombre clasificacion,
            afi.ID_ROL,
            rol.NOMBRE rol,
            (select count(distinct aux.id) from almacenes aux where aux.nombre like "%incauca%" and aux.id_visitador = afi.id) incauca
        FROM
            afiliados afi
            inner join roles rol on rol.id = afi.ID_ROL 
            inner join clasificacion cla on cla.id = afi.id_clasificacion
        ';
    public static $consulta_cambio_clave = '
        SELECT 
            afi.clave, 
            afi.ACEPTO_TERMINOS
        FROM afiliados afi 
            WHERE afi.id = %i';
    public static $consulta_premios = '
        select
            pre.id,
            pre.nombre,
            pre.descripcion,
            pre.marca,
            pre.puntos,
            pre.catalogo,
            pre.categoria_afiliado,
            cap.id id_categoria,
            cap.nombre categoria,
            cap.cliente_visualiza,
            (select count(red.id) from redenciones red where red.id_premio = pre.id) * -1 redimidos
        from premios pre
            inner join categoria_premio cap on cap.id = pre.id_categoria
        where  pre.activo = 1 
        ';
    
    public static $consulta_familiares = '
    SELECT
	fam.ID,
        fam.NOMBRE,
        fam.EDAD,
        par.ID ID_PARENTESCO,
        par.NOMBRE PARENTESCO
    FROM 
        familiares_afiliado fam
        inner join parentesco par on par.id = fam.id_parentesco';
    public static $consulta_intereses = "
        SELECT 
            ina.ID, 
            ina.ID_INTERES, 
            inte.NOMBRE INTERES, 
            ina.COMENTARIO 
        FROM 
            intereses_afiliado ina 
            inner join intereses inte on inte.id = ina.id_interes
        ";
    public static $consulta_llamadas_afiliado = "
        SELECT la.ID, la.FECHA, cl.NOMBRE CATEGORIA, usr.NOMBRE REGISTRO, COMENTARIO 
        FROM    
            llamadas_afiliado la 
            inner join categorias_llamada cl on la.ID_SUBCATEGORIA = cl.ID 
            left join afiliados usr on usr.id = la.id_usuario
        ";
    public static $consulta_llamadas_almacen = "
        SELECT la.ID, la.FECHA, cl.NOMBRE CATEGORIA, usr.NOMBRE REGISTRO, COMENTARIO, QUIEN_LLAMA
        FROM    
            llamadas_almacen la 
            inner join categorias_llamada cl on la.ID_SUBCATEGORIA = cl.ID 
            left join afiliados usr on usr.id = la.id_usuario
        ";
    public static $premios_sugeridos = "
        select 
            id_afiliado,
            sum(case when cec.suma = 0 then (ecu.puntos * -1) else ecu.puntos end) puntos_actuales,
            case when actual.id is null then 304 else actual.id end id_actual,
            case when actual.puntos is null then 23 else actual.puntos end actual_puntos,
            case when actual.nombre is null then 'Barra Fitness' else actual.nombre end actual_nombre,
            case when actual.marca is null then 'PROFIT' else actual.marca end actual_marca,
            case when actual.marca is null then 'Premio sugerido' else 'Puedes redimir ahora' end comentario_actual,
            case when cercano.id is null then 229 else cercano.id end id_cercano,
            case when cercano.puntos is null then 76 else cercano.puntos end cercano_puntos,
            case when cercano.nombre is null then 'Toca CD Canta y Aprende Conmigo' else cercano.nombre end cercano_nombre,
            case when cercano.marca is null then 'Fisher Price' else cercano.marca end cercano_marca,
            case when cercano.marca is null then 'Premio sugerido' else 'Sí te esfuerzas un poco' end comentario_cercano,
            case when lejano.id is null then 460 else lejano.id end id_lejano,
            case when lejano.puntos is null then 107 else lejano.puntos end lejano_puntos,
            case when lejano.nombre is null then 'Perfume Boss Woman (roja)' else lejano.nombre end lejano_nombre,
            case when lejano.marca is null then 'HUGO BOSS' else lejano.marca end lejano_marca,
            case when cercano.marca is null then 'Premio sugerido' else 'Alkanza tú meta' end comentario_lejano,
            case when bonos.id is null then 2005 else bonos.id end bono_id,
            case when bonos.puntos is null then 78 else bonos.puntos end bono_puntos,
            case when bonos.nombre is null then 'Frisby kids' else bonos.nombre end bono_nombre,
            case when bonos.marca is null then 'Frisby' else bonos.marca end bono_marca
        from
            estado_cuenta ecu
            inner join concepto_estado_cuenta cec on cec.id = ecu.id_concepto
            left join (
                select pre.ID,pre.PUNTOS,pre.NOMBRE,pre.MARCA
                from premios pre inner join categoria_premio cat on cat.ID = pre.ID_CATEGORIA 
                where cat.CLIENTE_VISUALIZA=1 and activo=1 and puntos<=(_puntos_) and cat.id <> 1
                order by puntos desc limit 1
            ) as actual on 1 = 1
            left join (
                select pre.ID,pre.PUNTOS,pre.NOMBRE,pre.MARCA
                from premios pre inner join categoria_premio cat on cat.ID = pre.ID_CATEGORIA 
                where cat.CLIENTE_VISUALIZA=1 and activo=1 and puntos<=(_puntos_+((10*_puntos_)/100)) and cat.id <> 1
                order by puntos desc limit 1
            ) as cercano on 1 = 1
            left join (
                select pre.ID,pre.PUNTOS,pre.NOMBRE,pre.MARCA
                from premios pre inner join categoria_premio cat on cat.ID = pre.ID_CATEGORIA 
                where cat.CLIENTE_VISUALIZA=1 and activo=1 and puntos<=(_puntos_+((50*_puntos_)/100)) and cat.id <> 1
                order by puntos desc limit 1
            ) as lejano on 1 = 1
            left join (
                SELECT pre.ID,pre.PUNTOS,pre.NOMBRE,pre.MARCA 
                FROM premios pre 
                WHERE pre.id_categoria = 1  and pre.activo = 1 and puntos <= (_puntos_)
                ORDER BY pre.PUNTOS desc LIMIT 1
           ) as bonos on 1 = 1
        where
            id_afiliado = %i
        group by
            id_afiliado
        ";
    public static $consulta_redencion = "
        SELECT  
            id,
            id_afiliado,
            id_premio,
            puntos,
            direccion_envio,
            fecha_redencion,
            fecha_provista_entrega,
            comentarios,
            id_registra,
            afecta_estado_cuenta,
            finalizada
        FROM 
            redenciones
        ";
    public static $obtener_periodo_por_fecha = "SELECT max(id) id_periodo FROM periodo WHERE '_date_' between INICIO and FINAL";
    public static $consulta_redenciones = '
        SELECT  
            red.id folio, 
            red.id_afiliado, 
            pre.id id_premio, 
            pre.nombre premio, 
            pre.marca,
            red.puntos, 
            red.direccion_envio, 
            red.fecha_redencion,
            red.fecha_provista_entrega,
            red.comentarios,
            red.finalizada,
            opr.id id_operacion,
            opr.nombre operacion,
            opr.finaliza_redencion
        FROM 
            redenciones red 
            inner join premios pre on pre.id = red.id_premio
            inner join operaciones_redencion opr on opr.id in (select max(sr.id_operacion) from seguimiento_redencion sr where sr.id_redencion = red.id order by sr.id desc )
        ';
    public static $verificar_archivo_ventas = "SELECT count(id) ventas FROM ventas";
    public static $obtener_ventas_afiliados = "        
        select 
            ven.id, 
            afi.ID id_afiliado, 
            ven.puntos_x_venta, 
            ven.marca,
            ven.articulo,
            ven.recaudo,
            ven.fecha,
            CONCAT('$', FORMAT(ven.venta,0)) venta,
            ven.ArchivoDeCarga archivo
        from 
            ventas ven 
            inner join afiliados afi on afi.CEDULA = ven.cedula_vendedor
        ";
    public static $reporte_ventas_afiliados = "        
        select 
            ven.id, 
            afi.nombre,
            afi.cedula, 
            mar.nombre marca,
            alm.nombre almacen,
            ven.puntos_x_venta, 
            ven.marca,
            ven.articulo,
            ven.recaudo,
            ven.venta,
            ven.ArchivoDeCarga archivo,
            case when afi.nombre is null then 'No procesada' else 'Procesada' end VentaProcesada
         from 
            ventas ven 
            left join afiliados afi on afi.CEDULA = ven.cedula_vendedor
            left join almacen alm on alm.id = afi.ID_ALMACEN
            left join marcas mar on mar.id = afi.ID_MARCA
        ";
    public static $reporte_estado_cuenta = "
        select
            afi.nombre,
            afi.cedula,
            ecu.id_afiliado,
            eje.nombre ejecutivo,
            ciu.nombre ciudad,
            ecu.descripcion,
            per.id id_periodo,
            per.nombre periodo,
            cec.id id_concepto,
            cec.nombre concepto,
            cec.suma,
            ecu.puntos puntos
        from 
            estado_cuenta ecu
            inner join periodo per on per.id = ecu.id_periodo
            inner join concepto_estado_cuenta cec on cec.id = ecu.id_concepto
            inner join afiliados afi on afi.id = ecu.id_afiliado
            left join afiliados eje on eje.id = alm.id_visitador
            left join ciudad ciu on ciu.id = alm.id_ciudad
        order by
            per.id,
            ecu.id
        ";
    public static $seleccionar_id_almacen_nuevo = "select max(id) + 1 id from marcas where id < 1000";
    public static $ventas_sin_vendedor = "
        select
            ven.id,
            ven.tienda,
            ven.recaudo,
            ven.fecha,
            ven.marca,
            ven.articulo,
            ven.precio,
            ven.venta
        from 
            ventas ven
            left join estado_cuenta ecu on ecu.id_venta = ven.id
        where 
            ven.cedula_vendedor = 0
            and ecu.id is null
            and ven.id_afiliado_novedad is null
        ";
    public static $consulta_almacenes = "
        select
            alm.id,
            alm.nombre,
            alm.direccion,
            alm.telefono,
            ciu.id id_ciudad,
            ciu.nombre ciudad,
            dep.ID id_departamento,
            dep.nombre departamento,
            cat.id id_categoria,
            cat.nombre categoria,
            alm.grande_aliado,
            alm.encuestas_periodo,
            alm.encuestas_periodo
        from 
            almacenes alm 
            left join ciudad ciu on ciu.id = alm.id_ciudad
            left join departamento dep on dep.ID = ciu.id_departamento
            left join categoria_almacen cat on cat.id = alm.id_categoria
            left join afiliados vis on vis.id = alm.id_visitador
    ";
    public static $puntos_empleados_almacen = "
        select
            alm.nombre almacen,
            afi.id id_empleado,
            afi.nombre,
            per.nombre periodo,
            ecu.venta,
            ecu.impactos,
            ecu.puntos_venta,
            ecu.puntos_impacto,
            ecu.puntos_venta + ecu.puntos_impacto puntos,
            ecu.puede_redimir
        from
            estado_cuenta ecu
            inner join periodo per on per.id = ecu.id_periodo
            inner join afiliados afi on afi.id = ecu.id_afiliado
            inner join almacenes alm on alm.id = afi.id_almacen
    ";
    public static $redenciones_empleados_almacen = "
        select
            alm.nombre almacen,
            afi.id id_empleado,
            afi.nombre,
            ecu.id id_ecu,
            ecu.puede_redimir,
            ecu.comentario,
            count(distinct case when per.id in (1,2) then 1 else 0 end) temporada,
            count(distinct red.id) entregas_solicitadas
        from
            afiliados afi
            inner join almacenes alm on alm.id = afi.id_almacen
            inner join estado_cuenta ecu on afi.id = ecu.id_afiliado
            inner join periodo per on per.id = ecu.id_periodo
            left join redenciones red on red.id_afiliado = afi.id
        where
            alm.id = _id_almacen_
        group by
            alm.nombre,
            afi.nombre
    ";
    public static $consulta_droguerias = "
    select 
        vis.id id_visitador,
        vis.nombre visitador,
        alm.id id_drogueria,
        alm.nombre drogueria,
        ter.nombre territorio,
        alm.estado,
        alm.direccion,
        alm.telefono,
        ciu.id id_ciudad,
        ciu.nombre ciudad,
        dep.ID id_departamento,
        dep.nombre departamento,
        alm.encuestas_periodo,
        alm.margen
    from
        almacenes alm
        left join afiliados vis on vis.id = alm.id_visitador
        left join ciudad ciu on ciu.id = alm.id_ciudad
        left join departamento dep on dep.id = ciu.id_departamento
        INNER JOIN territorios ter ON ter.id = alm.id_territorio
    ";
    public static $consulta_ventas = "
        select
            alm.id id_distribuidora,
            alm.nombre distribuidora,
            afi.id id_vendedor,
            afi.nombre vendedor,
            eje.id id_ejecutivo,
            eje.nombre ejecutivo,
            ciu.nombre ciudad,
            per.id id_periodo,
            per.nombre periodo,
            tem.nombre temporada,
            ifnull(cat.nombre,'NO REGISTRA') categoria,
            ifnull(pro.nombre,'NO REGISTRA') producto,
            ifnull(pro.sku,'NO REGISTRA') sku,
            pro.id_portafolio,
            pro.portafolio,
            ven.unidades,
            case 
                when des.descuento is null then round((ven.valor) / 1 )
                when des.descuento <= 0 then round((ven.valor) / 1 )
                else round((ven.valor) / des.descuento )
            end venta_total
            from 
            ventas ven
            inner join periodo per on per.id = ven.id_periodo
            inner join temporada tem on tem.id = per.id_temporada
            inner join afiliados afi on afi.id = ven.id_vendedor
            inner join almacenes alm on alm.id = afi.id_almacen
            left join descuentos des on 
                des.id_almacen = afi.id_almacen 
                and (per.id >= des.id_periodo_inicial and per.id <= des.id_periodo_final )
            left join productos pro on pro.id = ven.id_producto
            left join categoria_producto cat on cat.id = pro.id_categoria
            left join afiliados eje on eje.id = alm.id_visitador
            left join ciudad ciu on ciu.id = alm.id_ciudad
    ";
    public static $consulta_estado_cuenta = "
        SELECT 
            est.id_periodo,
            est.id_almacen,
            est.id_vendedor,
            afi.nombre vendedor,
            est.id_categoria,
            SUM(est.venta) venta,
            SUM(est.venta_especial) venta_especial,
            SUM(est.cuota) cuota,
            SUM(est.puntos_venta) puntos_venta,
            SUM(est.puntos_especial) puntos_especial,
            SUM(est.impactos) impactos,
            SUM(est.puntos_impactos) puntos_impactos,
            SUM(est.total_puntos_venta) total_puntos_venta,
            SUM(est.total_puntos) total_puntos
        FROM 
            t_estado_cuenta est
        INNER JOIN afiliados afi ON afi.ID = est.id_vendedor    
    ";
    public static $consulta_estado_cuenta__detallado = "
    SELECT 
        tem.id id_temporada,
        tem.nombre temporada,
        est.id_periodo,
        per.nombre periodo,
        est.id_almacen,
        est.id_vendedor,
        afi.nombre vendedor,
        est.id_categoria,
        cat.nombre categoria,
        SUM(est.cuota) cuota,
        SUM(est.venta) venta,
        SUM(est.venta_especial) venta_especial,            
        SUM(est.cumplimiento) cumplimiento,
        case 
            when est.id_categoria = 1 then 100
            when est.id_categoria = 2 then 75
            when est.id_categoria = 3 then 45
            when est.id_categoria = 4 then 20
            ELSE 0
        END cuota_impactos,
        SUM(est.impactos) impactos,
        case 
            when est.id_categoria = 1 then ROUND((SUM(est.impactos)/100)*100)
            when est.id_categoria = 2 then ROUND((SUM(est.impactos)/75)*100)
            when est.id_categoria = 3 then ROUND((SUM(est.impactos)/45)*100)
            when est.id_categoria = 4 then ROUND((SUM(est.impactos)/20)*100)
            ELSE 0
        END cumplimiento_impactos,            
        SUM(est.puntos_venta) puntos_venta,
        SUM(est.puntos_especial) puntos_especial,
        SUM(est.puntos_impactos) puntos_impactos,
        SUM(est.total_puntos) total_puntos
    FROM 
        t_estado_cuenta est
        INNER JOIN afiliados afi ON afi.ID = est.id_vendedor
        INNER JOIN periodo per ON per.id = est.id_periodo
        INNER JOIN temporada tem ON tem.id = per.id_temporada
        INNER JOIN categorias cat ON cat.id = est.id_categoria   
    ";
    public static $consulta_ganadores_ciclo1 = "
        select
            afi.id id_vendedor,
            afi.nombre vendedor,
            case when id_periodo in (1,2) then 'Diciembre / Enero' else '' end bimestre,
            sum(ecu.puntos_venta) venta,
            sum(ecu.puntos_impacto) impacto,
            sum(ecu.puntos_venta + ecu.puntos_impacto) puntos,
            count(distinct red.id) entregas_solicitadas,
            sum(ecu.novedad) novedad,
            max(ecu.id) id_ecu,
            max(ecu.comentario) comentario
        from 
            estado_cuenta ecu
            inner join afiliados afi on afi.id = ecu.id_afiliado
            left join redenciones red on red.id_afiliado = afi.id
        where
            afi.id_almacen = _id_almacen_
            and ecu.id_periodo in (1,2)
        group by
            ecu.id_afiliado,
            case when id_periodo in (1,2) then 'Diciembre / Enero' else '' end
        order by
            novedad,
            puntos desc
    ";
    public static $consulta_ganadores_ciclo2 = "
        select
            afi.id id_vendedor,
            afi.nombre vendedor,
            8 id_temporada,
            case when id_periodo in (16,17) then 'Abril / Mayo' else '' end bimestre,
            sum(ecu.puntos_venta) venta,
            sum(ecu.puntos_impacto) impacto,
            sum(ecu.puntos_venta + ecu.puntos_impacto) puntos,
            count(distinct red.id) entregas_solicitadas,
            sum(ecu.novedad) novedad,
            max(ecu.id) id_ecu,
            max(ecu.comentario) comentario
        from 
            estado_cuenta ecu
            inner join afiliados afi on afi.id = ecu.id_afiliado
            left join redenciones red on red.id_afiliado = afi.id and red.temporada = 8
        where
            afi.id_almacen = _id_almacen_
            and ecu.id_periodo in (16,17)
        group by
            ecu.id_afiliado,
            case when id_periodo in (16,17) then 'Abril / Mayo' else '' end
        order by
            novedad,
            puntos desc
    ";
    public static $consulta_ganadores_ciclo3 = "
        select
            afi.id id_vendedor,
            afi.nombre vendedor,
            9 id_temporada,
            case when id_periodo in (18,19) then 'Junio / Julio' else '' end bimestre,
            sum(ecu.puntos_venta) venta,
            sum(ecu.puntos_impacto) impacto,
            sum(ecu.puntos_venta + ecu.puntos_impacto) puntos,
            count(distinct red.id) entregas_solicitadas,
            sum(ecu.novedad) novedad,
            max(ecu.id) id_ecu,
            max(ecu.comentario) comentario,
            count(distinct red.id) entregas_solicitadas
        from 
            estado_cuenta ecu
            inner join afiliados afi on afi.id = ecu.id_afiliado
            left join redenciones red on red.id_afiliado = afi.id and red.temporada = 9 and afi.id_clasificacion = 6
        where
            afi.id_almacen = _id_almacen_
            and ecu.id_periodo in (18,19)
        group by
            ecu.id_afiliado,
            case when id_periodo in (18,19) then 'Junio / Julio' else '' end
        order by
            novedad,
            puntos desc
    ";
    public static $consulta_ganadores_ciclo4 = "
        select
            afi.id id_vendedor,
            afi.nombre vendedor,
            10 id_temporada,
            case when id_periodo in (20,21) then 'Agosto / Septiembre' else '' end bimestre,
            sum(ecu.puntos_venta) venta,
            sum(ecu.puntos_impacto) impacto,
            sum(ecu.puntos_venta + ecu.puntos_impacto) puntos,
            count(distinct red.id) entregas_solicitadas,
            sum(case when id_periodo in (20,21) then ecu.novedad else 0 end) novedad,
            max(ecu.id) id_ecu,
            max(ecu.comentario) comentario,
            count(distinct red.id) entregas_solicitadas
        from 
            estado_cuenta ecu
            inner join afiliados afi on afi.id = ecu.id_afiliado
            left join redenciones red on red.id_afiliado = afi.id and red.temporada = 10 and afi.id_clasificacion = 6
        where
            afi.id_almacen = _id_almacen_
            and ecu.id_periodo in (20,21)
        group by
            ecu.id_afiliado,
            case when id_periodo in (20,21) then 'Agosto / Septiembre' else '' end
        order by
            novedad,
            puntos desc
    ";
    public static $consulta_ganadores_ciclo5 = "
        select
            afi.id id_vendedor,
            afi.nombre vendedor,
            11 id_temporada,
            case when id_periodo in (22,23) then 'Octubre / Noviembre' else '' end bimestre,
            sum(ecu.puntos_venta) venta,
            sum(ecu.puntos_impacto) impacto,
            sum(ecu.puntos_venta + ecu.puntos_impacto) puntos,
            count(distinct red.id) entregas_solicitadas,
            sum(case when id_periodo in (22,23) then ecu.novedad else 0 end) novedad,
            max(ecu.id) id_ecu,
            max(ecu.comentario) comentario,
            count(distinct red.id) entregas_solicitadas
        from 
            estado_cuenta ecu
            inner join afiliados afi on afi.id = ecu.id_afiliado
            left join redenciones red on red.id_afiliado = afi.id and red.temporada = 11 and afi.id_clasificacion = 6
        where
            afi.id_almacen = _id_almacen_
            and ecu.id_periodo in (22,23)
        group by
            ecu.id_afiliado,
            case when id_periodo in (22,23) then 'Octubre / Noviembre' else '' end
        order by
            novedad,
            puntos desc
    ";
    public static $consulta_cuotas_supervisor = "
        select
            alm.id id_distribuidora,
            alm.nombre distribuidora,
            tem.id id_temporada,
            tem.nombre temporada,
            cuo.cuota_1, 
            cuo.cuota_2,
            cuo.nombre supervisor,
            GROUP_CONCAT(per.nombre SEPARATOR '|') meses
        from 
            cuotas_supervisor cuo
            inner join temporada tem on tem.id = cuo.id_temporada
            inner join periodo per on per.id_temporada = tem.id
            inner join almacenes alm on alm.id = cuo.id_almacen 
        _condicion_ 
        group by
            cuo.id
    ";
    public static $consulta_vendedores = "
        select 
            afi.id,
            afi.nombre,
            afi.cedula,
            afi.cod_formas,
            afi.id_almacen,
            alm.nombre distribuidora,
            afi.id_estatus,
            est.nombre estatus,
            afi.id_clasificacion,
            rol.nombre rol
        from 
            afiliados afi
            left join almacenes alm on alm.id = afi.id_almacen
            left join estatus est on est.id = afi.id_estatus
            left join roles rol on rol.id = afi.id_rol
        WHERE rol.id != 2
    ";
    public static $consulta_promotores = "
        select 
            afi.id,
            afi.nombre,
            afi.cod_formas,
            afi.id_almacen,
            alm.nombre distribuidora,
            afi.id_estatus,
            est.nombre estatus,
            afi.id_clasificacion,
            cla.nombre clasificacion
        from 
            afiliados afi
            left join almacenes alm on alm.id = afi.id_almacen
            left join estatus est on est.id = afi.id_estatus
            left join clasificacion cla on cla.id = afi.id_clasificacion
        where
            id_clasificacion = 9 
    ";
    public static $consulta_almacenes_promotor = "
        SELECT 
            pro.id_afiliados,
            alm.id,
            alm.nombre
        FROM promotores_almacenes pro
            INNER JOIN almacenes alm ON alm.id = pro.id_almacen
    ";
    public static $estructira_fdv = "
        SELECT 
            alm.id id_distribuidora,
            alm.nombre distribuidora, 
            ter.nombre territorio,
            eje.id id_ejecutivo,
            eje.nombre ejecutivo,
            dis.id id_distribuidora_madre,
            ven.NOMBRE supervisor,
            ciu.nombre ciudad,
            alm.encuestas_periodo,
            afi.id id_vendedor,
            afi.nombre vendedor,
            afi.CEDULA,
            afi.COD_FORMAS,
            rol.nombre rol,
            est.nombre estatus,
            afi.fecha_inscripcion creacion,
            cre.nombre regista,
            ina.NOMBRE inactiva
        FROM 
            afiliados afi
            LEFT JOIN almacenes alm ON alm.id = afi.id_almacen
            LEFT join roles rol on rol.id = afi.id_rol
            LEFT join ciudad ciu on ciu.id = alm.id_ciudad
            LEFT join estatus est on est.id = afi.ID_ESTATUS
            LEFT JOIN territorios ter ON ter.id = alm.id_territorio
            left JOIN distribuidora_madre dis ON dis.id = alm.id_madre
            LEFT JOIN vendedores_supervisor sup ON sup.id_vendedor = afi.id
            LEFT JOIN afiliados cre ON cre.id = afi.ID_REGISTRA
            LEFT JOIN afiliados ina ON ina.ID = afi.ID_INACTIVA
            LEFT JOIN afiliados ven ON ven.ID = sup.id_supervisor
            LEFT join afiliados eje on eje.id = alm.id_visitador
    ";
    public static $consulta_actas = "
        select
            tem.nombre temporada,
            asi.nombre asistente,
            eje.nombre ejecutivo,
            alm.nombre distribuidora,
            seg.comentario,
            max(red.id) folio_mayor,
            count(distinct red.id) entregas
        from
            redenciones red
            inner join seguimiento_redencion seg on seg.id_redencion = red.id and seg.id_operacion = 5
            inner join almacenes alm on alm.id = red.id_almacen
            inner join afiliados eje on eje.id = alm.id_visitador
            inner join afiliados asi on asi.id = eje.ID_ASISTENTE
            inner join temporada tem on tem.id = red.temporada
            WHERE tem.id = _id_temporada_ AND eje.ID = _id_ejecutivo_
        group by
            tem.nombre,
            asi.nombre,
            eje.nombre,
            alm.nombre,
            seg.comentario
    ";
    public static $consulta_acta = "
        select distinct
            tem.nombre temporada,
            asi.nombre asistente,
            eje.nombre ejecutivo,
            alm.nombre distribuidora,
            seg.comentario
        from
            redenciones red
            inner join seguimiento_redencion seg on seg.id_redencion = red.id and seg.id_operacion = 5
            inner join almacenes alm on alm.id = red.id_almacen
            inner join afiliados eje on eje.id = alm.id_visitador
            left join afiliados asi on asi.id = eje.ID_ASISTENTE
            inner join temporada tem on tem.id = red.temporada
    ";
    public static $consulta_temporada = "
        select
            tem.id,
            tem.nombre,
            GROUP_CONCAT(per.nombre SEPARATOR '/') as 'periodos',
            concat(tem.nombre,' / ', GROUP_CONCAT(per.nombre SEPARATOR '/')) as nombre_full
        from 
            temporada tem 
            inner join periodo per on tem.id = per.id_temporada
        group by
            tem.id
        order by
            tem.id
    ";
    public static $consulta_temporada_redenciones = "
        select 
            tem.id,
            tem.nombre,
            ifnull(GROUP_CONCAT(per.nombre SEPARATOR ' / '),'') as 'periodos',
            case
                when GROUP_CONCAT(per.nombre SEPARATOR ' / ') is null then tem.nombre
                else concat(tem.nombre,' (', GROUP_CONCAT(per.nombre SEPARATOR ' / '), ')')
            end as nombre_full
        from 
            temporada tem 
            left join periodo per on tem.id = per.id_temporada
        where
            tem.id in (select temporada from redenciones)
        group by
            tem.id
        order by
            tem.id
    ";
    public static $periodos_ventas_almacen = "
        select
            tem.id,
            tem.nombre temporada,
            group_concat(DISTINCT per.nombre ORDER BY per.id separator ' / ') bimestre
        from 
            temporada tem
            inner join periodo per on tem.id = per.id_temporada
        where
            tem.id in (
                    select 
                            tem.id
                    from 
                            estado_cuenta ecu 
                            inner join periodo per on per.id = ecu.id_periodo
                            inner join temporada tem on tem.id = per.id_temporada
                            inner join afiliado_almacen afia on afia.id_afiliado = ecu.id_afiliado
                    where
                            afia.id_almacen = _id_almacen_
            )
        group by
            tem.id,
            tem.nombre
    ";
    public static $supervisor_lider = "
            SELECT
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
            if(redenciones.id IS null,0,1)redencion,
            cuotas_supervisor_lider.participa
        FROM
            afiliados
        INNER JOIN ventas ON ventas.id_vendedor = afiliados.ID
        INNER JOIN productos ON ventas.id_producto = productos.id
        INNER JOIN afiliado_almacen ON afiliado_almacen.id_afiliado = afiliados.id
        INNER JOIN almacenes ON afiliado_almacen.ID_ALMACEN = almacenes.id
        INNER JOIN periodo ON ventas.id_periodo = periodo.id
        INNER JOIN temporada ON temporada.id = periodo.id_temporada
        INNER JOIN cuotas_supervisor_lider  on cuotas_supervisor_lider.id_almacen = almacenes.id AND temporada.id = cuotas_supervisor_lider.id_temporada 
        left JOIN redenciones ON redenciones.id_almacen = almacenes.id 
        AND redenciones.temporada = temporada.id 
        AND redenciones.id_afiliado = (SELECT id FROM afiliados WHERE id_almacen = almacenes.id AND id_clasificacion = 4 LIMIT 1)
        AND redenciones.id_premio in (2468,2606,2651)
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
            AND almacenes.id = _id_almacen_ AND temporada.id >= 19
        GROUP BY temporada.id
        ORDER BY temporada.id desc
    ";
    public static $reporte_supervisor_lider = "
        SELECT
        almacenes.id AS id_drogueria,
        (SELECT nombre FROM afiliados WHERE id_almacen = almacenes.id AND id_clasificacion = 4 LIMIT 1) supervisor,
        (SELECT id FROM afiliados WHERE id_almacen = almacenes.id AND id_clasificacion = 4 LIMIT 1) id_supervisor,
        almacenes.id_visitador,
        afiliado_visitador.nombre ejecutivo,
        almacenes.nombre AS almacen,
        almacenes.id AS id_almacen,
        territorios.nombre AS territorio,
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
    INNER JOIN territorios ON territorios.id = almacenes.id_territorio
    INNER JOIN cuotas_supervisor_lider  on cuotas_supervisor_lider.id_almacen = almacenes.id AND temporada.id = cuotas_supervisor_lider.id_temporada 
    INNER JOIN afiliados afiliado_visitador ON afiliado_visitador.ID = almacenes.id_visitador
	 left JOIN redenciones ON redenciones.id_almacen = almacenes.id 
    AND redenciones.temporada = temporada.id 
    AND redenciones.id_afiliado = (SELECT id FROM afiliados WHERE id_almacen = almacenes.id AND id_clasificacion = 4 LIMIT 1)
    AND redenciones.id_premio in (2468,2606,2651)
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
    AND temporada.id >= 19
    GROUP BY temporada.id,almacenes.id
    ORDER BY almacenes.id 
    ";
    public static $reporte_cupos_almacen = "
        SELECT
            afi.id Id_representante,
            afi.nombre Representante,
            alm.id id_almacen,
            alm.nombre Distribuidora,
            ter.nombre territorio,
            ciu.nombre ciudad,
            alm.margen
        FROM 
            almacenes alm
            left JOIN afiliados afi ON afi.ID = alm.id_visitador
            left JOIN ciudad ciu ON ciu.ID = alm.id_ciudad
            inner JOIN territorios ter ON ter.id = alm.id_territorio
            ORDER BY alm.id
    ";
    public static $reporte_ventas_distribuidora = "
        SELECT
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
            WHERE tem.id != 12
            GROUP BY alm.id,tem.id
    ";
    public static $grafica_usuario_ecu = "
        select
            ven.id_periodo,
            ven.id_usuario,
            sum(ven.venta) venta,
            cuo.id_periodo,
            cuo.id_usuario,
            sum(cuo.cuota) cuota,
            tem.nombre nombre,
            tem.id,
            ROUND((SUM(ven.venta)/sum(cuo.cuota))*100) cumplimiento
        from cuotas cuo
            inner join ventas_afiliado ven on cuo.id_usuario = ven.id_usuario
            inner join periodo per on per.id = ven.id_periodo and per.id = cuo.id_periodo
            inner join temporada tem on tem.id = per.id_temporada
        where ven.id_usuario = _id_usuario_
        group by 
            ven.id_usuario,
            tem.id
        order by ven.id_usuario
    ";
    public static $datos_afiliado = "
        SELECT
            afi.id,
            afi.nombre,
            afi.tipo_doc,
            afi.cedula,
            afi.cod_formas,
            afi.nacimiento,
            ciu.ID id_ciudad,
            ciu.ID_DEPARTAMENTO id_departamento,
            afi.direccion,
            afi.telefono,
            afi.celular,
            afi.email,
            afi.id_genero,
            afi.id_estatus,
            afi.id_almacen,
            alm.nombre almacen,
            alm.id_visitador id_representante
        FROM afiliados afi
            left JOIN almacenes alm ON alm.id = afi.id_almacen 
            left JOIN ciudad ciu ON ciu.ID = afi.ID_CIUDAD 
    ";
    public static $estado_cuenta_afiliado = "
        select
            e.*,
            p.id_temporada,
            p.nombre periodo
            from t_estado_cuenta e
            INNER JOIN periodo p ON p.id = e.id_periodo
    ";
    public static $denegar_solicitud_premio = "
        SELECT
        * 
        from redenciones red
        INNER JOIN premios pre ON pre.ID = red.id_premio
    ";
    public static $cuotas_simulador = "
        SELECT
            cuo.id_usuario,
            cuo.id_temporada,
            SUM(cuota_1) cuota_1,
            SUM(cuota_2) cuota_2,
            SUM(impactos) impactos
        FROM 
            cuotas cuo
        INNER JOIN vendedores_supervisor ven ON ven.id_vendedor = cuo.id_usuario AND ven.id_temporada = cuo.id_temporada
    ";
    public static $cuotas_simulador_vendedor = "
        SELECT
            cuo.id_usuario,
            afi.nombre,
            cuo.id_temporada,
            cuota_1,
            cuota_2,
            impactos
        FROM vendedores_supervisor ven
            INNER JOIN afiliados afi ON afi.ID = ven.id_vendedor
            INNER JOIN cuotas cuo ON ven.id_vendedor = cuo.id_usuario AND ven.id_temporada = cuo.id_temporada
    ";
    public static $reporte_ventas_sku = "
        SELECT 
            ter.id,
            ter.nombre territorio,
            alm.id id_distribuidora,
            alm.nombre distribuidora,
            vis.nombre visitador,
            ves.ID id_supervisor,
            ves.nombre supervisor,
            afi.id id_afiliado,
            afi.nombre afiliado,
            per.nombre periodo,
            SUM(ven.valor) venta,
            cat.id id_categoria,
            cat.nombre categoria,
            pro.nombre,
            pro.portafolio
    FROM ventas ven
            INNER JOIN afiliados afi ON afi.id = ven.id_vendedor
            INNER JOIN almacenes alm ON alm.id = afi.ID_ALMACEN
            INNER JOIN territorios ter ON ter.id = alm.id_territorio
            INNER JOIN afiliados vis ON vis.ID = alm.id_visitador
            INNER JOIN periodo per ON per.id = ven.id_periodo
            left JOIN vendedores_supervisor sup ON sup.id_vendedor = afi.id
            left JOIN afiliados ves ON ves.ID = sup.id_supervisor
            INNER JOIN productos pro ON pro.id = ven.id_producto
            INNER JOIN categoria_producto cat ON cat.id = pro.id_categoria
    WHERE id_periodo > 2
    group BY ven.id_vendedor,per.id,pro.id;
    ";
    public static $reporte_cuotas_supervisor = "
        select
            alm.id id_distribuidora,
            alm.nombre distribuidora,
            afi.NOMBRE,
            tem.id id_temporada,
            tem.nombre temporada,
            cuo.cuota_1, 
            cuo.cuota_2
        from 
            cuotas_supervisor cuo
            inner join temporada tem on tem.id = cuo.id_temporada
            inner join periodo per on per.id_temporada = tem.id
            inner join almacenes alm on alm.id = cuo.id_almacen       
				INNER JOIN vendedores_supervisor sup ON sup.id_supervisor = cuo.id_afiliado   
				INNER JOIN afiliados afi ON afi.ID = sup.id_supervisor        
    ";
    public static $reporte_estado_cuenta_total = "
                SELECT
                    per.id id_periodo,
                    per.nombre periodo,
                    vis.id id_visitador,
                    vis.NOMBRE visitador,
                    alm.id id_almacen,
                    alm.nombre almacen,
                    ven.id id_vendedor,
                    ven.nombre vendedor,
                    cat.id id_categoria,
                    cat.nombre categoria,
                    est.venta,
                    est.venta_especial,
                    est.cuota,
                    est.cumplimiento,
                    est.puntos_venta,
                    est.puntos_especial,
                    est.cuota_impactos,
                    est.impactos,
                    est.cumplimiento_impactos,
                    est.puntos_impactos,
                    est.total_puntos_venta,
                    est.total_puntos
                FROM t_estado_cuenta est
                    INNER JOIN periodo per ON per.id = est.id_periodo
                    INNER JOIN almacenes alm ON alm.id = est.id_almacen
                    INNER JOIN afiliados ven ON ven.ID = est.id_vendedor
                    left JOIN categorias cat ON cat.id = est.id_categoria
                    left JOIN afiliados vis ON vis.id = alm.id_visitador
    ";
    public static $reporte_estructura_supervisores = "
            SELECT 
                afi.id id_supervisor,
                afi.nombre supervisor,
                afi.COD_FORMAS,
                alm.id id_almacen,
                alm.nombre almacenes,
                vis.ID id_gerente,
                vis.NOMBRE gerente
            FROM afiliados afi 
                INNER JOIN almacenes alm ON alm.id = afi.ID_ALMACEN
                INNER JOIN afiliados vis ON vis.ID = alm.id_visitador
                WHERE afi.id_clasificacion = 4                    
                
            ";
    public static $reporte_cuotas_actualizadas = "
        SELECT 				
            alm.id id_distribuidora,
            alm.nombre distribuidora,
            ter.nombre territorio,
            vis.id id_ejecutivo,
            vis.nombre ejecutivo,
            afi.ID id_vendedor,
            afi.nombre vendedor,
            lo.id_cuota,
            per. nombre periodo,
            lo.cuota_1_anterior,
            lo.cuota_2_anterior,
            lo.cuota_1_nueva,
            lo.cuota_2_nueva,
            lo.fecha,
            reg.NOMBRE actualiza
        FROM 
            log_cuotas lo
            INNER JOIN cuotas cuo ON cuo.id = lo.id_cuota
            INNER JOIN afiliados afi ON afi.id = cuo.id_usuario
            INNER JOIN periodo per ON per.id = cuo.id_periodo
            INNER JOIN almacenes alm ON alm.id = afi.ID_ALMACEN
            INNER JOIN afiliados vis ON vis.id = alm.id_visitador
            INNER JOIN territorios ter ON ter.id = alm.id_territorio
            INNER JOIN afiliados reg ON reg.ID = lo.id_actualiza                   
                
            ";
    public static $consulta_cupos_almacenes_temporadas = "
            SELECT
                tem.id id_temporada,
                tem.nombre temporada,
                alm.supervisores,
                alm.cupos_diamante,
                alm.cupos_oro,
                alm.cupos_plata,
                alm.total_premiados
            FROM cupos_almacenes alm
                INNER JOIN temporada tem ON tem.id = alm.id_temporada                  
                
            ";

    public static $reporte_habeas_data = "
        SELECT
            hab.id,
            mad.nombre distribudora_madre,
            alm.nombre distribuidora,
            ciu.nombre ciudad,
            vis.nombre visitador,
            ter.nombre territorio,
            hab.nombre nombre_firma,
            hab.documento,
            hab.fecha,
            CONCAT('https://sociosyamigos.com.co/tat/ver_actas.php?id_almacen=',alm.id) link
        FROM habeas_data hab
            INNER JOIN almacenes alm ON alm.id = hab.id_almacen
            INNER JOIN distribuidora_madre mad ON mad.id = alm.id_madre
            INNER JOIN ciudad ciu ON ciu.id = alm.id_ciudad
            INNER JOIN afiliados vis ON vis.id = alm.id_visitador
            INNER JOIN territorios ter ON ter.id = alm.id_territorio              
                
            ";

    public static $clasificacion_afiliados_temporada = "
        SELECT
            nue.id_afiliado,
            tem.nombre temporada,
            cat.nombre categoria
        FROM 
            nueva_clasificacion_usuario nue
            INNER JOIN categorias cat ON cat.id = nue.id_categoria
            INNER JOIN temporada tem ON tem.id = nue.id_temporada            
                
            ";

    public static $reporte_distribuidordas_sin_habeas_data = "
        SELECT
            mad.id id_distribuidora_madre,
            mad.nombre distribuidora_madre,
            alm.id id_distribuidora,
            alm.nombre distribuidora,
            vis.nombre visitador,
            ter.nombre territorio,
            ciu.nombre ciudad,
            case
            when estado = 1 then 'Activo'
            when estado = 0 then 'Inactivo'
            END estado
        FROM 
            almacenes alm
            LEFT JOIN distribuidora_madre mad ON mad.id = alm.id_madre
            LEFT JOIN ciudad ciu ON ciu.ID = alm.id_ciudad
            INNER JOIN afiliados vis ON vis.ID = alm.id_visitador
            LEFT JOIN territorios ter ON ter.id = alm.id_territorio
        WHERE 
            alm.id NOT IN (SELECT id_almacen FROM habeas_data)         
                
            ";
    public static $consulta_llamadas_usuarios = "
        SELECT
            la.id,
            la.fecha,
            concat(tp.NOMBRE,'-',sc.NOMBRE,'-',cl.NOMBRE) categoria,
            usr.NOMBRE registro,
            comentario
        FROM
            llamadas_usuarios la
            inner join categorias_llamada cl on la.ID_SUBCATEGORIA = cl.ID
            inner join categorias_llamada sc on sc.ID = cl.ID_PADRE
            inner join categorias_llamada tp on tp.ID = sc.ID_PADRE
            left join afiliados usr on usr.id = la.id_usuario_registra
    ";
}