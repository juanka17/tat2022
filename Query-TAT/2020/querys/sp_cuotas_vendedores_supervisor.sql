DROP PROCEDURE IF EXISTS sp_cuotas_vendedores_supervisor;
DELIMITER //
CREATE PROCEDURE sp_cuotas_vendedores_supervisor(IN tmp_almacen INT)
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
BEGIN

	SET @id_almacen = tmp_almacen;
	
	DROP TEMPORARY TABLE IF EXISTS t_ventas_almacen;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas_almacen AS (
	
		SELECT
			per.id_temporada,
			ecu.id_periodo,
			afi.id_almacen,
			sup.id_supervisor,
			SUM(ecu.venta) venta
		FROM 
			estado_cuenta ecu 
			INNER JOIN afiliado_almacen afi ON afi.id_afiliado = ecu.id_afiliado
			INNER JOIN periodo per ON per.id = ecu.id_periodo
			INNER JOIN vendedores_supervisor sup ON sup.id_vendedor = ecu.id_afiliado AND sup.id_temporada = per.id_temporada
		WHERE afi.id_almacen = @id_almacen
		GROUP BY 
			ecu.id_periodo,
			sup.id_supervisor
		ORDER BY
			per.id_temporada,
			per.id
	
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_cuotas_almacen;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_cuotas_almacen AS (
	
			SELECT
			per.id_temporada,
			per.id id_periodo,
			per.nombre periodo,
			afi.id_almacen,
			afi.id id_afiliado,
			afi.nombre afiliado,
			ven.id_supervisor,
			cuo.cuota_1,
			cuo.cuota_2,
			cuo.impactos,
			cuo.puede_redimir
		FROM
			cuotas cuo
			INNER JOIN vendedores_supervisor ven ON ven.id_vendedor = cuo.id_usuario AND ven.id_temporada = cuo.id_temporada
			INNER JOIN periodo per ON per.id_temporada = cuo.id_temporada
			INNER JOIN afiliados afi ON afi.id = ven.id_supervisor
			INNER JOIN afiliado_almacen af ON af.id_afiliado = ven.id_vendedor
			INNER JOIN afiliados afia ON afia.id = af.id_afiliado
		  WHERE afia.id_estatus = 4 AND af.id_almacen = @id_almacen
		ORDER BY
			per.id_temporada,
			per.id
		
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_consolidado;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_consolidado AS (
	
		SELECT
			cuo.id_temporada,
			cuo.id_periodo,
			cuo.periodo,
			cuo.id_almacen,
			cuo.id_afiliado,
			cuo.afiliado,
			SUM(cuo.impactos)impactos,
			SUM(cuo.cuota_1)cuota_1,
			SUM(cuo.cuota_2)cuota_2,
			cuo.puede_redimir,
			ven.venta
		FROM 
			t_cuotas_almacen cuo
			LEFT JOIN t_ventas_almacen ven ON 
				cuo.id_periodo = ven.id_periodo AND
				cuo.id_temporada = ven.id_temporada AND
				cuo.id_supervisor = ven.id_supervisor
		WHERE 
			cuo.id_almacen = @id_almacen
		GROUP BY 
			cuo.id_afiliado,
			cuo.id_periodo
		ORDER BY
			cuo.id_temporada,
			cuo.id_periodo
			
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_agrupado;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_agrupado AS (
	
		SELECT
			id_temporada,
			id_almacen,
			id_afiliado,
			impactos,
			GROUP_CONCAT(periodo ORDER BY id_periodo SEPARATOR '|') AS 'periodos',
			SUM(case when id_periodo % 2 = 1 then cuota_1 ELSE 0 END) cuota_1,
			SUM(case when id_periodo % 2 = 0 then cuota_2 ELSE 0 END) cuota_2,
			SUM(case when id_periodo % 2 = 1 then venta ELSE 0 END) venta_1,
			SUM(case when id_periodo % 2 = 0 then venta ELSE 0 END) venta_2,
			MIN(puede_redimir) puede_redimir
		FROM
			t_consolidado
			WHERE id_almacen = @id_almacen
		GROUP BY
			id_temporada,
			id_almacen,
			id_afiliado
		
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_total;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_total AS (
	
		SELECT
			id_temporada,
			id_almacen,
			id_afiliado,
			impactos,
			periodos,
			cuota_1,
			venta_1,
			ROUND((venta_1 * 100) / cuota_1,2) cumplimiento_1,
			venta_2,
			cuota_2,
			ROUND((venta_2 * 100) / cuota_2,2) cumplimiento_2,
			cuota_1 + cuota_2 cuota_bimestre,
			venta_1 + venta_2 venta_bimestre,
			ROUND(((venta_1 + venta_2) * 100) / (cuota_1 + cuota_2),2) cumplimiento_bimestre,
			case				
				when id_temporada > 5 then 0
			ELSE 1
			END puede_redimir
		FROM
			t_agrupado
			
			
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_total_rankeada;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_total_rankeada AS (
	
		SELECT		
		tem.nombre temporada,
		afi.id id_supervisor,
		afi.nombre supervisor,
		afi.id_categoria,
		cat.nombre categoria,
		(
			SELECT COUNT(DISTINCT red.id) 
			FROM redenciones red 
			INNER JOIN premios pre ON pre.id = red.id_premio
			WHERE red.temporada = tot.id_temporada
			 AND red.id_afiliado = tot.id_afiliado 
			 AND pre.navidad != 1
		) redenciones_temporada,
		tot.*
	FROM
		t_total tot
		INNER JOIN afiliados afi ON afi.id = tot.id_afiliado
		INNER JOIN temporada tem ON tem.id = tot.id_temporada
		INNER JOIN categorias cat ON cat.id = afi.id_categoria
		ORDER BY 7 DESC,19 desc,20 desc
		
	);
	
	SET @rank=0;
	SET @tmp_alm = 0;
	SELECT
		case 
			when @tmp_alm != tot.id_temporada then @rank:=1
			ELSE @rank:=@rank+1
		END ranking,
		@tmp_alm:= tot.id_temporada tempo,
		tot.*
	FROM 
		t_total_rankeada tot;
	
	SELECT * FROM t_total_rankeada;
	select * from t_agrupado;
	select * from t_consolidado;
	select * from t_ventas_almacen;
	select * from t_cuotas_almacen;
	
	DROP TABLE t_total_rankeada;
	drop table t_cuotas_almacen;
	drop table t_ventas_almacen;
	drop table t_consolidado;
	drop table t_agrupado;
	drop table t_total;

END//
DELIMITER ;

CALL sp_cuotas_vendedores_supervisor(70);