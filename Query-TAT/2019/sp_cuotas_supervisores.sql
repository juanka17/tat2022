DROP PROCEDURE IF EXISTS sp_cuotas_supervisores;
DELIMITER //
CREATE PROCEDURE sp_cuotas_supervisores(IN tmp_almacen INT)
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
BEGIN
	
	DROP TEMPORARY TABLE IF EXISTS t_cumplimiento_almacen;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_cumplimiento_almacen AS (
	
		select
			tem.id id_temporada,
			tem.nombre temporada,
			alm.id id_distribuidora,
			alm.nombre distribuidora,
			sup.id id_supervisor,
			sup.nombre supervisor,
			sum(ecu.venta) ventas,
			cuo.cuota_1,
			cuo.cuota_2,
			cuo.cuota_1 + cuo.cuota_2 cuota
		from 	
			cuotas_supervisor cuo
			inner join almacenes alm on alm.id = cuo.id_almacen
			inner join afiliados ven on ven.id_almacen = alm.id
			inner join temporada tem on tem.id = cuo.id_temporada	
			inner join periodo per on per.id_temporada = tem.id
			inner join afiliados sup on sup.id = cuo.id_afiliado
			left join estado_cuenta ecu on ecu.id_periodo = per.id 
												and ecu.id_afiliado = ven.id
		where 
			alm.id = tmp_almacen
		group by
			tem.id,
			alm.id,
			sup.id
			
	);
	
	select 
		cum.id_temporada,
		cum.temporada,
		cum.id_distribuidora,
		cum.distribuidora,
		cum.id_supervisor,
		cum.supervisor,
		cum.ventas,
		cum.cuota,
		ifnull(round(((ventas * 100) / cum.cuota),2),0) cumplimiento,
		(
			select count(distinct red.id) 
			from redenciones red 
			where red.temporada = cum.id_temporada and red.id_afiliado = cum.id_supervisor
		) redenciones_temporada
	from 
		t_cumplimiento_almacen cum
	order by
		id_distribuidora;
	
	drop table t_cumplimiento_almacen;
	
END//
DELIMITER ;

call sp_cuotas_supervisores(87);