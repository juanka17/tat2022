DROP PROCEDURE IF EXISTS sp_cuotas_vendedores;
DELIMITER //
CREATE PROCEDURE sp_cuotas_vendedores(IN tmp_almacen INT, IN tmp_periodo INT, IN tmp_estado INT)
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
BEGIN
	SET @id_periodo_temporal = tmp_periodo;
	SET @id_almacen_temporal = tmp_almacen;
	SET @estado_variante = tmp_estado;
	
	DROP TEMPORARY TABLE IF EXISTS t_ventas_almacen;
		CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas_almacen AS (
		
			SELECT 
				alm.id id_almacen,
				alm.nombre almacen,
				case
					when @id_periodo_temporal IN (14,15,16,17,18) then ifnull(sum(case when ve.id_periodo IN (9,10,11) then ve.valor END),1)
					when @id_periodo_temporal = 19 then ifnull(sum(case when ve.id_periodo IN (14,15,16) then ve.valor END),1)
					when @id_periodo_temporal = 20 then ifnull(sum(case when ve.id_periodo IN (15,16,17) then ve.valor END),1)
				END venta_almacen,
				case
					when @id_periodo_temporal = 20 then ifnull(sum(case when ve.id_periodo IN (15,16,17) then ve.impactos END),1)
				END impactos_almacen
			FROM 
				ventas ve
				INNER JOIN afiliados af ON ve.id_vendedor=af.ID
				INNER JOIN almacenes alm ON alm.id=af.ID_ALMACEN AND alm.id=@id_almacen_temporal
			WHERE
				af.ID_ESTATUS != 2
			GROUP BY 
				alm.id
			
			);
						
		DROP TEMPORARY TABLE IF EXISTS t_ventas_vendedores;
		CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas_vendedores AS (
		
			SELECT 
				alm.id id_almacen,
				af.ID id_vendedor,
				af.nombre vendedor,
				case
					when @id_periodo_temporal IN (14,15,16,17,18) then ifnull(sum(case when ve.id_periodo IN (9,10,11) then ve.valor END),1)
					when @id_periodo_temporal = 19 then ifnull(sum(case when ve.id_periodo IN (14,15,16) then ve.valor END),1)
					when @id_periodo_temporal = 20 then ifnull(sum(case when ve.id_periodo IN (15,16,17) then ve.valor END),1)
				END venta_ultimo_q_vendedor,
				case
					when @id_periodo_temporal IN (14,15,16,17,18) then ifnull(sum(case when ve.id_periodo IN (9,10,11) then round(ve.valor/3) END),1)
					when @id_periodo_temporal = 19 then ifnull(sum(case when ve.id_periodo IN (14,15,16) then round(ve.valor /3) END),1)
					when @id_periodo_temporal = 20 then ifnull(sum(case when ve.id_periodo IN (15,16,17) then round(ve.valor/3) END),1)
				END venta_vendedor,
				case					
					when @id_periodo_temporal = 20 then ifnull(sum(case when ve.id_periodo IN (15,16,17) then round(ve.impactos/3) END),1)
				END impactos_vendedor		
				
			FROM 
				ventas ve
				left JOIN afiliados af ON ve.id_vendedor=af.ID
				inner JOIN almacenes alm ON alm.id=af.ID_ALMACEN AND alm.id=@id_almacen_temporal
			WHERE
				af.ID_ESTATUS != 2
			GROUP BY 
				af.ID
		);

		DROP TEMPORARY TABLE IF EXISTS t_participacion_vendedores;
		CREATE TEMPORARY TABLE IF NOT EXISTS t_participacion_vendedores AS (
		
			SELECT 
				@id_periodo_temporal id_periodo,
				tv.id_almacen,
				almacen,				
				tv.id_vendedor,
				vendedor,
				venta_almacen,
				venta_ultimo_q_vendedor,
				venta_vendedor,
				alm.margen,			
				if(cuo.cuota=0,1,cuo.cuota) cuota_almacen,
				cuo.cuota * (alm.margen/100) cuota_costo,
				cuo.cuota_aumentada * (alm.margen/100) cuota_costo_modificada,
				cuo.cuota_aumentada cuota_modificada,
				ROUND(((venta_vendedor*alm.margen)/100)) por_venta,
				case 					
					when esp.id_periodo IN (14,15,16,17,18,19,20) then esp.cuota
					when venta_vendedor < 500000 then 500000 
					ELSE venta_vendedor
				end cuota_vendedor,
				0 cuota_supervisor,
				tv.impactos_vendedor,
				ifnull(esp.estado,1) estado
			FROM 
				t_ventas_vendedores tv
				INNER JOIN t_ventas_almacen ta ON tv.id_almacen=ta.id_almacen
				INNER JOIN almacenes alm ON alm.id = ta.id_almacen
				left JOIN cuotas_especiales_2022 esp ON esp.id_vendedor = tv.id_vendedor AND esp.id_periodo = @id_periodo_temporal 
				LEFT JOIN impactos imp ON imp.id_afiliado = esp.id_vendedor AND imp.id_periodo = esp.id_periodo
				INNER JOIN cuotas_almacen cuo ON cuo.id_almacen = tv.id_almacen AND cuo.id_periodo = @id_periodo_temporal 
		);

	DROP TEMPORARY TABLE IF EXISTS t_total_cuotas_vendedores;
		CREATE TEMPORARY TABLE IF NOT EXISTS t_total_cuotas_vendedores AS (
		SELECT
			par.id_periodo,
			par.id_almacen,
			par.almacen,	
			ven.id_supervisor,	
			v.cedula,
			v.cod_formas,
			par.id_vendedor,
			par.vendedor,
			par.venta_almacen,
			par.venta_ultimo_q_vendedor,
			par.venta_vendedor,			
			par.cuota_almacen,
			(par.cuota_almacen-par.cuota_costo) cuota_costo,	
			(par.cuota_modificada -cuota_costo_modificada) cuota_costo_modificada,					
			par.margen,
			par.por_venta,
			par.cuota_vendedor,
			ROUND((par.cuota_vendedor/par.cuota_almacen)*100,2) porcentaje_participacion,
			par.cuota_modificada,			
			par.cuota_vendedor cuota_supervisor,
			impactos_vendedor cuota_impactos,
			estado
		FROM 
			t_participacion_vendedores par
			left JOIN vendedores_supervisor ven ON ven.id_vendedor = par.id_vendedor AND ven.id_periodo = @id_periodo_temporal
			left JOIN afiliados v ON v.ID = ven.id_vendedor
		WHERE 
			estado = 1
		);
		
		SELECT 
			tot.*,
			ai.nombre supervisor,
			cuota_vendedor
		FROM 
			t_total_cuotas_vendedores tot
		left JOIN afiliados ai ON ai.ID = tot.id_supervisor
		ORDER BY ai.nombre,id_supervisor;
		
			
		if@estado_variante = 0 then
			insert into cuotas_especiales_2022(id_vendedor,id_periodo,cuota)
			select
				id_vendedor,
				@id_periodo_temporal id_periodo,
				cuota_vendedor
			from
				t_total_cuotas_vendedores
			where
				id_vendedor NOT IN (SELECT id_vendedor FROM cuotas_especiales_2022 WHERE id_periodo = @id_periodo_temporal);
		else		
				insert into cuotas_especiales_2022(id_vendedor,id_periodo,cuota)
				select
					id_vendedor,
					@id_periodo_temporal id_periodo,
					ROUND((cuota_modificada*porcentaje_participacion)/100)
				from
					t_total_cuotas_vendedores
				where
					id_vendedor NOT IN (SELECT id_vendedor FROM cuotas_especiales_2022 WHERE id_periodo = @id_periodo_temporal);
		END IF;		
		UPDATE cuotas_almacen 
			SET 
				cuota = (select SUM(cuota_vendedor) FROM t_total_cuotas_vendedores),
				impactos = IFNULL((select SUM(cuota_impactos) FROM t_total_cuotas_vendedores),0),
				cuota_aumentada = (select SUM(cuota_vendedor) FROM t_total_cuotas_vendedores),
				estado = 1
				WHERE id_almacen = @id_almacen_temporal AND id_periodo = @id_periodo_temporal
				AND estado = 0 ;
			
		#SELECT * FROM t_ventas_almacen;
		#SELECT * FROM t_ventas_vendedores;	
		#SELECT * FROM t_participacion_vendedores;
		
		#DROP TEMPORARY TABLE t_participacion_vendedores;
		DROP TEMPORARY TABLE t_ventas_almacen;
		DROP TEMPORARY TABLE t_ventas_vendedores;


END//
DELIMITER ;

call sp_cuotas_vendedores(3924,20,0);