DROP PROCEDURE IF EXISTS sp_reporte_avance_entregas_temporada;

DELIMITER //

CREATE PROCEDURE sp_reporte_avance_entregas_temporada (IN id_temporada_busqueda INT)
LANGUAGE SQL
DETERMINISTIC
BEGIN
	
	set @id_temporada = id_temporada_busqueda;
	
	DROP TEMPORARY TABLE IF EXISTS t_participantes_temporada;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_participantes_temporada AS (
	
		select 
			ecu.id_afiliado,
			per.id_temporada,
			GROUP_CONCAT(per.nombre SEPARATOR '/') periodos
		from 
			estado_cuenta ecu 
			inner join periodo per on per.id = ecu.id_periodo
		where 
			per.id_temporada = @id_temporada
		group by
			ecu.id_afiliado,
			per.id_temporada
	
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_entregas_vendedores;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_entregas_vendedores AS (
	
		select
			tem.id id_temporada,
		   tem.nombre temporada,
			alm.id id_distribuidora,
			alm.nombre distribuidora,
			cup.total_premiados cupos,
			
			cup.cupos_diamante,
			(SELECT count(distinct r.id) entregas FROM redenciones r
			 inner join afiliado_almacen can on can.id_afiliado = r.id_afiliado 
			 INNER JOIN nueva_clasificacion_usuario nue ON nue.id_afiliado = can.id_afiliado AND nue.id_temporada = @id_temporada
			 where nue.ID_CATEGORIA = 1
			 AND can.id_almacen = alm.id
			 AND r.temporada = @id_temporada) entregas_diamante,
			 
			 (SELECT count(distinct est.id_afiliado) entregas FROM estado_cuenta est
			 inner join afiliado_almacen can on can.id_afiliado = est.id_afiliado 
			 INNER JOIN nueva_clasificacion_usuario nue ON nue.id_afiliado = can.id_afiliado AND nue.id_temporada = @id_temporada
			 INNER JOIN periodo per ON per.id = est.id_periodo
			 where nue.ID_CATEGORIA = 1
			 AND can.id_almacen = alm.id
			 AND per.id_temporada = @id_temporada
			 AND (puntos_venta > 0 || puntos_impacto > 0)) cumplen_diamante,
			
			cup.cupos_oro,
			(SELECT count(distinct r.id) entregas FROM redenciones r
			 inner join afiliado_almacen can on can.id_afiliado = r.id_afiliado 
			 INNER JOIN nueva_clasificacion_usuario nue ON nue.id_afiliado = can.id_afiliado AND nue.id_temporada = @id_temporada
			 where nue.ID_CATEGORIA = 2
			 AND can.id_almacen = alm.id
			 AND r.temporada = @id_temporada) entregas_oro,
			 
			 (SELECT count(distinct est.id_afiliado) entregas FROM estado_cuenta est
			 inner join afiliado_almacen can on can.id_afiliado = est.id_afiliado 
			 INNER JOIN nueva_clasificacion_usuario nue ON nue.id_afiliado = can.id_afiliado AND nue.id_temporada = @id_temporada
			 INNER JOIN periodo per ON per.id = est.id_periodo
			 where nue.ID_CATEGORIA = 2
			 AND can.id_almacen = alm.id
			 AND per.id_temporada = @id_temporada
			 AND (puntos_venta > 0 || puntos_impacto > 0)) cumplen_oro,
			 
			 cup.cupos_plata,
			 (SELECT count(distinct r.id) entregas FROM redenciones r
			 inner join afiliado_almacen can on can.id_afiliado = r.id_afiliado 
			 INNER JOIN nueva_clasificacion_usuario nue ON nue.id_afiliado = can.id_afiliado AND nue.id_temporada = @id_temporada
			 where nue.ID_CATEGORIA = 3
			 AND can.id_almacen = alm.id
			 AND r.temporada = @id_temporada) entregas_plata,
			 
			 (SELECT count(distinct est.id_afiliado) entregas FROM estado_cuenta est
			 inner join afiliado_almacen can on can.id_afiliado = est.id_afiliado 
			 INNER JOIN nueva_clasificacion_usuario nue ON nue.id_afiliado = can.id_afiliado AND nue.id_temporada = @id_temporada
			 INNER JOIN periodo per ON per.id = est.id_periodo
			 where nue.ID_CATEGORIA = 3
			 AND can.id_almacen = alm.id
			 AND per.id_temporada = @id_temporada
			 AND (puntos_venta > 0 || puntos_impacto > 0)) cumplen_plata,
			 
			count(distinct red.id) entregas,		
			
			
			(SELECT count(distinct r.id) entregas FROM redenciones r
			 inner join afiliado_almacen can on can.id_afiliado = r.id_afiliado 
			 INNER JOIN nueva_clasificacion_usuario nue ON nue.id_afiliado = can.id_afiliado AND nue.id_temporada = @id_temporada
			 where nue.ID_CATEGORIA = 4
			 AND can.id_almacen = alm.id
			 AND r.temporada = @id_temporada) otras_entregas,
			
			round((count(distinct red.id) * 100) / cup.total_premiados) avance
		from 
			almacenes alm
			inner join afiliado_almacen afi on afi.id_almacen = alm.id
			left join t_participantes_temporada pat on pat.id_afiliado = afi.id_afiliado
			inner join temporada tem on tem.id = pat.id_temporada
			left join redenciones red on red.id_afiliado = afi.id_afiliado and red.temporada = @id_temporada
			INNER JOIN cupos_almacenes cup ON cup.id_almacen = alm.id AND cup.id_temporada = tem.id			
		where
			ifnull(cup.total_premiados,0) > 0
		group by
			alm.id
			
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_entregas_supervisores;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_entregas_supervisores AS (
	
		select
			alm.id id_distribuidora,
			alm.nombre distribuidora,
			cup.supervisores cupos,
			count(distinct red.id) entregas,
			round((count(distinct red.id) * 100) / count(distinct cuo.id_afiliado)) avance
		from
			almacenes alm
			LEFT JOIN cupos_almacenes cup ON cup.id_almacen = alm.id  AND cup.id_temporada = @id_temporada
			LEFT JOIN cuotas_supervisor cuo on cuo.id_almacen = alm.id AND cuo.id_temporada = cup.id_temporada
			LEFT JOIN redenciones red on 
				red.id_almacen = cuo.id_almacen AND 
				red.temporada = cuo.id_temporada AND
				red.id_afiliado = cuo.id_afiliado
		group by
			alm.id
		
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_ventas_almacen;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas_almacen AS (
	
		select 
			per.id_temporada,
			ecu.id_periodo,
			afi.id_almacen,
			sup.id_supervisor,
			sum(ecu.venta) venta
		from 
			estado_cuenta ecu 
			inner join afiliado_almacen afi on afi.id_afiliado = ecu.id_afiliado
			inner join periodo per on per.id = ecu.id_periodo
			inner JOIN vendedores_supervisor sup ON sup.id_vendedor = ecu.id_afiliado AND sup.id_temporada = per.id_temporada
		where
			per.id_temporada = @id_temporada
		group by 
			ecu.id_periodo,
			sup.id_supervisor
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
			ven.id_supervisor,
			cuo.cuota_1,
			cuo.cuota_2,
			cuo.impactos,
			cuo.puede_redimir
		from 
			cuotas cuo
			INNER join vendedores_supervisor ven ON ven.id_vendedor = cuo.id_usuario AND ven.id_temporada = cuo.id_temporada
			INNER join periodo per on per.id_temporada = cuo.id_temporada
			INNER join afiliados afi on afi.id = ven.id_supervisor
			INNER JOIN afiliados afia ON afia.id = ven.id_vendedor
		WHERE afia.id_estatus = 4
		  	and per.id_temporada = @id_temporada
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
			ven.venta
		from 
			t_cuotas_almacen cuo
			left join t_ventas_almacen ven on 
				cuo.id_periodo = ven.id_periodo AND
				cuo.id_temporada = ven.id_temporada and
				cuo.id_supervisor = ven.id_supervisor
		GROUP BY id_afiliado,periodo		
		order by
			cuo.id_temporada,
			cuo.id_periodo
			
	);	

	
	DROP TEMPORARY TABLE IF EXISTS t_cumplimiento_por_supervisor;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_cumplimiento_por_supervisor AS (
			
		select 
			id_temporada,
			id_almacen id_distribuidora,
			id_afiliado id_supervisor,
			(cuota_1 + cuota_2)cuota,
			sum(venta) venta,
			round(((sum(venta) * 100) / (cuota_1 + cuota_2))) cumplimiento,
			if(round(((sum(venta) * 100) / (cuota_1 + cuota_2)))>= 95,1,0) cantidad
		from 
			t_consolidado
		group by
			id_temporada,
			id_almacen,
			id_afiliado
		
	);				
				
	
			select 
				env.temporada,
				alm.id id_distribuidora,
				alm.nombre distribuidora,
				ter.nombre territorio,
				eje.id id_ejecutivo,
				eje.nombre ejecutivo,
				ciu.nombre ciudad,
				env.cupos total_cupos,
				
				env.cupos_diamante,
				env.cumplen_diamante,
				env.entregas_diamante solicitudes_diamante,
				
				env.cupos_oro,
				env.cumplen_oro,
				env.entregas_oro solicitudes_oro,
				
				env.cupos_plata,	
				env.cumplen_plata,	
				env.entregas_plata solicitudes_plata,
				
				ifnull(ens.cupos,0) cupos_supervisor,
				sum(if(cus.cantidad=1,cus.cantidad,0)) cumplen_supervisor,	
				ifnull(ens.entregas,0) entregas_supervisor,
				
				env.otras_entregas,
					
				(cumplen_diamante+cumplen_oro+cumplen_plata+if(cus.cumplimiento >=95,COUNT(cus.cumplimiento),0)) tota_cumplen,
				(entregas_diamante+entregas_oro+entregas_plata+ifnull(ens.entregas,0)) total_solicitados,
				ROUND((entregas_diamante+entregas_oro+entregas_plata+ifnull(ens.entregas,0))*100/env.cupos) avance
			from 
				t_entregas_vendedores env
				left join t_entregas_supervisores ens on ens.id_distribuidora = env.id_distribuidora
				left join t_cumplimiento_por_supervisor cus on 
					cus.id_distribuidora = env.id_distribuidora 
					and env.id_temporada = cus.id_temporada		
				left join almacenes alm on alm.id = env.id_distribuidora
				left join afiliados eje on eje.id = alm.id_visitador
				left join ciudad ciu on ciu.id = alm.id_ciudad
				LEFT JOIN territorios ter ON ter.id = alm.id_territorio
				GROUP BY alm.id;					
				
				
	SELECT * FROM t_entregas_supervisores;
	SELECT * FROM t_cumplimiento_por_supervisor;
	
	SELECT * FROM t_ventas_almacen;
	SELECT * FROM t_cuotas_almacen;
	SELECT * FROM t_consolidado;
	
	DROP TEMPORARY TABLE t_ventas_almacen;
	DROP TEMPORARY TABLE t_cuotas_almacen;
	DROP TEMPORARY TABLE t_consolidado;
	
	DROP TEMPORARY TABLE t_entregas_vendedores;
	DROP TEMPORARY TABLE t_participantes_temporada;
	DROP TEMPORARY TABLE t_cumplimiento_por_supervisor;	
	
END//

call sp_reporte_avance_entregas_temporada(5);