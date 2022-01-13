DROP PROCEDURE IF EXISTS sp_consolidado_entregas;

DELIMITER //

CREATE PROCEDURE sp_consolidado_entregas()
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
BEGIN

	DROP TEMPORARY TABLE IF EXISTS t_ultimos_seguimientos;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ultimos_seguimientos AS (
		
		select id_redencion,max(seg.id) ultimo_id 
		from 
			seguimiento_redencion seg
			inner join redenciones red on red.id = seg.id_redencion
		where
			red.id_almacen = id_almacen
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
	
	DROP TEMPORARY TABLE IF EXISTS t_consolidado;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_consolidado AS (
	
		select
			eje.id id_ejecutivo,
			eje.nombre ejecutivo,
			alm.id id_distribuidor,
			alm.nombre distribuidor,
			ter.nombre territorio,
			ciu.nombre ciudad,
			tem.id id_temporada,
			tem.nombre temporada,
			sum(case when esa.id_operacion = 1 then 1 else 0 end) Solicitado,
			sum(case when esa.id_operacion = 2 then 1 else 0 end) Confirmado,
			sum(case when esa.id_operacion = 5 then 1 else 0 end) Legalizado,
			sum(case when esa.id_operacion in (1,2,3,4,5,8) then 1 else 0 end) Total,
			sum(case when esa.id_operacion = 6 then 1 else 0 end) DenegadoCumplimiento,
			sum(case when esa.id_operacion = 7 then 1 else 0 end) DenegadoAutorizacion,
			sum(case when esa.id_operacion = 8 then 1 else 0 end) despachado
		from 
			t_estados_actuales esa
			inner join redenciones red on red.id = esa.id_redencion
			inner join temporada tem on tem.id = red.temporada
			inner join almacenes alm on alm.id = red.id_almacen
			inner join afiliados eje on eje.id = alm.id_visitador
			left join ciudad ciu on ciu.id = alm.id_ciudad
			INNER JOIN territorios ter ON ter.id = alm.id_territorio
		group by
			eje.id,
			eje.nombre,
			alm.id,
			alm.nombre,
			tem.id,
			tem.nombre
		order by
			eje.id,
			alm.id,
			tem.id
	
	);
	
	select
		id_ejecutivo,
		ejecutivo,
		id_distribuidor id_distribuidora,
		distribuidor distribuidora,
		territorio,
		ejecutivo,
		ciudad,
		id_temporada,
		temporada,
		Solicitado,
		despachado,
		Legalizado,
		Total,
		total - legalizado PorLegalizar
	from 
		t_consolidado;
	
	drop table t_consolidado;
	drop table t_estados_actuales;
	drop table t_ultimos_seguimientos;
	
END//

call sp_consolidado_entregas();