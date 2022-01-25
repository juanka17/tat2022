DROP PROCEDURE IF EXISTS sp_ventas_promedio;

DELIMITER //

CREATE PROCEDURE sp_ventas_promedio(IN id_periodo_t INT, IN tmp_almacen INT)
BEGIN
	
		DROP TEMPORARY TABLE IF EXISTS t_ventas_almacen;
		CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas_almacen AS (
			SELECT alm.id id_almacen,
				alm.nombre almacen,
				SUM(ve.valor) venta_almacen
			FROM ventas ve
				INNER JOIN afiliados af ON ve.id_vendedor=af.ID
				INNER JOIN almacenes alm ON alm.id=af.ID_ALMACEN AND alm.id=tmp_almacen
			WHERE ve.id_periodo IN (9,10,11)
			GROUP BY alm.id
			);
				
		DROP TEMPORARY TABLE IF EXISTS t_ventas_vendedores;
		CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas_vendedores AS (
			SELECT alm.id id_almacen,
				af.ID id_vendedor,
				af.nombre vendedor,
				af.NOMBRE,
				SUM(ve.valor) venta_vendedor
			FROM ventas ve
				INNER JOIN afiliados af ON ve.id_vendedor=af.ID
				INNER JOIN almacenes alm ON alm.id=af.ID_ALMACEN AND alm.id=tmp_almacen
			WHERE ve.id_periodo IN (9,10,11)
			GROUP BY af.ID
			);

		DROP TEMPORARY TABLE IF EXISTS t_participacion_vendedores;
		CREATE TEMPORARY TABLE IF NOT EXISTS t_participacion_vendedores AS (
			SELECT tv.id_almacen,
					almacen,
					venta_almacen,
					id_vendedor,
					vendedor,
					venta_vendedor,
					id_periodo_t id_periodo,
					(venta_vendedor/venta_almacen)*100 porcentaje_participacion,
					ca.cuota cuota_almacen,
					case when ((venta_vendedor/venta_almacen)*100 * ca.cuota /100) < 500000 then 500000 
						else ((venta_vendedor/venta_almacen)*100) * ca.cuota /100 end cuota_vendedor,
					ca.impactos cuota_impactos
			FROM t_ventas_vendedores tv
			INNER JOIN t_ventas_almacen ta ON tv.id_almacen=ta.id_almacen
			inner JOIN cuotas_almacen ca ON ca.id_almacen=tv.id_almacen AND ca.id_periodo=id_periodo_t
		);
		
		DROP TEMPORARY TABLE IF EXISTS t_supervisor_almacen;
		CREATE TEMPORARY TABLE IF NOT EXISTS t_supervisor_almacen AS (
			SELECT id id_supervisor, 
			nombre, 
			id_almacen
			FROM afiliados WHERE id_almacen=tmp_almacen
			AND id_categoria=5 AND id_clasificacion=4 and id_estatus=4
		);

		DROP TEMPORARY TABLE IF EXISTS t_vendedores_supervisor;
		CREATE TEMPORARY TABLE IF NOT EXISTS t_vendedores_supervisor AS (
			SELECT ven.*
			FROM t_supervisor_almacen t 
			INNER JOIN vendedores_supervisor ven ON ven.id_supervisor=t.id_supervisor
			WHERE ven.id_periodo=id_periodo_t
		);

		INSERT INTO cuotas (id_usuario,id_periodo,cuota_venta,puede_redimir, fecha)
			SELECT
			id_vendedor id_usuario,
			id_periodo,
			cuota_vendedor cuota_venta,
			1 puede_redimir,
			NOW()
			FROM t_participacion_vendedores
			WHERE id_periodo= id_periodo_t
			AND id_vendedor NOT IN (SELECT id_usuario FROM cuotas WHERE id_periodo= id_periodo_t);

		SELECT * FROM t_ventas_almacen;
		SELECT * FROM t_ventas_vendedores;

		INSERT INTO cuotas_supervisor (id_afiliado,cuota_venta,id_periodo,cuota_impactos,id_almacen,puede_redimir,fecha)
		SELECT sup.id_supervisor id_afiliado, 
		SUM(cuota_vendedor) cuota_venta, 
		id_periodo_t id_periodo,
		(SUM(porcentaje_participacion) * cuota_impactos /100)  cuota_impactos,
		tmp_almacen id_almacen,
		1 puede_redimir,
		NOW()
		FROM t_supervisor_almacen sup 
		INNER JOIN t_participacion_vendedores par ON par.id_almacen=sup.id_almacen
		INNER JOIN t_vendedores_supervisor ven ON ven.id_supervisor=sup.id_supervisor AND ven.id_vendedor = par.id_vendedor
		WHERE sup.id_supervisor NOT IN (SELECT id_afiliado FROM cuotas_supervisor WHERE id_periodo=id_periodo_t)
		GROUP BY sup.id_supervisor;

DROP TEMPORARY TABLE IF EXISTS t_ventas_vendedores;
DROP TEMPORARY TABLE IF EXISTS t_ventas_almacen;
DROP TEMPORARY TABLE IF EXISTS t_ventas_vendedores;
DROP TEMPORARY TABLE IF EXISTS t_supervisor_almacen;

END//

call sp_ventas_promedio(14, 3918);
