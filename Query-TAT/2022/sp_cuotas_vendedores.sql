DROP PROCEDURE IF EXISTS sp_cuotas_vendedores;
DELIMITER //
CREATE PROCEDURE sp_cuotas_vendedores(IN tmp_almacen INT)
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
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
				SUM(ve.valor)/3 venta_vendedor
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
					(venta_vendedor/venta_almacen)*100 porcentaje_participacion,
					ca.cuota_aumentada cuota_almacen,
					((venta_vendedor/venta_almacen)*100 * ca.cuota /100) cuota_sin_aumento,
					case when ((venta_vendedor/venta_almacen)*100 * ca.cuota /100) < 500000 then 500000 
						else ((venta_vendedor/venta_almacen)*100) * ca.cuota /100
					end cuota_vendedor,
					((venta_vendedor/venta_almacen)*100) * ca.impactos /100 cuota_impactos,
					ca.impactos cuota_impactos_total
			FROM t_ventas_vendedores tv
			INNER JOIN t_ventas_almacen ta ON tv.id_almacen=ta.id_almacen
			inner JOIN cuotas_almacen ca ON ca.id_almacen=tv.id_almacen
		);
		
		DROP TEMPORARY TABLE IF EXISTS t_supervisor_almacen;
		CREATE TEMPORARY TABLE IF NOT EXISTS t_supervisor_almacen AS (
			SELECT id id_supervisor, 
			nombre, 
			id_almacen
			FROM afiliados WHERE id_almacen=tmp_almacen
			AND id_clasificacion=4 and id_estatus=4
		);

		DROP TEMPORARY TABLE IF EXISTS t_vendedores_supervisor;
		CREATE TEMPORARY TABLE IF NOT EXISTS t_vendedores_supervisor AS (
			SELECT ven.*
			FROM t_supervisor_almacen t 
			INNER JOIN vendedores_supervisor ven ON ven.id_supervisor=t.id_supervisor
		);

	
		SELECT * FROM t_participacion_vendedores;
		SELECT * FROM t_ventas_almacen;
		SELECT * FROM t_ventas_vendedores;	
		SELECT * FROM t_supervisor_almacen;
		SELECT * FROM t_vendedores_supervisor;
		
		DROP TEMPORARY TABLE t_participacion_vendedores;
		DROP TEMPORARY TABLE t_ventas_almacen;
		DROP TEMPORARY TABLE t_ventas_vendedores;
		DROP TEMPORARY TABLE t_supervisor_almacen;
		DROP TEMPORARY TABLE t_vendedores_supervisor;	


END//
DELIMITER ;

call sp_cuotas_vendedores(3919);