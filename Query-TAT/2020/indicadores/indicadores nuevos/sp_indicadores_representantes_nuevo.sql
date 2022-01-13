DROP PROCEDURE IF EXISTS sp_indicadores_representantes_nuevo;

DELIMITER //

CREATE PROCEDURE sp_indicadores_representantes_nuevo(in id_portafolio_p int, in id_categoria_p int,IN id_marca_p INT,IN id_sub_marca_p INT,IN id_producto_p INT,IN id_territorio_p INT, IN id_representante_p INT,IN id_madre_p INT, in id_distribuidora_p INT, IN hash_consulta_p VARCHAR(50))
LANGUAGE SQL
BEGIN

	DROP TEMPORARY TABLE IF EXISTS t_ventas_territorios;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas_territorios AS (
		SELECT
			vco.id_representante,
			vco.id_distribuidora,
			vco.id_distribuidora_madre,
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
			vco.id_representante,
			vco.id_distribuidora
		ORDER BY 
			vco.id_representante
	);
	
	DROP TEMPORARY TABLE IF EXISTS t_indicadores_territorios;
	CREATE TEMPORARY TABLE IF NOT EXISTS t_indicadores_territorios AS (
		SELECT
			ico.id_representante,
			ico.id_distribuidora,
			ico.id_distribuidora_madre,
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
			ico.id_representante,
			ico.id_distribuidora
		ORDER BY 
			ico.id_representante
	);
	
	SELECT 
		rep.id id_representante,
		rep.nombre representante,
		dis.id id_distribuidora,
		dis.nombre distribuidora,
		SUM(ven.ventas) ventas,
		inp.impactos,
		round(ven.ventas / inp.impactos , 2) dropsize
	FROM	
		t_ventas_territorios ven
		INNER JOIN afiliados rep ON rep.id = ven.id_representante
		INNER JOIN almacenes alm ON alm.id = ven.id_distribuidora
		INNER JOIN distribuidora_madre dis ON dis.id = alm.id_madre		
		LEFT JOIN t_indicadores_territorios inp ON 
			inp.id_representante = ven.id_representante 
			AND inp.id_distribuidora = ven.id_distribuidora 
			AND ven.id_distribuidora_madre = ven.id_distribuidora_madre
		GROUP BY rep.id,dis.id
	ORDER BY 
		ven.id_representante,
		ven.ventas desc
		;

END//

call sp_indicadores_representantes_nuevo(0,0,0,0,0,0,0,0,0,'bf2cfe51-e219-4574-9261-bbcc062de8da');