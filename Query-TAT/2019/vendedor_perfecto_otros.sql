DROP PROCEDURE IF EXISTS sp_reporte_vendedor_perfecto_otros1;

DELIMITER //

CREATE PROCEDURE sp_reporte_vendedor_perfecto_otros1 ()
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
BEGIN
	
	DROP TEMPORARY TABLE IF EXISTS t_cuota_historica;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_cuota_historica AS (
	
		select
			alm.id id_distribuidora,
			alm.nombre distribuidora,
			ven.id id_vendedor,
			ven.nombre vendedor,
			count(distinct per.id) periodos,
			sum(ecu.venta) ventas_historico,
			round(sum(ecu.venta) / 4) promedio_historico,
			round(sum(ecu.venta) / 4) * 3 base_cuota,
			round((round(sum(ecu.venta) / 4) * 3) * 0.1) incremento,
			(round(sum(ecu.venta) / 4) * 3) + round((round(sum(ecu.venta) / 4) * 3) * 0.1) cuota
		from 	
			estado_cuenta ecu
			inner join periodo per on per.id = ecu.id_periodo
			inner join afiliados ven on ven.id = ecu.id_afiliado
			inner join almacenes alm on alm.id = ven.id_almacen
		where
			per.id in (10,11,12,13)
			and alm.incauca = 0
			and alm.vendedor_perfecto = 1
		group by
			alm.id,
			ven.id
		order by
			cuota
		
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_venta_cuatrimestre;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_venta_cuatrimestre AS (
	
		select
			alm.id id_distribuidora,
			alm.nombre distribuidora,
			ven.id id_vendedor,
			ven.nombre vendedor,
			sum(ecu.venta) venta
		from 	
			estado_cuenta ecu
			inner join periodo per on per.id = ecu.id_periodo
			inner join afiliados ven on ven.id = ecu.id_afiliado
			inner join almacenes alm on alm.id = ven.id_almacen
		where
			per.id in (14,15,16)
			and alm.incauca = 0
			and alm.vendedor_perfecto = 1
		group by
			alm.id,
			ven.id
		order by
			venta
		
	);
	
	select
		ven.id_distribuidora,
		ven.distribuidora,
		eje.nombre ejecutivo,
		ciu.nombre ciudad,
		ven.id_vendedor,
		ven.vendedor,
		case when cuo.cuota is null then 0 else 1 end tiene_historico,
		ifnull(cuo.cuota,0) cuota,
		ven.venta,
		case 
			when cuo.cuota is null then 0 
			else round((ven.venta * 100) / ifnull(cuo.cuota,1)) 
		end cumplimiento
	from
		t_cuota_historica cuo
		inner join almacenes alm on alm.id = cuo.id_distribuidora
		inner join afiliados eje on eje.id = alm.id_visitador	
		right join t_venta_cuatrimestre ven on ven.id_vendedor = cuo.id_vendedor
		left join ciudad ciu on ciu.id = alm.id_ciudad
	where
		cuo.periodos >= 3
	order by
		distribuidora,
		venta desc,
		cumplimiento desc;
	
	/*
	select * from t_cuota_historica;
	select * from t_venta_cuatrimestre;
	*/
	
	drop table t_cuota_historica;
	drop table t_venta_cuatrimestre;
	
END//
DELIMITER ;

call sp_reporte_vendedor_perfecto_otros1();