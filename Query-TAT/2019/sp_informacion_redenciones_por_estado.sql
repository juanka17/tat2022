DROP PROCEDURE IF EXISTS sp_informacion_redenciones_por_estado;

DELIMITER //

CREATE PROCEDURE sp_informacion_redenciones_por_estado (IN id_estado INT)
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
BEGIN
	
	DROP TEMPORARY TABLE IF EXISTS t_ultimos_seguimientos;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ultimos_seguimientos AS (
		
		select id_redencion,max(id) ultimo_id 
		from seguimiento_redencion
		group by id_redencion
	
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_estados_actuales;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_estados_actuales AS (
		
		select
			seg.id_redencion,
			opr.id id_operacion,
			opr.nombre estado,
			seg.fecha_operacion
		from 
			seguimiento_redencion seg 
			inner join operaciones_redencion opr on opr.id = seg.id_operacion
		where
			seg.id in (select ultimo_id from t_ultimos_seguimientos)
	
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_redenciones_estado;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_redenciones_estado AS (
	
		select
			alm.id id_almacen,
			alm.nombre almacen,
			vis.id id_visitador,
			vis.nombre visitador,
			red.id id_redencion,
			red.fecha_redencion,
			pre.nombre premio,
			eac.id_operacion,
			eac.estado,
			eac.fecha_operacion ultimo_cambio,
			tem.nombre temporada,
			tem.id id_temporada
		from
			redenciones red
			inner join t_estados_actuales eac on eac.id_redencion = red.id
			inner join almacenes alm on alm.id = red.id_almacen
			inner join afiliados vis on vis.id = alm.id_visitador
			inner join premios pre on pre.id = red.id_premio
			inner join temporada tem on tem.id = red.temporada
		where
			eac.id_operacion = id_estado
		order by
			red.id
		
	);
	
	select * from t_redenciones_estado;
	
	drop table t_ultimos_seguimientos;
	drop table t_estados_actuales;
	
END//

call sp_informacion_redenciones_por_estado(1);