DROP PROCEDURE IF EXISTS sp_ganadores_ciclo;

DELIMITER //

CREATE PROCEDURE sp_ganadores_ciclo(in id_temporada_p int, in id_almacen_p int)
LANGUAGE SQL
BEGIN
	
	set @id_temporada = id_temporada_p;	
	set @rank=0;
	SET @id_categoria_anterior = 0;
	
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
	
	drop temporary table if exists t_total;
	create temporary table if not exists t_total as (
	select
		alm.id id_distribuidora,
		alm.nombre distribuidora,
		ter.nombre territorio,
		eje.ID id_ejecutivo,
		eje.nombre ejecutivo,
	   afi.id id_vendedor,
	   afi.nombre vendedor,	   
	   cat.id id_categoria,
	   cat.nombre categoria,	   
	   id_temporada_p id_temporada,
	   tpe.bimestre bimestre,
	   sum(ecu.puntos_venta) venta,
	   sum(ecu.puntos_impacto) impacto,
	   sum(ecu.puntos_venta + ecu.puntos_impacto) puntos,
	   count(distinct red.id) entregas_solicitadas,
	   sum(ecu.novedad) novedad,
	   max(ecu.comentario) comentario,
	   ecu.id id_ecu
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
	   LEFT JOIN nueva_clasificacion_usuario nue ON nue.id_afiliado = afia.id_afiliado AND nue.id_temporada = @id_temporada
	   INNER JOIN categorias cat ON cat.id = nue.ID_CATEGORIA		  
	where
	   (id_almacen_p = -1 or afia.id_almacen = id_almacen_p)
	   and tem.id = @id_temporada 
	group by
		alm.id,
	   ecu.id_afiliado,
	   tem.id
	order by
		tem.id,
		alm.id,
		cat.id,		
	   novedad,
	   puntos DESC
	   
	);	
	
	drop temporary table if exists t_total_ranking;
	create temporary table if not exists t_total_ranking as (
	
	 
    SELECT *,
        @id_categoria_anterior id_categoria_anterior,
      case
         when id_categoria != @id_categoria_anterior then @rank:= 1 ELSE @rank:=@rank+1
        END ranking,
        @id_categoria_anterior:=id_categoria
    FROM t_total
    
	);
	
	SELECT 	
		id_distribuidora,
		distribuidora,
		territorio,
		id_ejecutivo,
		ejecutivo,
		id_vendedor,
		vendedor,			
		id_categoria,		
		categoria,
		ranking,
		id_temporada,
		bimestre		,
		venta,
		impacto,
		puntos,
		entregas_solicitadas,
		novedad,
		comentario,
		id_ecu
	 FROM t_total_ranking;
	
	DROP TABLE t_total;
	DROP table t_periodo;
	DROP table t_total_ranking;

END//

call sp_ganadores_ciclo(6,3918);