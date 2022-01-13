DROP PROCEDURE IF EXISTS sp_ranking_vendedores_4;
DELIMITER //
CREATE PROCEDURE sp_ranking_vendedores_4()
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
BEGIN
	
	DROP TEMPORARY TABLE IF EXISTS t_vendedores;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_vendedores AS (
	
		select
			alm.id id_distribuidora,
			alm.nombre distribuidora,
			alm.encuestas_periodo,
			eje.nombre ejecutivo,
			ciu.nombre ciudad,
		   afi.id id_vendedor,
		   afi.nombre vendedor,
		   10 id_temporada,
		   case when id_periodo in (22,23) then 'Octubre / Noviembre' else '' end bimestre,
		   sum(ecu.puntos_venta) venta,
		   sum(ecu.puntos_impacto) impacto,
		   sum(ecu.puntos_venta + ecu.puntos_impacto) puntos,
		   count(distinct red.id) entregas_solicitadas,
		   sum(ecu.novedad) novedad,
		   max(ecu.id) id_ecu,
		   max(ecu.comentario) comentario
		from 
		   estado_cuenta ecu
		   inner join afiliados afi on afi.id = ecu.id_afiliado
		   inner join almacenes alm on alm.id = afi.id_almacen
		   inner join afiliados eje on eje.id = alm.id_visitador
		   left join redenciones red on red.id_afiliado = afi.id and red.temporada = 10
		   left join ciudad ciu on ciu.id = alm.id_ciudad
		where
			ecu.id_periodo in (22,23)
		group by
		   ecu.id_afiliado,
		   case when id_periodo in (22,23) then 'Octubre / Noviembre' else '' end
		order by
			distribuidora,
		   puntos desc
	   
	);
	
	SET @rank=0;
	set @tmp_alm = 0;
	select 
		case 
			when @tmp_alm <> ven.id_distribuidora then @rank:=1
			else @rank:=@rank+1
		end ranking,
		@tmp_alm:= ven.id_distribuidora id_distribuidora,
		ven.distribuidora,
		ven.encuestas_periodo,
		ven.ejecutivo,
		ven.ciudad,
		ven.id_vendedor,
		ven.vendedor,
		ven.id_temporada,	
		ven.bimestre,
		ven.venta,
		ven.impacto,
		ven.puntos,
		case when @rank <= ven.encuestas_periodo then 1 else 0 end gana
	from 
		t_vendedores ven;
		
END//
DELIMITER ;

call sp_ranking_vendedores_4();