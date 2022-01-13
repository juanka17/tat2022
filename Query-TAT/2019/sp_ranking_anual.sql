DROP PROCEDURE IF EXISTS sp_ranking_anual;
DELIMITER //
CREATE PROCEDURE sp_ranking_anual(id_almacen_p int)
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
BEGIN

	select
	   afi.id id_vendedor,
	   afi.nombre vendedor,
	   sum(ecu.puntos_venta) venta,
	   sum(ecu.puntos_impacto) impacto,
	   sum(ecu.puntos_venta + ecu.puntos_impacto) puntos,
	   count(distinct nra.id) negacion,
	   count(distinct red.id) solicitadas
	FROM 
	   estado_cuenta ecu
	   inner join afiliados afi on afi.id = ecu.id_afiliado
	   INNER JOIN afiliado_almacen afia ON afia.id_afiliado = afi.ID
	   inner join almacenes alm on alm.id = afia.id_almacen
	   inner join periodo per on per.id = ecu.id_periodo
	   inner join temporada tem on tem.id = per.id_temporada
	   left join negacion_ranking_anual nra on nra.id_afiliado = afi.id
	   left join redenciones red on red.temporada = 109 and afi.ID = red.id_afiliado
	where
	   afia.id_almacen = id_almacen_p
	   and tem.historico = 0
	   and per.ranking = 1
	group by
	   ecu.id_afiliado
	order by
	   novedad,
	   negacion,
	   puntos desc;
	   
END//
DELIMITER ;

call sp_ranking_anual(3880);