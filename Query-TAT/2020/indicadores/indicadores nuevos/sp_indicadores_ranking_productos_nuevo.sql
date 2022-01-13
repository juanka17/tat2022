DROP PROCEDURE IF EXISTS sp_indicadores_ranking_productos_nuevo;

DELIMITER //

CREATE PROCEDURE sp_indicadores_ranking_productos_nuevo(in id_portafolio_p int, in id_categoria_p int,IN id_marca_p INT,IN id_sub_marca_p INT,IN id_producto_p INT,IN id_territorio_p INT, IN id_representante_p INT,IN id_madre_p INT, in id_distribuidora_p INT, IN hash_consulta_p VARCHAR(50))
LANGUAGE SQL
BEGIN

	DROP TEMPORARY TABLE IF EXISTS t_ventas;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas AS (
		SELECT
			vco.id_producto,
			vco.id_periodo,
			vco.id_marca,
			vco.id_sub_marca,
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
			vco.id_producto,
			vco.id_periodo
	);
	
	SELECT 
		per.id id_periodo,
		mar.nombre marca,
		sma.nombre sub_marca,
		per.nombre periodo,
		pro.id id_producto,
		pro.nombre producto,
		ven.ventas
	FROM	
		t_ventas ven
		INNER JOIN productos pro ON pro.id = ven.id_producto
		INNER JOIN periodo per ON per.id = ven.id_periodo
		INNER JOIN marcas mar ON mar.id = ven.id_marca
		INNER JOIN sub_marca sma ON sma.id = ven.id_sub_marca
	ORDER BY 
		mar.nombre,
		sma.nombre
		;

END//

call sp_indicadores_ranking_productos_nuevo(0,0,0,0,0,0,0,0,0,'bf2cfe51-e219-4574-9261-bbcc062de8da');