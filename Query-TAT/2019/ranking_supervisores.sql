DROP PROCEDURE IF EXISTS sp_ranking_supervisores;
DELIMITER //
CREATE PROCEDURE sp_ranking_supervisores()
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
			eje.ID id_ejecutivo,
			eje.nombre ejecutivo,
			ciu.nombre ciudad,
			sup.id id_supervisor,
			sup.nombre supervisor,
			sum(ecu.venta) ventas,
			cuo.cuota_1 + cuo.cuota_2 cuota
		from 	
			cuotas_supervisor cuo
			inner join almacenes alm on alm.id = cuo.id_almacen
			inner join afiliados eje on eje.id = alm.id_visitador
			inner join afiliados ven on ven.id_almacen = alm.id
			inner join temporada tem on tem.id = cuo.id_temporada	
			inner join periodo per on per.id_temporada = tem.id
			inner join afiliados sup on sup.id = cuo.id_afiliado
			left join estado_cuenta ecu on ecu.id_periodo = per.id 
												and ecu.id_afiliado = ven.id
			left join ciudad ciu on ciu.id = alm.id_ciudad
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
		cum.id_ejecutivo,
		cum.ejecutivo,
		cum.ciudad,
		cum.id_supervisor,
		cum.supervisor,
		cum.ventas,
		cum.cuota,
		ifnull(round(((ventas * 100) / cum.cuota),2),0) cumplimiento
	from 
		t_cumplimiento_almacen cum
	order by
		cum.id_temporada,
		id_distribuidora;
	
	drop table t_cumplimiento_almacen;

END//
DELIMITER ;

call sp_ranking_supervisores();