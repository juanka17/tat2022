DROP PROCEDURE IF EXISTS sp_reporte_cuotas_vendedores;
DELIMITER //
CREATE PROCEDURE sp_reporte_cuotas_vendedores()
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
BEGIN

	DROP TEMPORARY TABLE IF EXISTS t_ventas_almacen;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas_almacen AS (
			
		SELECT 
			alm.id id_almacen,
			alm.nombre almacen,
			ifnull(sum(case when ve.id_periodo IN (9,10,11) then ve.valor END),1) venta_almacen
		FROM 
			ventas ve
			INNER JOIN afiliados af ON ve.id_vendedor=af.ID
			INNER JOIN almacenes alm ON alm.id=af.ID_ALMACEN
		WHERE
				af.ID_ESTATUS != 2
		GROUP BY 
			alm.id
				
	);
				
	DROP TEMPORARY TABLE IF EXISTS t_ventas_vendedores;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas_vendedores AS (
			
		SELECT 
			alm.id id_almacen,
			af.ID id_vendedor,
			af.nombre vendedor,
			ifnull(sum(case when ve.id_periodo IN (9,10,11) then ve.valor END),0) venta_ultimo_q_vendedor,
			ifnull(sum(case when ve.id_periodo IN (9,10,11) then ve.valor/3 END),0) venta_vendedor
		FROM 
			ventas ve
			left JOIN afiliados af ON ve.id_vendedor=af.ID
			inner JOIN almacenes alm ON alm.id=af.ID_ALMACEN 
		WHERE
				af.ID_ESTATUS != 2
		GROUP BY 
			af.ID
	);

		DROP TEMPORARY TABLE IF EXISTS t_participacion_vendedores;
		CREATE TEMPORARY TABLE IF NOT EXISTS t_participacion_vendedores AS (
			SELECT 
				ca.id_periodo,
				tv.id_almacen,
				almacen,				
				tv.id_vendedor,
				vendedor,
				venta_almacen,
				venta_ultimo_q_vendedor,
				venta_vendedor,
				(venta_ultimo_q_vendedor/venta_almacen)*100 porcentaje_participacion,
				ca.cuota_aumentada cuota_almacen,
				ROUND(((venta_ultimo_q_vendedor/venta_almacen)*100 * ca.cuota /100)) cuota_sin_aumento,
				round(ca.impactos/ca.cantidad_supervisores) cuota_impactos,
				case 					
					when esp.id_periodo IN (14,15,16,17,18,19) then esp.cuota
					when ((venta_ultimo_q_vendedor/venta_almacen)*100 * ca.cuota_aumentada /100) < 500000 then 500000 
					ELSE ROUND(((venta_ultimo_q_vendedor/venta_almacen)*100) * ca.cuota_aumentada /100)
				end cuota_vendedor
			FROM t_ventas_vendedores tv
			INNER JOIN t_ventas_almacen ta ON tv.id_almacen=ta.id_almacen
			inner JOIN cuotas_almacen ca ON ca.id_almacen=tv.id_almacen	AND ca.id_periodo = 18
			left JOIN cuotas_especiales_2022 esp ON esp.id_vendedor = tv.id_vendedor AND esp.id_periodo = ca.id_periodo
	
		);

	
		SELECT
			par.id_periodo,
			par.id_almacen,
			par.almacen,	
			ven.id_supervisor,			
			par.id_vendedor,
			par.vendedor,
			par.venta_almacen,
			par.venta_ultimo_q_vendedor,
			par.venta_vendedor,
			par.porcentaje_participacion,
			par.cuota_almacen,
			par.cuota_sin_aumento,
			par.cuota_vendedor,
			par.cuota_impactos,
			sup.NOMBRE supervisor
		FROM t_participacion_vendedores par
		left JOIN vendedores_supervisor ven ON ven.id_vendedor = par.id_vendedor AND ven.id_periodo = par.id_periodo
		left JOIN afiliados sup ON sup.ID = ven.id_supervisor
		ORDER BY 6 ;
		
		SELECT * FROM t_ventas_almacen;
		SELECT * FROM t_ventas_vendedores;	
		SELECT * FROM t_participacion_vendedores;
		
		DROP TEMPORARY TABLE t_ventas_almacen;
		DROP TEMPORARY TABLE t_ventas_vendedores;
		
		

END//
DELIMITER ;

call sp_reporte_cuotas_vendedores();