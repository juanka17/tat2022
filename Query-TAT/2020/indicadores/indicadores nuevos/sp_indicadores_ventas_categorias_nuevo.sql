DROP PROCEDURE IF EXISTS sp_indicadores_ventas_categorias_nuevo;

DELIMITER //

CREATE PROCEDURE sp_indicadores_ventas_categorias_nuevo(in id_portafolio_p int, in id_categoria_p int,IN id_marca_p INT,IN id_sub_marca_p INT,IN id_producto_p INT,IN id_territorio_p INT, IN id_representante_p INT,IN id_madre_p INT, in id_distribuidora_p INT, IN hash_consulta_p VARCHAR(50))
LANGUAGE SQL
BEGIN

	DROP TEMPORARY TABLE IF EXISTS t_ventas_territorios;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas_territorios AS (
		SELECT
			vco.id_portafolio,
			vco.id_categoria_producto,			
			venta
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
		ORDER BY 
			vco.id_portafolio
	);	
	
	SELECT 
		case
			when id_portafolio = 1 then 'PFE'
			when id_portafolio = 2 then 'GSK'
			when id_portafolio = 3 then 'Sin SKU'
		END portafolio,
		sum(venta) ventas,
		SUM(	CASE WHEN id_categoria_producto = 2	 THEN	venta	ELSE 0	END	)	Analgesicos,
		SUM(	CASE WHEN id_categoria_producto = 3	 THEN	venta	ELSE 0	END	)	Cuidado_Personal,
		SUM(	CASE WHEN id_categoria_producto = 5	 THEN	venta	ELSE 0	END	)  Respiratorios	,
		SUM(	CASE WHEN id_categoria_producto = 8	 THEN	venta	ELSE 0	END	)  Promoción	,
		SUM(	CASE WHEN id_categoria_producto = 9	 THEN	venta	ELSE 0	END	)	Expectorante	,
		SUM(	CASE WHEN id_categoria_producto = 10 THEN	venta	ELSE 0	END	)	Multivitamínicos	,
		SUM(	CASE WHEN id_categoria_producto = 11 THEN	venta	ELSE 0	END	)	Antigripales	,
		SUM(	CASE WHEN id_categoria_producto = 12 THEN	venta	ELSE 0	END	)	N_A,
		SUM(	CASE WHEN id_categoria_producto = 13 THEN	venta	ELSE 0	END	)	Analgesicos_Ninos	,
		SUM(	CASE WHEN id_categoria_producto = 14 THEN	venta	ELSE 0	END	)	Antiemeticos,
		SUM(	CASE WHEN id_categoria_producto = 15 THEN	venta	ELSE 0	END	)	Cuidado_Oral			
	 FROM 
	 	t_ventas_territorios
	 GROUP BY id_portafolio
		;

END//

call sp_indicadores_ventas_categorias_nuevo(0,0,0,0,0,0,0,0,0,'bf2cfe51-e219-4574-9261-bbcc062de8da');