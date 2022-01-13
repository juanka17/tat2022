DROP PROCEDURE IF EXISTS sp_reporte_ventas_2020;
DELIMITER //
CREATE PROCEDURE sp_reporte_ventas_2020(IN id_temporada_p INT)
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
BEGIN
		SET @id_temporada = id_temporada_p;
		
		DROP TEMPORARY TABLE IF EXISTS t_ventas_reporte;
		CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas_reporte AS (
		
			SELECT
				ter.id id_territorio,
				ter.nombre territorio,
				alm.id id_distribuidora,
				alm.nombre distribuidora,
				vis.ID id_ejecutivo,
				vis.nombre ejecutivo,
				afi.id id_afiliado,
				afi.nombre afiliado,
				per.id id_periodo,
				per.nombre periodo,
				per.id_temporada,
				ven.valor venta
			FROM 
				ventas ven 
				inner join afiliado_almacen afia ON ven.id_vendedor = afia.id_afiliado
				inner JOIN afiliados afi ON afi.ID = ven.id_vendedor
				inner JOIN almacenes alm ON alm.id = afia.id_almacen
				inner JOIN territorios ter ON ter.id = alm.id_territorio
				inner JOIN afiliados vis ON vis.ID = alm.id_visitador
				inner JOIN periodo per ON per.id = ven.id_periodo
			WHERE 
				ven.id_periodo > 2
			
		);
		
		if @id_temporada >= 5 then
			SELECT 
				venta.id_territorio,
				venta.territorio,
				venta.id_distribuidora,
				venta.distribuidora,
				venta.id_ejecutivo,
				venta.ejecutivo,
				venta.id_afiliado,
				venta.afiliado,
				venta.id_periodo,
				venta.periodo,
				venta.id_temporada,
				sum(venta.venta) venta,
				cuo.cuota_1,
				cuo.impactos cuota_impactos,
				imp.impactos
			FROM 
			t_ventas_reporte venta
			left JOIN impactos imp ON imp.id_afiliado = venta.id_afiliado AND imp.id_periodo = venta.id_periodo
			LEFT JOIN cuotas cuo ON cuo.id_usuario = venta.id_afiliado AND cuo.id_temporada = venta.id_temporada
			WHERE 
			id_distribuidora 
			NOT IN (4,3865,3905,86,95,96,3862) 
			AND venta.id_temporada = @id_temporada
			GROUP BY 
				venta.id_afiliado,
				venta.id_periodo;
		ELSE
			SELECT 
				venta.id_territorio,
				venta.territorio,
				venta.id_distribuidora,
				venta.distribuidora,
				venta.id_ejecutivo,
				venta.ejecutivo,
				venta.id_afiliado,
				venta.afiliado,
				venta.id_periodo,
				venta.periodo,
				venta.id_temporada,
				sum(venta.venta) venta,
				cuo.cuota_1,
				cuo.impactos cuota_impactos,
				imp.impactos
			FROM 
			t_ventas_reporte venta
			left JOIN impactos imp ON imp.id_afiliado = venta.id_afiliado AND imp.id_periodo = venta.id_periodo
			LEFT JOIN cuotas cuo ON cuo.id_usuario = venta.id_afiliado AND cuo.id_temporada = venta.id_temporada
			WHERE 
			id_distribuidora 
			NOT IN (3918,3919) 
			AND venta.id_temporada = @id_temporada
			GROUP BY 
				venta.id_afiliado,
				venta.id_periodo;
		END if;

		
				
END//
DELIMITER ;

call sp_reporte_ventas_2020(6);