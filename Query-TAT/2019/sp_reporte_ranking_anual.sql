DROP PROCEDURE IF EXISTS sp_reporte_ranking_anual;
DELIMITER //
CREATE PROCEDURE sp_reporte_ranking_anual()
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
BEGIN

	select
		alm.id id_distribuidora,
		alm.nombre distribuidora,
		eje.id id_ejecutivo,
		eje.nombre ejecutivo,
	   afi.id id_vendedor,
	   afi.nombre vendedor,
	   sum(ecu.puntos_venta) venta,
	   sum(ecu.puntos_impacto) impacto,
	   sum(ecu.puntos_venta + ecu.puntos_impacto) puntos,
	   count(distinct nra.id) negacion,
	   count(distinct red.id) solicitadas
	from 
	   estado_cuenta ecu
	   inner join afiliados afi on afi.id = ecu.id_afiliado
	   inner join almacenes alm on alm.id = afi.id_almacen
	   inner join afiliados eje on eje.id = alm.id_visitador
	   inner join periodo per on per.id = ecu.id_periodo
	   inner join temporada tem on tem.id = per.id_temporada
	   left join negacion_ranking_anual nra on nra.id_afiliado = afi.id
	   left join redenciones red on red.temporada = 109 and afi.ID = red.id_afiliado
	where
		tem.historico = 0 
		and per.ranking = 1
	group by
		alm.nombre,
	   ecu.id_afiliado
	order by
		alm.nombre,
	   novedad,
	   negacion,
	   puntos desc;
	   
END//
DELIMITER ;

call sp_reporte_ranking_anual();