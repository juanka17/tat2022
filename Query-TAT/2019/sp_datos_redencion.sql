DROP PROCEDURE IF EXISTS sp_datos_redencion;

DELIMITER //

CREATE PROCEDURE sp_datos_redencion(IN id_redencion_busqueda INT)
LANGUAGE SQL
BEGIN
	
	DROP TEMPORARY TABLE IF EXISTS t_ultimos_seguimientos;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ultimos_seguimientos AS (
		
		select id_redencion,max(id) ultimo_id 
		from seguimiento_redencion 
		where id_redencion = id_redencion_busqueda
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
	
	select
		red.id id_redencion,
		red.fecha_redencion,
		pre.nombre premio,
		eac.id_operacion,
		eac.estado,
		eac.fecha_operacion ultimo_cambio,
		tem.nombre temporada
	from
		redenciones red
		inner join t_estados_actuales eac on eac.id_redencion = red.id
		inner join premios pre on pre.id = red.id_premio
		inner join temporada tem on tem.id = red.temporada
	order by
		red.id;
	
	drop table t_ultimos_seguimientos;
	drop table t_estados_actuales;
	
END//

call sp_datos_redencion(69);