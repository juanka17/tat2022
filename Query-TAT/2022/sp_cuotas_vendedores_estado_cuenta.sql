DROP PROCEDURE IF EXISTS sp_cuotas_vendedores_estado_cuenta;

DELIMITER //

CREATE PROCEDURE sp_cuotas_vendedores_estado_cuenta(id_vendedor_tmp int)
BEGIN
		
		SET @id_vendedor = id_vendedor_tmp;
		
		DROP TEMPORARY TABLE IF EXISTS t_ventas_estado_cuenta;
		CREATE TEMPORARY TABLE IF NOT EXISTS t_ventas_estado_cuenta AS (
		
			SELECT 
				ve.id_periodo ,
				ve.id_vendedor,
				SUM(ve.valor) venta
			FROM
				ventas ve
			WHERE 
				ve.id_periodo >= 14 AND ve.id_vendedor = @id_vendedor
			GROUP BY id_periodo
		
		);
			
			
		DROP TEMPORARY TABLE IF EXISTS t_cuotas_estado_cuenta;
		CREATE TEMPORARY TABLE IF NOT EXISTS t_cuotas_estado_cuenta AS (
		
			SELECT 
			* 
			FROM 
				cuotas_especiales_2022 
			WHERE 
				id_vendedor = @id_vendedor
			
		);

	
		SELECT 
		c.id_vendedor,
		p.id id_periodo,
		p.nombre periodo,
		c.cuota,
		v.venta,
		ROUND((v.venta/c.cuota)*100) cumplimiento
		FROM t_cuotas_estado_cuenta c
		left JOIN t_ventas_estado_cuenta v ON c.id_vendedor = v.id_vendedor AND c.id_periodo = v.id_periodo
		INNER JOIN periodo p ON p.id = c.id_periodo
		ORDER BY 2;
		
DROP TEMPORARY TABLE IF EXISTS t_ventas_estado_cuenta;
DROP TEMPORARY TABLE IF EXISTS t_cuotas_estado_cuenta;

END//

call sp_cuotas_vendedores_estado_cuenta(23630);