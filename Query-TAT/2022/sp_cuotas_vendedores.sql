DROP PROCEDURE IF EXISTS sp_cuotas_vendedores;
DELIMITER //
CREATE PROCEDURE sp_cuotas_vendedores(IN tmp_almacen INT)
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
BEGIN

	set @id_almacen = tmp_almacen;
	
	DROP TEMPORARY TABLE IF EXISTS t_ventas_almacen;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas_almacen AS (
		select 
			ecu.id_vendedor id_afiliado,
			per.id_temporada,
			ecu.id_periodo,
			afi.id_almacen,
			sum(ecu.valor) venta
		from 
			ventas ecu 
			inner join afiliados afi on afi.id = ecu.id_vendedor
			inner join periodo per on per.id = ecu.id_periodo
		where 
			afi.ID_ALMACEN = @id_almacen
		group by 
			ecu.id_vendedor,
			ecu.id_periodo
		order by
			per.id_temporada,
			per.id
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_cuotas_almacen;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_cuotas_almacen AS (
		select
			cuo.id id_cuota,
			per.id id_periodo,
			per.nombre periodo,
			af.ID_ALMACEN,
			af.id id_afiliado,
			af.nombre afiliado,
			cuo.cuota_venta,
			cuo.puede_redimir
		from 
			cuotas cuo
			INNER join periodo per on per.id=cuo.id_periodo
			INNER JOIN afiliados af ON af.ID = cuo.id_usuario
		where
			af.id_almacen = @id_almacen
		order by
			per.id_temporada,
			per.id
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_consolidado;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_consolidado AS (
		select
			cuo.id_cuota,
			cuo.id_periodo,
			cuo.periodo,
			cuo.id_almacen,
			cuo.id_afiliado,
			cuo.afiliado,
			cuo.cuota_venta,
			cuo.puede_redimir,
			ven.venta
		from 
			t_cuotas_almacen cuo
			left join t_ventas_almacen ven on 
				cuo.id_almacen = ven.id_almacen and
				cuo.id_periodo = ven.id_periodo AND
				cuo.id_afiliado = ven.id_afiliado
		order by
			cuo.id_periodo
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_agrupado;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_agrupado AS (
		select 
			id_cuota,
			id_almacen,
			id_afiliado,
			periodo,
			cuota_venta,
			venta,
			puede_redimir
		from 
			t_consolidado
		group by
			id_almacen,
			id_afiliado
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_total;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_total AS (
		select
			id_cuota,
			id_almacen,
			id_afiliado,
			periodo,
			cuota_venta,
			venta,
			round((venta * 100) / cuota_venta,2) cumplimiento,
			puede_redimir
		from 
			t_agrupado
	);
	
	select
		afi.nombre supervisor,
		(
			select count(distinct red.id) 
			from redenciones red 
			INNER JOIN premios pre ON pre.id = red.id_premio
			WHERE red.id_afiliado = tot.id_afiliado 
			 AND pre.navidad != 1
		) redenciones_temporada,
		tot.*
	from 
		t_total tot
		inner join afiliados afi on afi.id = tot.id_afiliado
		LEFT JOIN nueva_clasificacion_usuario nue ON nue.id_afiliado = afi.id		
		LEFT JOIN categorias cat ON cat.id = nue.id_categoria
		ORDER BY 7 desc;
	
	select * from t_total;
	select * from t_agrupado;
	select * from t_consolidado;
	select * from t_ventas_almacen;
	select * from t_cuotas_almacen;
	
	
	/*drop table t_cuotas_almacen;
	drop table t_ventas_almacen;
	drop table t_consolidado;
	drop table t_agrupado;
	drop table t_total;*/

END//
DELIMITER ;

call sp_cuotas_vendedores(3919);