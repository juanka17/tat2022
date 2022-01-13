DROP PROCEDURE IF EXISTS sp_reporte_vendedor_perfecto_global_incauca;

DELIMITER //

CREATE PROCEDURE sp_reporte_vendedor_perfecto_global_incauca (IN tmp_almacen INT)
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
BEGIN

	set @tmp_almacen = tmp_almacen; 	
 	
	
	DROP TEMPORARY TABLE IF EXISTS t_cuota_historica;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_cuota_historica AS (
	
		select
			pvp.id id_ciclo,
			pvp.nombre ciclo,
			ecu.id_periodo,
			alm.id id_distribuidora,
			alm.nombre distribuidora,
			ven.id id_vendedor,
			ven.nombre vendedor,
			count(distinct per.id) periodos,
			sum(ecu.venta) ventas_historico,
			round(sum(ecu.venta) / pvp.divisor) promedio_historico,
			round(sum(ecu.venta) / pvp.divisor) * pvp.multiplicador base_cuota,
			round((round(sum(ecu.venta) / pvp.divisor) * pvp.multiplicador) * 0.1) incremento,
			(round(sum(ecu.venta) / pvp.divisor) * pvp.multiplicador) + round((round(sum(ecu.venta) / pvp.divisor) * pvp.multiplicador) * 0.1) cuota
		from 	
			estado_cuenta ecu
			inner join afiliados ven on ven.id = ecu.id_afiliado
			inner join almacenes alm on alm.id = ven.id_almacen
			inner join periodo per on per.id = ecu.id_periodo
			inner join periodos_vendedor_perfecto pvp on 
				( 
					(per.id < 12 and pvp.id = 1 )
					or (
						per.id >= 12
						and POSITION(per.id IN pvp.id_periodo_cuotas) > 0
					)
				)
				and pvp.incauca = alm.incauca
		where
			per.id >= 8
			and (alm.id = @tmp_almacen or (@tmp_almacen = -1 and alm.incauca = 1 ) )
		group by
			pvp.id,
			alm.id,
			ven.id
		order by
			id_ciclo,
			cuota desc
			
		
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_venta_cuatrimestre;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_venta_cuatrimestre AS (
	
		select
			pvp.id id_ciclo,
			pvp.nombre ciclo,
			alm.id id_distribuidora,
			alm.nombre distribuidora,
			ven.id id_vendedor,
			ven.nombre vendedor,
			sum(ecu.venta) venta
		from 	
			estado_cuenta ecu
			inner join afiliados ven on ven.id = ecu.id_afiliado
			inner join almacenes alm on alm.id = ven.id_almacen
			inner join periodo per on per.id = ecu.id_periodo
			inner join periodos_vendedor_perfecto pvp on
				( 
					(per.id < 12 and pvp.id = 1 and POSITION(per.id IN pvp.id_periodo_ventas) > 0 )
					or (
						per.id >= 12
						and POSITION(per.id IN pvp.id_periodo_ventas) > 0
					)
				)
				and pvp.incauca = alm.incauca
		where
			per.id >= 8
			and (alm.id = @tmp_almacen or (@tmp_almacen = -1 and alm.incauca = 1 ) )
		group by
			pvp.id,
			alm.id,
			ven.id
		order by
			pvp.id,
			venta
		
	);		

		DROP TEMPORARY TABLE IF EXISTS t_reporte_vendedor_perfecto;
		CREATE TEMPORARY TABLE IF NOT EXISTS t_reporte_vendedor_perfecto AS (	
	
			select
				ven.id_ciclo,
				ven.ciclo,
				pvp.id_temporada,
				pvp.activo,
				ven.id_distribuidora,
				ven.distribuidora,
				eje.ID id_ejecutivo,
				eje.nombre ejecutivo,
				ciu.nombre ciudad,
				ven.id_vendedor,
				ven.vendedor,
			   case when cuo.cuota = 6800000 then 0 else 1 end tiene_historico,
			   cuo.incremento,
				cuo.cuota cuota,
				ven.venta,
				case 
					when cuo.cuota is null then 6800000 
					else round((ven.venta * 100) / ifnull(cuo.cuota,6800000)) 
				end cumplimiento,
				(select count(id) from redenciones where id_afiliado = ven.id_vendedor and temporada = pvp.id_temporada) entregas,
				ifnull(dvp.id,0) id_denegacion,
				ifnull(dvp.razon_denegacion,'') razon
			from
				t_cuota_historica cuo
				inner join almacenes alm on alm.id = cuo.id_distribuidora
				inner join afiliados eje on eje.id = alm.id_visitador
				inner join periodos_vendedor_perfecto pvp on pvp.id = cuo.id_ciclo			
				inner join t_venta_cuatrimestre ven on 
					ven.id_vendedor = cuo.id_vendedor
					and ven.id_ciclo = cuo.id_ciclo
				left join ciudad ciu on ciu.id = alm.id_ciudad
				left join denegaciones_vendedor_perfecto dvp on 
					dvp.id_afiliado = ven.id_vendedor 
					and ciclo_vendedor_perfecto = pvp.id_temporada
			order by
				ven.id_ciclo desc,			
				distribuidora,
				ifnull(dvp.id,0),
				cumplimiento desc,
				venta desc
	);		
	
	
	
	select
	distribuidora,
	id_distribuidora,
	ejecutivo,
	ciudad,
	id_ciclo,
	ciclo,
	if(cumplimiento >=100,"si","no") cumplimient,
	if(entregas = 0,"no","si") solicitaron
	from t_reporte_vendedor_perfecto
	where id_ciclo IN(14,15,17)
	group by id_distribuidora,id_ciclo
	order by id_distribuidora, cumplimiento desc;
	
	
			
	
	select * from t_reporte_vendedor_perfecto;
	/*
	select * from t_cuota_historica;
	select * from t_venta_cuatrimestre;
	*/	
	
	drop table t_cuota_historica;
	drop table t_venta_cuatrimestre;
	drop table t_reporte_vendedor_perfecto;	
	
end//
DELIMITER ;

call sp_reporte_vendedor_perfecto_global_incauca(-1);
