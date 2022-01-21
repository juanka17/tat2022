DROP PROCEDURE IF EXISTS sp_ventas_promedio;

DELIMITER //

CREATE PROCEDURE sp_ventas_promedio()
BEGIN
	
		DROP TEMPORARY TABLE IF EXISTS t_ventas_almacen;
		CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas_almacen AS (
			SELECT alm.id id_almacen,
				alm.nombre almacen,
				ve.id_periodo id_periodo,
				SUM(ve.valor) venta_almacen
			FROM ventas ve
				INNER JOIN afiliados af ON ve.id_vendedor=af.ID
				INNER JOIN almacenes alm ON alm.id=af.ID_ALMACEN
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
				INNER JOIN almacenes alm ON alm.id=af.ID_ALMACEN
			WHERE ve.id_periodo IN (9,10,11)
			GROUP BY af.ID
			);

SELECT tv.id_almacen,
		almacen,
		venta_almacen,
		id_vendedor,
		vendedor,
		venta_vendedor,
		ta.id_periodo,
		(venta_vendedor/venta_almacen)*100 porcentaje_participacion,
		ca.cuota cuota_almacen,
		((venta_vendedor/venta_almacen)*100) * ca.cuota /100 cuota_vendedor
FROM t_ventas_vendedores tv
INNER JOIN t_ventas_almacen ta ON tv.id_almacen=ta.id_almacen
inner JOIN cuotas_almacen ca ON ca.id_almacen=tv.id_almacen;

SELECT * FROM t_ventas_almacen;
SELECT * FROM t_ventas_vendedores;

DROP TEMPORARY TABLE IF EXISTS t_ventas_vendedores;
DROP TEMPORARY TABLE IF EXISTS t_ventas_almacen;

END//

call sp_ventas_promedio();
