DROP PROCEDURE IF EXISTS sp_ganadores_ciclo;

DELIMITER //

CREATE PROCEDURE sp_ganadores_ciclo(in id_temporada_p int, in id_almacen_p int)
LANGUAGE SQL
BEGIN

	set @id_temporada = id_temporada_p;
	
	drop temporary table if exists t_periodo;
	create temporary table if not exists t_periodo as (
		select 
			tem.id id_temporada,
			group_concat(per.nombre separator ' / ') bimestre
		from
			periodo per
			inner join temporada tem on tem.id = per.id_temporada
		where
			tem.id = @id_temporada
	);
	
	select
		alm.id id_distribuidora,
		alm.nombre distribuidora,
		ter.nombre territorio,
		eje.ID id_ejecutivo,
		eje.nombre ejecutivo,
	   afi.id id_vendedor,
	   afi.nombre vendedor,
	   id_temporada_p id_temporada,
	   tpe.bimestre bimestre,
	   tem.activo_redencion,
	   sum(ecu.puntos_venta) venta,
	   sum(ecu.puntos_impacto) impacto,
	   sum(ecu.puntos_venta + ecu.puntos_impacto) puntos,
	   count(distinct red.id) entregas_solicitadas,
	   sum(ecu.novedad) novedad,
	   max(ecu.id) id_ecu,
	   max(ecu.comentario) comentario,
	   count(distinct red.id) entregas_solicitadas
	from 
	   estado_cuenta ecu
	   inner join afiliados afi on afi.id = ecu.id_afiliado
	   INNER JOIN afiliado_almacen afia ON afia.id_afiliado = afi.ID
	   inner join almacenes alm on alm.id = afia.id_almacen
	   inner join afiliados eje on eje.id = alm.id_visitador
	   inner join periodo per on per.id = ecu.id_periodo
	   inner join temporada tem on tem.id = per.id_temporada
	   inner join t_periodo tpe on tpe.id_temporada = tem.id
	   left join redenciones red on red.id_afiliado = afia.id_afiliado and red.temporada = @id_temporada and afi.id_clasificacion = 6
	   LEFT JOIN territorios ter ON ter.id = alm.id_territorio
	where
	   (id_almacen_p = -1 or afia.id_almacen = id_almacen_p)
	   and tem.id = @id_temporada 
	group by
		alm.id,
	   ecu.id_afiliado,
	   tem.id
	order by
		alm.nombre,
	   novedad,
	   puntos desc,
	   per.id;
	   
	drop table t_periodo;

END//

call sp_ganadores_ciclo(11,-1);