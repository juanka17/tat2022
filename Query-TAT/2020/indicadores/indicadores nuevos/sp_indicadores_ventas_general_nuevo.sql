DROP PROCEDURE IF EXISTS sp_indicadores_ventas_general_nuevo;

DELIMITER //

CREATE PROCEDURE sp_indicadores_ventas_general_nuevo(in id_portafolio_p int, in id_categoria_p int,IN id_marca_p INT,IN id_sub_marca_p INT,IN id_producto_p INT,IN id_territorio_p INT, IN id_representante_p INT,IN id_madre_p INT, in id_distribuidora_p INT, IN hash_consulta_p VARCHAR(50))
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
			vco.id_territorio,
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
			ico.id_territorio,
			ico.id_periodo
		ORDER BY 
			ico.id_territorio
	);
	
	SELECT 
		per.id id_periodo,
		per.nombre periodo,
		ter.id id_territorio,
		ter.nombre territorio,
		ven.ventas,
		inp.impactos,
		round(ven.ventas / inp.impactos , 2) dropsize
	FROM	
		t_ventas_territorios ven
		INNER JOIN territorios ter ON ter.id = ven.id_territorio
		INNER JOIN periodo per ON per.id = ven.id_periodo
		LEFT JOIN t_indicadores_territorios inp ON 
			inp.id_territorio = ven.id_territorio 
			AND inp.id_periodo = ven.id_periodo
	ORDER BY 
		per.id,
		ter.id
		;

END//

call sp_indicadores_ventas_general_nuevo(0,0,0,0,0,0,0,0,0,'bf2cfe51-e219-4574-9261-bbcc062de8da');