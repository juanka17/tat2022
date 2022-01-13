DROP PROCEDURE IF EXISTS sp_reporte_ventas;
DELIMITER //
CREATE PROCEDURE sp_reporte_ventas(IN id_temporada_p INT)
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
BEGIN
	
	SET @id_temporada = id_temporada_p;

	DROP TEMPORARY TABLE IF EXISTS t_ventas;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas AS (
	
		select
         alm.id id_distribuidora,
         alm.nombre distribuidora,
         ter.nombre territorio,
         afi.id id_vendedor,
         afi.nombre vendedor,
         vis.id id_ejecutivo,
         vis.nombre ejecutivo,
         ciu.nombre ciudad,
         per.id id_periodo,
         per.nombre periodo,
         tem.id id_temporada,
         tem.nombre temporada,
         ifnull(cat.nombre,'NO REGISTRA') categoria,
         ifnull(pro.nombre,'NO REGISTRA') producto,
         ifnull(pro.sku,'NO REGISTRA') sku,
         pro.portafolio,
         mar.nombre marca,
         ven.unidades,
         ven.valor      
      from 
         ventas ven
         left join afiliado_almacen afia ON ven.id_vendedor = afia.id_afiliado
         left JOIN afiliados afi ON afi.ID = ven.id_vendedor
         left JOIN almacenes alm ON alm.id = afia.id_almacen
         left JOIN territorios ter ON ter.id = alm.id_territorio
         left JOIN afiliados vis ON vis.ID = alm.id_visitador         
         left join periodo per on per.id = ven.id_periodo
         left join temporada tem on tem.id = per.id_temporada
         left join productos pro on pro.id = ven.id_producto
         left join categoria_producto cat on cat.id = pro.id_categoria
         left join ciudad ciu on ciu.id = alm.id_ciudad
         left JOIN marcas mar ON mar.id = pro.id_marca
         WHERE id_periodo > 2
		
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_impactos;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_impactos AS (
		select distinct
			id_distribuidora,
			distribuidora,
			territorio,
			id_vendedor,
			vendedor,
			id_ejecutivo,
			ejecutivo,
			ciudad,
			ven.id_periodo,
			periodo,
			id_temporada,
			temporada,
			'Impactos' categoria,
			'Impactos' producto,
			'Impactos' sku,
			'Impactos' portafolio,
			'Impactos' marca,
			0 unidades,
			imp.impactos venta_total
		from 
			t_ventas ven
			left join impactos imp on 
				imp.id_periodo = ven.id_periodo 
				and imp.id_afiliado = ven.id_vendedor
	);
	
		if @id_temporada >= 5 then
		
			SELECT 
				* 
			FROM 
				t_ventas
			WHERE 
				id_distribuidora 
				NOT IN (4,3865,3905,86,95,96,3862) AND id_temporada = @id_temporada
			UNION all
			SELECT 
				* 
			FROM 
				t_impactos
			WHERE 
				id_distribuidora 
				NOT IN (4,3865,3905,86,95,96,3862)  AND id_temporada = @id_temporada
			ORDER BY
				id_ejecutivo,
				distribuidora,
				id_vendedor,
				id_periodo,
				sku;
		
		ELSE
		
			SELECT 
			* 
			FROM 
				t_ventas
			WHERE 
				id_distribuidora 
				NOT IN (3918,3919) AND id_temporada = @id_temporada
			UNION
			SELECT 
			* 
			FROM 
				t_impactos
			WHERE 
				id_distribuidora 
				NOT IN (3918,3919) AND id_temporada = @id_temporada
			ORDER BY
				id_ejecutivo,
				distribuidora,
				id_vendedor,
				id_periodo,
				sku;
		END if;
	
END//
DELIMITER ;

call sp_reporte_ventas(6);