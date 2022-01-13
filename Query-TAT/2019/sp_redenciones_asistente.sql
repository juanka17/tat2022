DROP PROCEDURE IF EXISTS sp_redenciones_asistente;

DELIMITER //

CREATE PROCEDURE sp_redenciones_asistente(IN id_asistente_busqueda INT)
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
BEGIN
	
	DROP TEMPORARY TABLE IF EXISTS t_ultimos_seguimientos;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ultimos_seguimientos AS (
		
		select seg.id_redencion,max(seg.id) ultimo_id 
		from 
			seguimiento_redencion seg
			inner join redenciones red on red.id = seg.id_redencion
			inner join almacenes alm on alm.id = red.id_almacen
			inner join afiliados ger on ger.id = alm.id_visitador
			inner join afiliados asi on asi.id = ger.ID_ASISTENTE and asi.ID = id_asistente_busqueda 
		group by 
			id_redencion
	
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
	
	select
		tem.id id_temporada,
		tem.nombre temporada,
		alm.nombre almacen,
		alm.id id_almacen,
		vis.id id_visitador,
		vis.nombre visitador,
		red.id id_redencion,
		red.fecha_redencion,
		pre.nombre premio,
		eac.id_operacion,
		eac.estado,
		eac.fecha_operacion ultimo_cambio
	from
		redenciones red
		inner join t_estados_actuales eac on eac.id_redencion = red.id
		inner join premios pre on pre.id = red.id_premio
		inner join almacenes alm on alm.id = red.id_almacen
		inner join afiliados vis on vis.id = red.id_afiliado
		inner join temporada tem on tem.id = red.temporada
	order by
		red.id;
	
	drop table t_ultimos_seguimientos;
	drop table t_estados_actuales;
	
END//

call sp_redenciones_asistente(5606);