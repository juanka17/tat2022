DROP PROCEDURE IF EXISTS sp_informacion_redenciones_almacen;

DELIMITER //

CREATE PROCEDURE sp_informacion_redenciones_almacen (IN id_almacen_busqueda INT)
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
BEGIN
	
	DROP TEMPORARY TABLE IF EXISTS t_ultimos_seguimientos;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ultimos_seguimientos AS (
		
		select id_redencion,max(id) ultimo_id 
		from seguimiento_redencion 
		where id_redencion in (select id from redenciones where id_almacen = id_almacen_busqueda)
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
		pre.id,
		pre.nombre premio,
		afi.nombre empleado,		
		case
		WHEN cla.id = 4 AND pre.id = 2468 then "Supervisor Lider"
		WHEN cla.id = 4 AND pre.id = 2606 then "Supervisor Lider"
		WHEN cla.id = 4 AND pre.id = 2651 then "Supervisor Lider"
		ELSE cla.nombre
		END clasificacion,
		eac.id_operacion,
		eac.estado,
		eac.fecha_operacion ultimo_cambio,
		tem.id id_temporada,
		tem.nombre temporada
	from
		redenciones red
		inner join t_estados_actuales eac on eac.id_redencion = red.id
		inner join premios pre on pre.id = red.id_premio
		inner join afiliados afi on afi.id = red.id_afiliado
		inner join clasificacion cla on cla.id = afi.id_clasificacion
		inner join temporada tem on tem.id = red.temporada
	order by
		red.id;
	
	drop table t_ultimos_seguimientos;
	drop table t_estados_actuales;
	
END//

call sp_informacion_redenciones_almacen(90);