	SET @id_temporada = 3;
	SET @cargar = 0;
	DROP TEMPORARY TABLE IF EXISTS t_ventas_almacen;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas_almacen AS (
	
		select 
			per.id_temporada,
			ecu.id_periodo,
			afi.id_almacen,
			sum(ecu.venta) venta
		from 
			estado_cuenta ecu 
			inner join vendedores_supervisor ven ON ven.id_vendedor = ecu.id_afiliado
			inner join afiliado_almacen afi on afi.id_afiliado = ven.id_vendedor
			inner join periodo per on per.id = ecu.id_periodo
			WHERE per.id_temporada = @id_temporada
		group by 
			afi.ID_ALMACEN,ecu.id_periodo
		order by
			per.id_temporada,
			per.id
	
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_cuotas_almacen;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_cuotas_almacen AS (
	
		select
			per.id_temporada,
			per.id id_periodo,
			per.nombre periodo,
			afi.id_almacen,
			afi.id id_afiliado,
			afi.nombre afiliado,
			cuo.cuota_1,
			cuo.cuota_2,
			cuo.impactos,
			cuo.puede_redimir
		from 
			cuotas cuo
			INNER join vendedores_supervisor ven ON ven.id_vendedor = cuo.id_usuario AND ven.id_temporada = cuo.id_temporada
			INNER join periodo per on per.id_temporada = cuo.id_temporada
			INNER join afiliados afi on afi.id = ven.id_supervisor
		WHERE
			afi.ID_ESTATUS = 4 
			and cuo.id_temporada = @id_temporada
		order by
			per.id_temporada,
			per.id
		
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_consolidado;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_consolidado AS (
	
		select
			cuo.id_temporada,
			cuo.id_periodo,
			cuo.periodo,
			cuo.id_almacen,
			cuo.id_afiliado,
			cuo.afiliado,
			sum(cuo.impactos)impactos,
			sum(cuo.cuota_1)cuota_1,
			sum(cuo.cuota_2)cuota_2,
			cuo.puede_redimir,
			ven.venta
		from 
			t_cuotas_almacen cuo
			left join t_ventas_almacen ven on 
				cuo.id_almacen = ven.id_almacen and
				cuo.id_periodo = ven.id_periodo AND
				cuo.id_temporada = ven.id_temporada
		WHERE 
			cuo.id_temporada = @id_temporada
		GROUP BY 
			id_afiliado,periodo
		ORDER BY
			cuo.id_temporada,
			cuo.id_periodo
			
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_agrupado;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_agrupado AS (
	
		select 
			id_temporada,
			id_almacen,
			id_afiliado,
			impactos,
			GROUP_CONCAT(periodo ORDER BY id_periodo SEPARATOR '|') as 'periodos',
			sum(case when id_periodo % 2 = 0 then cuota_1 else 0 end) cuota_1,
			sum(case when id_periodo % 2 = 1 then cuota_2 else 0 end) cuota_2,
			sum(case when id_periodo % 2 = 0 then venta else 0 end) venta_1,
			sum(case when id_periodo % 2 = 1 then venta else 0 end) venta_2,
			min(puede_redimir) puede_redimir
		from 
			t_consolidado
		WHERE 
			id_temporada = @id_temporada
		group by
			id_temporada,
			id_almacen,
			id_afiliado
		
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_total;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_total AS (
	
		select
			id_temporada,
			id_almacen,
			id_afiliado,
			impactos,
			periodos,
			cuota_1,
			venta_1,
			round((venta_1 * 100) / cuota_1,2) cumplimiento_1,
			venta_2,
			cuota_2,
			round((venta_2 * 100) / cuota_2,2) cumplimiento_2,
			cuota_1 + cuota_2 cuota_bimestre,
			venta_1 + venta_2 venta_bimestre,
			round(((venta_1 + venta_2) * 100) / (cuota_1 + cuota_2),2) cumplimiento_bimestre,
			puede_redimir
		from 
			t_agrupado
		WHERE 
			id_temporada = @id_temporada
			
	);
	
	select
		tem.nombre temporada,
		afi.nombre supervisor,
		afi.id_categoria,
		cat.nombre categoria,
		(
			select count(distinct red.id) 
			from redenciones red 
			INNER JOIN premios pre ON pre.id = red.id_premio
			where red.temporada = tot.id_temporada
			 AND red.id_afiliado = tot.id_afiliado 
			 AND pre.navidad != 1
		) redenciones_temporada,
		tot.*
	from 
		t_total tot
		inner join afiliados afi on afi.id = tot.id_afiliado
		inner join temporada tem on tem.id = tot.id_temporada
		INNER JOIN categorias cat ON cat.id = afi.id_categoria
		WHERE id_temporada = @id_temporada
		ORDER BY 4 desc;
	
	INSERT INTO cuotas_supervisor(id_almacen,id_temporada,id_afiliado,cuota_1,cuota_2,fecha)
	SELECT 
		id_almacen,
		id_temporada,
		id_afiliado,
		cuota_1,
		cuota_2,
		NOW()
	FROM t_total
	WHERE @cargar = 1;
	
	select * from t_agrupado;
	select * from t_consolidado;
	select * from t_ventas_almacen;
	select * from t_cuotas_almacen;
	
	
/*	drop table t_cuotas_almacen;
	drop table t_ventas_almacen;
	drop table t_consolidado;
	drop table t_agrupado;
	drop table t_total;*/
