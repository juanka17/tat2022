DROP PROCEDURE IF EXISTS sp_redenciones_2022;

DELIMITER //

CREATE PROCEDURE sp_redenciones_2022()
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
			seg.id_usuario,
			seg.fecha_operacion
		from 
			seguimiento_redencion seg 
			inner join operaciones_redencion opr on opr.id = seg.id_operacion
		where
			seg.id in (select ultimo_id from t_ultimos_seguimientos)
	
	);
	
	select
		alm.nombre distribuidora,
		alm.id id_distribuidora,
		ter.nombre territorio,
		cli.id id_vendedor,
		cli.nombre vendedor,
		cli.CEDULA,
		eje.id id_ejecutivo,
		eje.nombre ejecutivo,
		ciu.nombre ciudad,
		case
		WHEN cla.id = 4 AND pre.id = 2468 then "Supervisor Lider"
		WHEN cla.id = 4 AND pre.id = 2606 then "Supervisor Lider"
		ELSE cla.nombre
		END clasificacion,
		per.nombre periodo,
		if(red.cambio_datos=0,'No','Si') alerta_cambio_datos,
		red.correo_envio,
		red.numero_envio,
		concat('GSK - ',red.id) folio,
		red.fecha fecha_solicitud,
		pre.nombre entrega,
		pre.marca,
		'Bonos' Categoria,
		eac.id_operacion,
		eac.estado,
		eac.fecha_operacion ultimo_cambio,
		reg.NOMBRE registro
	from
		redenciones red
		INNER JOIN t_estados_actuales eac on eac.id_redencion = red.id
		INNER JOIN premios pre on pre.id = red.id_premio
		INNER JOIN periodo per on per.id = red.id_periodo		
		INNER JOIN afiliados cli on cli.id = red.id_usuario		
		INNER JOIN clasificacion cla on cla.id = cli.id_clasificacion		
		INNER JOIN almacenes alm on alm.id = cli.id_almacen
		INNER JOIN afiliados eje on eje.id = alm.id_visitador
		LEFT JOIN ciudad ciu on ciu.id = alm.id_ciudad
		LEFT JOIN territorios ter ON ter.id = alm.id_territorio
		LEFT JOIN afiliados reg ON reg.ID = eac.id_usuario
	order by
		red.id;
			
	drop table t_ultimos_seguimientos;
	drop table t_estados_actuales;
	
END//

call sp_redenciones_2022();