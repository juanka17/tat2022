DROP PROCEDURE IF EXISTS sp_indicadores_crecimiento_nuevo;

DELIMITER //

CREATE PROCEDURE sp_indicadores_crecimiento_nuevo(in id_portafolio_p int, in id_categoria_p int,IN id_marca_p INT,IN id_sub_marca_p INT,IN id_producto_p INT,IN id_territorio_p INT, IN id_representante_p INT,IN id_madre_p INT, in id_distribuidora_p INT, IN hash_consulta_p VARCHAR(50))
LANGUAGE SQL
DETERMINISTIC
SQL SECURITY DEFINER
BEGIN
	
	DROP TEMPORARY TABLE IF EXISTS t_ventas_2021;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas_2021 AS (
		
	SELECT
			vco.id_territorio,
			vco.id_periodo,
			SUM(venta) ventas
		FROM 
			vista_general_ventas vco
		WHERE
			(id_portafolio_p = 0 || id_portafolio = id_portafolio_p)
			AND (id_categoria_p = 0 || id_categoria_producto = id_categoria_p)
			AND (id_marca_p = 0 || id_marca = id_marca_p)
			AND (id_sub_marca_p = 0 || id_sub_marca = id_sub_marca_p)
			AND (id_producto_p = 0 || id_producto = id_producto_p)
			AND (id_territorio_p = 0 || id_territorio = id_territorio_p)
			AND (id_representante_p = 0 || id_representante = id_representante_p)
			AND (id_madre_p = 0 || id_distribuidora_madre = id_madre_p)
			AND (id_distribuidora_p = 0 || id_distribuidora = id_distribuidora_p)
		   AND id_periodo IN (SELECT id_filtro FROM filtros_indicadores WHERE tipo_filtro = 'periodo' and hash_consulta = hash_consulta_p )
		GROUP BY
			vco.id_periodo
		ORDER BY 
			vco.id_periodo
	
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_ventas_2020;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas_2020 AS (
		
	SELECT
			vco.id_territorio,
			vco.id_periodo_homologado,
			SUM(venta) ventas
		FROM 
			vista_general_ventas_2020 vco
		WHERE
			(id_portafolio_p = 0 || id_portafolio = id_portafolio_p)
			AND (id_categoria_p = 0 || id_categoria_producto = id_categoria_p)
			AND (id_marca_p = 0 || id_marca = id_marca_p)
			AND (id_sub_marca_p = 0 || id_sub_marca = id_sub_marca_p)
			AND (id_producto_p = 0 || id_producto = id_producto_p)
			AND (id_territorio_p = 0 || id_territorio = id_territorio_p)
			AND (id_representante_p = 0 || id_representante = id_representante_p)
			AND (id_madre_p = 0 || id_distribuidora_madre = id_madre_p)
			AND (id_distribuidora_p = 0 || id_distribuidora = id_distribuidora_p)
		   AND id_periodo IN (SELECT id_filtro FROM filtros_indicadores WHERE tipo_filtro = 'periodo' and hash_consulta = hash_consulta_p )
		GROUP BY
			vco.id_periodo
		ORDER BY 
			vco.id_periodo
	
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_impactos_2021;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_impactos_2021 AS (
		
	SELECT
			vco.id_territorio,
			vco.id_periodo,
			SUM(impactos) impactos
		FROM 
			v_impactos_consolidado vco
		WHERE
			(id_territorio_p = 0 || id_territorio = id_territorio_p)
			AND (id_representante_p = 0 || id_representante = id_representante_p)
			AND (id_madre_p = 0 || id_distribuidora_madre = id_madre_p)
			AND (id_distribuidora_p = 0 || id_distribuidora = id_distribuidora_p)
			AND id_periodo IN (SELECT id_filtro FROM filtros_indicadores WHERE tipo_filtro = 'periodo' and hash_consulta = hash_consulta_p )
		GROUP BY
			vco.id_periodo
		ORDER BY 
			vco.id_periodo
	
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_impactos_2020;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_impactos_2020 AS (
		
	SELECT
			vco.id_territorio,
			vco.id_periodo_homologado,
			SUM(venta) impactos
		FROM 
			vista_general_impactos_2020 vco
		WHERE
			 (id_territorio_p = 0 || id_territorio = id_territorio_p)
			AND (id_representante_p = 0 || id_representante = id_representante_p)
			AND (id_madre_p = 0 || id_madre = id_madre_p)
			AND (id_distribuidora_p = 0 || id_distribuidora = id_distribuidora_p)
			AND id_periodo IN (SELECT id_filtro FROM filtros_indicadores WHERE tipo_filtro = 'periodo' and hash_consulta = hash_consulta_p )
		GROUP BY
			vco.id_periodo
		ORDER BY 
			vco.id_periodo
	
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_totales;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_totales AS (
	
		SELECT 
		t_2021.id_periodo,
		per.nombre periodo,	
		round(IFNULL(t_2020.ventas,0)) ventas_2020,
		IFNULL(imp_2020.impactos,0) impactos_2020,
		ROUND(IFNULL((t_2020.ventas/imp_2020.impactos),0),2) dropsize_2020,
		t_2021.ventas ventas_2021,
		imp_2021.impactos impactos_2021,
		ROUND((t_2021.ventas/imp_2021.impactos),2) dropsize_2021,
		IFNULL(ROUND((t_2021.ventas/ifnull(t_2020.ventas,0)-1)*100,2),0) crecimiento_ventas,
		IFNULL(ROUND(((imp_2021.impactos/imp_2020.impactos)-1)*100,2),0) crecimiento_impactos
	 	FROM t_ventas_2021 t_2021
		left JOIN t_ventas_2020 t_2020 ON t_2020.id_periodo_homologado = t_2021.id_periodo
		left JOIN t_impactos_2021 imp_2021 ON imp_2021.id_periodo = t_2021.id_periodo
		left JOIN t_impactos_2020 imp_2020 ON imp_2020.id_periodo_homologado = t_2021.id_periodo
		left JOIN periodo per ON per.id = t_2021.id_periodo 
	
	);
	
	INSERT INTO t_totales
	SELECT 
		1000 id_periodo,
		'Total' periodo,
		sum(t_2020.ventas) ventas_2020,
		sum(imp_2020.impactos) impactos_2020,
		(ROUND((ifnull(sum(t_2020.ventas),0)/sum(imp_2020.impactos)),2)) dropsize_2020,
		sum(t_2021.ventas) ventas_2021,
		sum(imp_2021.impactos) impactos_2021,
		ROUND((sum(t_2021.ventas)/sum(imp_2021.impactos)),2) dropsize_2021,
		ROUND((sum(t_2021.ventas)/ifnull(sum(t_2020.ventas),0)-1)*100,2) crecimiento_ventas,
		ROUND((sum(imp_2021.impactos)/sum(imp_2020.impactos)-1)*100,2) crecimiento_impactos
	 	FROM t_ventas_2021 t_2021 
		left JOIN t_ventas_2020 t_2020 ON t_2020.id_periodo_homologado = t_2021.id_periodo
		left JOIN t_impactos_2021 imp_2021 ON imp_2021.id_periodo = t_2021.id_periodo
		left JOIN t_impactos_2020 imp_2020 ON imp_2020.id_periodo_homologado = t_2021.id_periodo
		left JOIN periodo per ON per.id = t_2021.id_periodo ;  
		
	SELECT * FROM t_totales;
	SELECT * FROM t_ventas_2021;
	SELECT * FROM t_ventas_2020;
	
	DROP TABLE t_totales;
	DROP TABLE t_ventas_2021;
	DROP TABLE t_ventas_2020;
	
END//

CALL sp_indicadores_crecimiento_nuevo(0,0,0,0,0,0,0,0,0,'14eec0f5-8ebc-4829-b0e6-970f7eaf3ff9');