DROP PROCEDURE IF EXISTS sp_reporte_ventas;
DELIMITER //
CREATE PROCEDURE sp_reporte_ventas(IN per_inicio INT, IN per_final INT)
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
BEGIN

	DROP TEMPORARY TABLE IF EXISTS t_ventas;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas AS (
		select
         alm.id id_distribuidora,
         alm.nombre distribuidora,
         ter.nombre territorio,
         afi.id id_vendedor,
         afi.nombre vendedor,
         eje.id id_ejecutivo,
         eje.nombre ejecutivo,
         ciu.nombre ciudad,
         per.id id_periodo,
         per.nombre periodo,
         ifnull(cat.nombre,'NO REGISTRA') categoria,
         ifnull(pro.nombre,'NO REGISTRA') producto,
         ifnull(pro.sku,'NO REGISTRA') sku,
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
         INNER JOIN territorios ter ON ter.id = alm.id_territorio
		 where 
		 	ven.id_periodo in (per_inicio,per_final) 
		
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
			temporada,
			'Impactos' categoria,
			'Impactos' producto,
			'Impactos' sku,
			0 unidades,
			imp.impactos venta_total
		from 
			t_ventas ven
			left join impactos imp on 
				imp.id_periodo = ven.id_periodo 
				and imp.id_afiliado = ven.id_vendedor
	);
	
	select * 
	from t_ventas
	union
	select * 
	from t_impactos
	order by
		id_ejecutivo,
		distribuidora,
		id_vendedor,
		id_periodo,
		sku
	;
	
END//
DELIMITER ;

call sp_reporte_ventas(16,17);