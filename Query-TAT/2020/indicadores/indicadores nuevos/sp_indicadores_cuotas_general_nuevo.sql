DROP PROCEDURE IF EXISTS sp_indicadores_cuotas_general_nuevo;

DELIMITER //

CREATE PROCEDURE sp_indicadores_cuotas_general_nuevo(in id_portafolio_p int, in id_categoria_p int,IN id_marca_p INT,IN id_sub_marca_p INT,IN id_producto_p INT,IN id_territorio_p INT, IN id_representante_p INT,IN id_madre_p INT, in id_distribuidora_p INT, IN hash_consulta_p VARCHAR(50))
LANGUAGE SQL
BEGIN

	DROP TEMPORARY TABLE IF EXISTS t_ventas_territorios;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas_territorios AS (
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
			vco.id_territorio
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_indicadores_territorios;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_indicadores_territorios AS (
		SELECT
			ico.id_territorio,
			ico.id_periodo,
			SUM(impactos) impactos
		FROM 
			v_impactos_consolidado ico
		WHERE
			(id_territorio_p = 0 || id_territorio = id_territorio_p)
			AND (id_representante_p = 0 || id_representante = id_representante_p)
			AND (id_madre_p = 0 || id_distribuidora_madre = id_madre_p)
			AND (id_distribuidora_p = 0 || id_distribuidora = id_distribuidora_p)
			AND id_periodo IN (SELECT id_filtro FROM filtros_indicadores WHERE tipo_filtro = 'periodo' and hash_consulta = hash_consulta_p )
		GROUP BY
			ico.id_periodo
		ORDER BY 
			ico.id_territorio
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_cuotas;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_cuotas AS (
		SELECT
			ico.id_territorio,
			ico.id_periodo,
			SUM(cuota) cuotas,
			SUM(cuota_impactos) cuota_impactos
		FROM 
			v_cuotas_consolidado ico
		WHERE
			(id_territorio_p = 0 || id_territorio = id_territorio_p)
			AND (id_representante_p = 0 || id_representante = id_representante_p)
			AND (id_madre_p = 0 || id_distribuidora_madre = id_madre_p)
			AND (id_distribuidora_p = 0 || id_distribuidora = id_distribuidora_p)
			AND id_periodo IN (SELECT id_filtro FROM filtros_indicadores WHERE tipo_filtro = 'periodo' and hash_consulta = hash_consulta_p )
		GROUP BY
			ico.id_periodo
		ORDER BY 
			ico.id_territorio
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_totales;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_totales AS (
	
		SELECT 
			per.id id_periodo,
			per.nombre periodo,
			cuo.cuotas,
			ven.ventas,
			ROUND((ven.ventas/cuo.cuotas)*100,2) cumplimiento_ventas,
		   cuo.cuota_impactos,
			inp.impactos,
			ROUND((inp.impactos/cuo.cuota_impactos)*100,2) cumplimiento_impactos
		FROM	
			t_ventas_territorios ven
			INNER JOIN territorios ter ON ter.id = ven.id_territorio
			INNER JOIN periodo per ON per.id = ven.id_periodo
			INNER JOIN t_indicadores_territorios inp ON inp.id_periodo = ven.id_periodo
			INNER JOIN t_cuotas cuo ON cuo.id_periodo = ven.id_periodo
		ORDER BY 
			per.id
			
	);

	INSERT INTO t_totales
	SELECT 
		1000 id_periodo,
		'Total' periodo,
		SUM(cuo.cuotas) cuotas,
		SUM(ven.ventas) ventas,
		ROUND((SUM(ven.ventas)/SUM(cuo.cuotas))*100,2) cumplimiento_ventas,
	   SUM(cuo.cuota_impactos) cuota_impactos,
		SUM(inp.impactos) impactos,
		ROUND((SUM(inp.impactos)/SUM(cuo.cuota_impactos))*100,2) cumplimiento_impactos
	FROM	
		t_ventas_territorios ven
		INNER JOIN territorios ter ON ter.id = ven.id_territorio
		INNER JOIN periodo per ON per.id = ven.id_periodo
		INNER JOIN t_indicadores_territorios inp ON inp.id_periodo = ven.id_periodo
		INNER JOIN t_cuotas cuo ON cuo.id_periodo = ven.id_periodo
	where
		cuo.cuotas IS NOT NULL 
	ORDER BY 
		per.id;
	
	SELECT * FROM t_totales;

END//

call sp_indicadores_cuotas_general_nuevo(0,0,0,0,0,0,0,0,0,'7ca093e2-034d-44ab-8f0f-3cc416311d55');